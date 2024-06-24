<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use App\Models\Project;
use App\Models\Chat;
use App\Models\User;
use App\Models\Setting;
use App\Mail\Notification;
use Illuminate\Http\Request;

class Client extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
       
    }
    public function ProjectsLimit(){
        $projects = Project::where(['user_id'=>Auth::user()->id])->get();
        if(count($projects) >= Auth::user()->max_projects_per_client){
            return true;
        }
    }
    public function ChatLimit(){
        $chat = Chat::where(['client_id'=>Auth::user()->id])->get();
        if(count($chat) >= Auth::user()->max_conversations_in_inbox){
            return true;
        }
    }
    public function index()
    {
        $projectsLimit = false;
        if($this->ProjectsLimit()){
            $projectsLimit = true;
        }
        $inprogress=\DB::table('projects')
        ->join('chats','projects.id','=','chats.project_id')
        ->where('projects.status','=',1)
        ->where('chats.client_id','=',Auth::user()->id)
        ->get();
        $previous=\DB::table('projects')
        ->join('chats','projects.id','=','chats.project_id')
        ->where('projects.status','=',2)
        ->where('chats.client_id','=',Auth::user()->id)
        ->get();
        return view('client.projects',compact('inprogress','previous','projectsLimit'));
    }
    public function search()
    {
        return view('client.search');
    }
    public function search_ajax(Request $request)
    {
        $projects=\DB::table('projects')
        ->join('chats','projects.id','=','chats.project_id')
        ->where('chats.client_id','=',Auth::user()->id)
        ->where('project_title','like','%'.$request->input('query').'%')
        ->get();
        return json_encode(array("success"=>true,"projects"=>$projects),true);
    }
    public function start_chat($id)
    {
        $project=Project::where(['id'=>$id])->first();
        if($project){
            $chat=new Chat();
            $chat->agency_id=$project->user_id;
            $chat->project_id=$project->id;
            $chat->client_id=Auth::user()->id;
            $chat->save();
            if($chat)
            {
                return redirect(route($this->getRole().'-dashboard'))->withSuccess('Chat has started!');
            }
            return redirect()->back()->withErrors('error','Something went wrong!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function client_inbox()
    {
        $chatLimit = false;
        if($this->ChatLimit()){
            $chatLimit = true;
        }
        $chats=Chat::where('client_id',Auth::user()->id)->where('is_deleted',0)->where('archived',0)->limit(Auth::user()->max_conversations_in_inbox)->get();
        return view('client.inbox',compact('chats','chatLimit'));
    }
    public function client_archived_inbox()
    {
        $chats=Chat::where(['client_id'=>Auth::user()->id,'archived'=>1])->get();
        return view('client.inbox_archived',compact('chats'));
    }
    public function archive_post(Request $request){
        if(isset($_POST['chat_id'])){
            $response = array();
            $chat = Chat::where('id', $_POST['chat_id'])->first();
            $chat->archived = 1;
            $chat->save();
            if($chat){
                $response['success'] = true;
            }
            else{
                $response['error'] = true;
            }
            echo json_encode($response);
        }
    }
    public function unarchive_post(Request $request){
        if(isset($_POST['chat_id'])){
            $response = array();
            $chat = Chat::where('id', $_POST['chat_id'])->first();
            $chat->archived = 0;
            $chat->save();
            if($chat){
                $response['success'] = true;
            }
            else{
                $response['error'] = true;
            }
            echo json_encode($response);
        }
    }
    public function profile()
    {
        $user=User::where('id',Auth::user()->id)->first();
        return view('client.profile',compact('user'));
    }
    public function deleteAccount()
    {
        $id=Auth::user()->id;
        $chats=Chat::where('client_id',$id)->get();
        $user=User::where('id',$id)->first();
        $nMail=new Notification();
        $nMail->text=Auth::user()->name."(".Auth::user()->email.") has deleted their account.";
        $nMail->url=url("/");
        $nMailUser=User::where('role',1)->first();
        Mail::to($nMailUser)->send($nMail);
        foreach($chats as $chat)
        {
            $chat->delete();
        }
        if($user->delete())
        {
            session()->flash('account_deleted',true);
            return redirect(url('/'));
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function cancelAccount()
    {
        $nMail=new Notification();
        $nMail->text=Auth::user()->name." Requested Account Cancellation.";
        $nMail->url=url("/admin/client/".Auth::user()->id);
        $nMailUser=User::where('role',1)->first();
        Mail::to($nMailUser)->send($nMail);
        if(count(Mail::failures())==0)
        {
            session()->flash('success', "Cancellation Requested Submitted!");
            return redirect()->back();
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function profile_update(Request $request)
    {
        $user=User::where('id',Auth::user()->id)->first();
        $user->name=$request->input('name');
        $user->username=$request->input('username');
        $user->notification_status=$request->input('notification_status');
        if(!empty($request->input("password")))
        {
            $user->password=\Hash::make($request->input('password'));
        }
        if($request->file('profile_picture')){
            $file = $request->file('profile_picture');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $user->profile_picture = asset("uploads/".$filename);
        }
        $user->save();
        if($user)
        {
            return redirect()->back()->withSuccess('Profile updated!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function getRole()
    {
        if(Auth::user()->role==2){
            return "agency";
        }else if(Auth::user()->role==3){
            return "client";
        }
        else if(Auth::user()->role==1){
            return "admin";
        }
    }

    /* Mail Methods */
    public function mail(){
        return view('client.mail');
    }

    /* Send Mail */
    public function sendmail(Request $request){
        try{
            $files = [];
            if($request->file('images')){
                foreach($request->file('images') as $file){
                    $filename= date('YmdHi').rand(10000,100000000).'.'.$file->getClientOriginalExtension();
                    $file->move('uploads', $filename);
                    array_push($files,asset('uploads/'.$filename));
                }
            }
            $data["email"] = $request->mail_to;
            $data["title"] = $request->title;
            $data["body"] = $request->description;
            
            Mail::send('emails.email', $data, function($message)use($data, $files){
                // $message->from(Auth::user()->email, Auth::user()->name)
                        $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                if(count($files)){
                    foreach ($files as $file){
                        $message->attach($file);
                    }
                }
            });
            return redirect()->back()->withSuccess('Mail Sent Successfully!');
        }
        catch(Throwable $e){
            return $e;
        }
    }
}
