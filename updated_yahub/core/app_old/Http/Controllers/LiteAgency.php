<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Auth;
use Mail;
use App\Models\User;
use App\Models\Chat;
use App\Models\Setting;
use Illuminate\Http\Request;

class LiteAgency extends Controller
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
        $chat = Chat::where(['agency_id'=>Auth::user()->id])->get();
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
        $inprogress=Project::where(['user_id'=>Auth::user()->id,'status'=>1])->get();
        $previous=Project::where(['user_id'=>Auth::user()->id,'status'=>2])->get();
        return view('lite-agency.projects',compact('inprogress','previous','projectsLimit'));
    }
    public function search()
    {
        return view('lite-agency.search');
    }
    public function search_ajax(){
        $projects = Project::where('user_id',Auth::user()->id)->when($_POST['query'], function ($q) {
            $q->where(function ($query) {
                $query->where('project_title','like','%'.$_POST['query'].'%')->orWhere('client_name','like','%'.$_POST['query'].'%');
            });
        })->get();
        return json_encode(array("success"=>true,"projects"=>$projects),true);
    }
    public function new_project()
    {
        $users=User::where('role',3)->whereRaw('FIND_IN_SET(id,"'.Auth::user()->assigned_clients.'")',)->get();
        return view('lite-agency.new-project',compact('users'));
    }
    public function agency_inbox()
    {
        $chatLimit = false;
        if($this->ChatLimit()){
            $chatLimit = true;
        }
        $chats=Chat::where(['agency_id'=>Auth::user()->id,'archived'=>0])->limit(Auth::user()->max_conversations_in_inbox)->get();
        return view('lite-agency.inbox',compact('chats','chatLimit'));
    }
    public function delete_chat(Request $request){
        if(isset($_POST['chat_id'])){
            $response = array();
            $chat = Chat::where('id', $_POST['chat_id'])->first();
            $chat->is_deleted=1;
            if($chat->update()){
                $response['success'] = true;
            }
            else{
                $response['error'] = true;
            }
            echo json_encode($response);
        }
    }
    public function agency_archived_inbox()
    {
        $chats=Chat::where(['agency_id'=>Auth::user()->id,'archived'=>1])->get();
        return view('lite-agency.inbox_archived',compact('chats'));
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
    public function deleteAccount()
    {
        $id=Auth::user()->id;
        $projects=Project::where('user_id',$id)->get();
        $chats=Chat::where('agency_id',$id)->get();
        $user=User::where('id',$id)->first();
        $nMail=new Notification();
        $nMail->text=Auth::user()->name."(".Auth::user()->email.") has deleted their account.";
        $nMail->url=url("/");
        $nMailUser=User::where('role',1)->first();
        Mail::to($nMailUser)->send($nMail);
        foreach($projects as $project)
        {
            $project->delete();
        }
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
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function project_show($project_id)
    {
        $project=Project::where('id',$project_id)->first();
        return view('lite-agency.project-show',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function project_edit($project_id)
    {
        $project=Project::where('id',$project_id)->first();
        $users=User::where('role',3)->whereRaw('FIND_IN_SET(id,"'.Auth::user()->assigned_clients.'")',)->get();
        $chat=Chat::where('project_id',$project->id)->first();
        return view('lite-agency.edit-project',compact('project','users','chat'));
    }
    public function cancelAccount()
    {
        $nMail=new Notification();
        $nMail->text=Auth::user()->name." Requested Account Cancellation.";
        $nMail->url=url("/admin/agency/".Auth::user()->id);
        $nMailUser=User::where('role',1)->first();
        Mail::to($nMailUser)->send($nMail);
        if(count(Mail::failures())==0)
        {
            session()->flash('success', "Cancellation Requested Submitted!");
            return redirect()->back();
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function profile()
    {
        $user=User::where('id',Auth::user()->id)->first();
        return view('lite-agency.profile',compact('user'));
    }
    public function profile_update(Request $request){
        $user=User::where('id',Auth::user()->id)->first();
        $check=User::where('email',$request->email)->where('id','<>',Auth::user()->id)->first();
        if($check)
        {
            return redirect()->back()->withErrors('error','Agency with same email already exists!');
        }
        $user->name=$request->input('name');
        $user->username=$request->input('username');
        $user->email=$request->input('email');
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
        if($user->update())
        {
            return redirect()->back()->withSuccess('Profile updated!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function clients_search()
    {
        $users=User::where('role',3)->whereRaw('FIND_IN_SET(id,"'.Auth::user()->assigned_clients.'")',)->get();
        return view('lite-agency.clients',compact('users'));
    }
    public function delete_client($id)
    {
        $projects=Project::where('user_id',$id)->get();
        $chats=Chat::where('client_id',$id)->get();
        $user=User::where('id',$id)->first();
        foreach($projects as $project)
        {
            $project->client_id=0;
            $project->update();
        }
        foreach($chats as $chat)
        {
            $chat->client_id=0;
            $chat->update();
        }
        if($user->delete())
        {
            return redirect()->back()->withSuccess('Client deleted!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function currentAuth()
    {
        $user=User::where('id',Auth::user()->id)->first();
        return $user;
    }
    public function getRole()
    {
        $user=Auth::user();
        if($user->role==2){
            return "agency";
        }else if($user->role==3){
            return "client";
        }
        else if($user->role==1){
            return "admin";
        }else if($user->role==4){
            return "lite-agency";
        }
    }

    /* Mail Methods */
    public function mail(){
        return view('lite-agency.mail');
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
