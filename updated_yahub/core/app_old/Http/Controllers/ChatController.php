<?php

namespace App\Http\Controllers;
use Auth;
use Mail;
use File;
use App\Mail\Notification;
use App\Models\Project;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($id)
    {
        $role=$this->getRole();
        $chat=NULL;
        $receiver_id=NULL;
        $chat=\DB::table('chats')->where('id',$id)->first();
        if($role=="agency" || $role=="lite-agency")
        {
            $receiver_id=$chat->client_id;
        }else{
            $receiver_id=$chat->agency_id;
        }
        $AuthUser=Auth::user();
        $user=User::where('id',$receiver_id)->first();
        $messages=Message::where(['chat_id'=>$chat->id])->get();
        $media=Message::where(['chat_id'=>$chat->id,'is_text'=>0])->get();
        $project=Project::where('id',$chat->project_id)->first();
        return view('chat.index',compact('AuthUser','role','chat','project','receiver_id','messages','user','media'));
    }
    public function backup()
    {
        try{
            $role=$this->getRole();
            $chat=NULL;
            $receiver_id=NULL;
            
            $AuthUser=Auth::user();
            $chats=\DB::table('chats')->where('agency_id',$AuthUser->id)->whereRaw('client_id>0')->where('archived',1)->get();
            
            $zip_file =$AuthUser->id."_chatbackup".date("YmdHis");
            $basePath= base_path();
            $basePath=str_replace("core","",$basePath);
            $zip_path=$basePath."backups/".$zip_file;
            File::ensureDirectoryExists($zip_path);
            $zip = new \ZipArchive();
            $zip->open($zip_path.".zip", \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            foreach($chats as $chat)
            {
                $currentAgencyId="";
                if($role=="lite-agency")
                {
                    
                    $currentAgencyId=$chat->agency_id;
                }
                if($role=="agency" || $role=="lite-agency")
                {
                    $receiver_id=$chat->client_id;
                }else{
                    $receiver_id=$chat->agency_id;
                }
                $user=User::where('id',$receiver_id)->first();
                $messages=Message::where(['chat_id'=>$chat->id])->get();
                $media=Message::where(['chat_id'=>$chat->id,'is_text'=>0])->get();
                $project=Project::where('id',$chat->project_id)->first();
                $file_html=view('chat.backup',compact('AuthUser','currentAgencyId','role','chat','project','receiver_id','messages','user','media'))->render();
                $FileName=$AuthUser->id."_".$project->project_title."_".$user->name.".html";
                $FileName=str_replace(" ","_",$FileName);
                $FileName=str_replace("/","",$FileName);
                File::put($zip_path."/".$FileName,$file_html);
                
            }
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($zip_path));
            foreach ($files as $name => $file)
            {
                // We're skipping all subfolders
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();

                    // extracting filename with substr/strlen
                    $relativePath = 'chats/' . substr($filePath, strlen($zip_path) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }
            $zip->close();
            File::deleteDirectory($zip_path);
            return response()->download($zip_path.".zip");
        }catch(Exception $e)
        {
            return back()->with("error","Unexpected error!");
        }
        
    }
    /* Edit Messages */
    public function chat_edit($id){
        $messages=Message::where('id',$id)->get();
        $res['message'] = $messages;
        echo json_encode($res);
    }
    /* Update Message */
    public function chat_update(Request $request)
    {
        $messages = Message::where('id',$request->input("is_update"))->update([
            'message' => $request->input("message")
        ]);
        if($messages){
            $res['success'] = true;
        }
        else{
            $res['error'] = false;
        }
        echo json_encode($res);
    }

    public function chat_ajax(Request $request)
    {
        $action=$request->input('action');
        $chat_id=$request->input('chat_id');
      
        if($action=="fetch_messages")
        { 
            $receiver_id=$request->input("receiver_id");
            $messages=Message::where(['is_delivered'=>0,'chat_id'=>$chat_id,'sender_id'=>$receiver_id])->get();
            foreach($messages as $message)
            {
                $message->is_delivered=1;
                $message->save();
            }
            $dMessages=Message::where(['is_delivered'=>1,'chat_id'=>$chat_id])->get()->pluck('id');
            return json_encode(array("success"=>true,"message_count"=>count($messages),"messages"=>$messages,"delivered"=>$dMessages),JSON_PRETTY_PRINT);
        }
        $chat_response=array();
        if($action=="send_message")
        {
            $receiver_id=$request->input("receiver_id");
            $sender_id=$request->input("sender_id");
            $message=$request->input("message");
            $newMessage=new Message();
            $newMessage->message=$message;
            $newMessage->sender_id=$sender_id;
            $newMessage->receiver_id=$receiver_id;
            $newMessage->is_text=1;
            $newMessage->chat_id=$chat_id;
            $newMessage->is_delivered=0;
            $newMessage->save();
            if($newMessage)
            {
                $nMail=new Notification();
                $nMail->text=Auth::user()->name." sent you a message about the project.";
                $nMail->url=url("chat/".$chat_id);
                $nMailUser=User::where('id',$receiver_id)->first();
                if($nMailUser->notification_status==1)
                {
                    try{
                        Mail::to($nMailUser)->send($nMail);
                    }catch(\Exception $e)
                    {
                        Log::error($e->getMessage());
                    }
                }
                $chat_response=array("success"=>true,"message"=>$newMessage);
            }else{
                
                $chat_response=array("success"=>false,"error"=>"Something went wrong!");
            }
        }
        if($action=="send_image")
        {
            $receiver_id=$request->input("receiver_id");
            $sender_id=$request->input("sender_id");
            $file = $request->file('sendImage');
            $destinationPath = 'uploads/chat';
            $mime=$file->getMimeType();
            $fileName= preg_replace( '/[^a-z0-9]+/', '-', strtolower( $file->getClientOriginalName()) ).".".$file->getClientOriginalExtension();
            $file->move($destinationPath,$fileName);
            $newMessage=new Message();
            $newMessage->message=asset($destinationPath."/".$fileName);
            $newMessage->sender_id=$sender_id;
            $newMessage->receiver_id=$receiver_id;
            $newMessage->is_text=0;
            $newMessage->is_image=1;
            $newMessage->chat_id=$chat_id;
            $newMessage->is_delivered=0;
            $newMessage->mime_type=$mime;
            $newMessage->save();
            if($newMessage)
            {
                $nMail=new Notification();
                $nMail->text=Auth::user()->name." sent you a image the project.";
                $nMail->url=url("chat/".$chat_id);
                $nMailUser=User::where('id',$receiver_id)->first();
                if($nMailUser->notification_status==1)
                {
                    try{
                        Mail::to($nMailUser)->send($nMail);
                    }catch(\Exception $e)
                    {
                        Log::error($e->getMessage());
                    }
                }
                $chat_response=array("success"=>true,"message"=>$newMessage);
            }else{
                
                $chat_response=array("success"=>false,"error"=>"Something went wrong!");
            }
        }
        if($action=="send_file")
        {
            $success=array();
            foreach($request->file('sendFile') as $file)
            {
                $receiver_id=$request->input("receiver_id");
                $sender_id=$request->input("sender_id");
                $destinationPath = 'uploads/chat';
                $mime=$file->getMimeType();
                $fileName= preg_replace( '/[^a-z0-9]+/', '-', strtolower( $file->getClientOriginalName()) ).".".$file->getClientOriginalExtension();
                $file->move($destinationPath,$fileName);
                $newMessage=new Message();
                $newMessage->message=asset($destinationPath."/".$fileName);
                $newMessage->sender_id=$sender_id;
                $newMessage->receiver_id=$receiver_id;
                $newMessage->is_text=0;
                $newMessage->is_file=1;
                $newMessage->chat_id=$chat_id;
                $newMessage->is_delivered=0;
                $newMessage->mime_type=$mime;
                $newMessage->save();
                if($newMessage)
                {
                    $nMail=new Notification();
                    $nMail->text=Auth::user()->name." sent you a file about the project.";
                    $nMail->url=url("chat/".$chat_id);
                    $nMailUser=User::where('id',$receiver_id)->first();
                    if($nMailUser->notification_status==1)
                    {
                        try{
                            Mail::to($nMailUser)->send($nMail);
                        }catch(\Exception $e)
                        {
                            Log::error($e->getMessage());
                        }
                    }
                    $success[]=$newMessage;
                   
                }
            }
            if(!empty($success))
            {
                $chat_response=array("success"=>true,"messages"=>$success);
            }
            else{
                $chat_response=array("success"=>false,"error"=>"Something went wrong!");
            }
        }
        if($action=="send_video")
        {
            $receiver_id=$request->input("receiver_id");
            $sender_id=$request->input("sender_id");
            $file = $request->file('sendVideo');
            $destinationPath = 'uploads/chat';
            $mime=$file->getMimeType();
            $fileName= preg_replace( '/[^a-z0-9]+/', '-', strtolower( $file->getClientOriginalName()) ).".".$file->getClientOriginalExtension();
            $file->move($destinationPath,$fileName);
            $newMessage=new Message();
            $newMessage->message=asset($destinationPath."/".$fileName);
            $newMessage->sender_id=$sender_id;
            $newMessage->receiver_id=$receiver_id;
            $newMessage->is_text=0;
            $newMessage->is_video=1;
            $newMessage->chat_id=$chat_id;
            $newMessage->is_delivered=0;
            $newMessage->mime_type=$mime;
            $newMessage->save();
            if($newMessage)
            {
                $nMail=new Notification();
                $nMail->text=Auth::user()->name." sent you a video about the project.";
                $nMail->url=url("chat/".$chat_id);
                $nMailUser=User::where('id',$receiver_id)->first();
                if($nMailUser->notification_status==1)
                {
                    try{
                        Mail::to($nMailUser)->send($nMail);
                    }catch(\Exception $e)
                    {
                        Log::error($e->getMessage());
                    }
                }
                $chat_response=array("success"=>true,"message"=>$newMessage);
            }else{
                
                $chat_response=array("success"=>false,"error"=>"Something went wrong!");
            }
        }
        return json_encode($chat_response,JSON_PRETTY_PRINT);
    }
    public function getRole()
    {
        if(Auth::user()->role==4)
        {
            return "lite-agency";
        }else if(Auth::user()->role==2){
            return "agency";
        }else if(Auth::user()->role==3){
            return "client";
        }
        else if(Auth::user()->role==1){
            return "admin";
        }
    }
}
