<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Project;
use App\Models\Chat;
use App\Models\Setting;
use Illuminate\Http\Request;
use File;
use Auth;

class Admin extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
       
    }
    public function index()
    {
        $agencies=User::where('role',2)->get();
        return view('admin.agencies',compact('agencies'));
    }

    public function usage($user_id){
        $user = User::find($user_id);
        $maxLimit = $user->max_projects_per_client;
        $projects = Project::where(['user_id'=>$user_id])->get();
        return view('admin.usage',compact('maxLimit','projects'));
    }
    public function agencyThemeSetting($userId,$value){
        $client=User::where('id',$userId)->first();
        $client->theme_setting = $value;
        if($client->update()){
            return redirect()->back()->withSuccess('Updated Theme Setting Options!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function changeTheme(Request $request,$id){
        $setting_json = json_encode($request->except(['_token']));
        $user = User::where('id',$id)->first();
        $user->theme_style = $setting_json;
        if($user->update()){
            return true;
        }
    }

    public function changeLogo(Request $request,$id){
        $user = User::where('id',$id)->first();
        $old_image = $user->theme_log;
        if($request->file('logo')){
            $file= $request->file('logo');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $user->theme_log= asset("uploads/".$filename);
            if($user->update()){
                if($old_image){
                    unlink($old_image);
                }
                return true;
            }
        }
    }
    public function removeLogo($id){
        $user = User::where('id',$id)->first();
        $user->theme_log = null;
        if($user->update()){
            return true;
        }
    }
    public function manage_agency($id)
    {
        $agency=User::where('id',$id)->first();
        return view('admin.agency-edit',compact('agency'));
    }
    public function update_agency(Request $request,$id)
    {
        $agency=User::where('id',$id)->first();
        $agency->name=$request->name;
        if($request->file('profile_picture')){
            $file = $request->file('profile_picture');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $agency->profile_picture = asset("uploads/".$filename);
        }
        if($agency->update())
        {
            return redirect()->back()->withSuccess('Agency updated!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function lite_agecnies()
    {
        $agencies=User::where('role',4)->get();
        return view('admin.lite-agencies',compact('agencies'));
    }
    public function liteAgenciesThemeSetting($userId,$value){
        $client=User::where('id',$userId)->first();
        $client->theme_setting = $value;
        if($client->update()){
            return redirect()->back()->withSuccess('Updated Theme Setting Options!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function manage_lite_agency($id)
    {
        $agency=User::where('id',$id)->first();
        $assigned_clients=explode(",",$agency->assigned_clients);
        $clients=User::where('role',3)->get();
        return view('admin.lite-agency-edit',compact('agency','clients','assigned_clients'));
    }
    public function update_lite_agency(Request $request,$id)
    {
        $lite=User::where('id',$id)->first();
        $lite->assigned_clients=implode(",",$request->assigned_clients);
        $lite->name=$request->name;
        $lite->upload_limit_in_mbs=$request->upload_limit_in_mbs;
        $lite->max_conversations_in_inbox=$request->max_conversations_in_inbox;
        $lite->max_projects_per_client=$request->max_projects_per_client;
        if($request->file('profile_picture')){
            $file = $request->file('profile_picture');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $lite->profile_picture = asset("uploads/".$filename);
        }
        if($lite->update())
        {
            return redirect()->back()->withSuccess('Lite Agency updated!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function clients()
    {
        $users=User::where('role',3)->get();
        return view('admin.clients',compact('users'));
    }
    public function userThemeSetting($userId,$value){
        $client=User::where('id',$userId)->first();
        $client->theme_setting = $value;
        if($client->update()){
            return redirect()->back()->withSuccess('Updated Theme Setting Options!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function edit_client($id)
    {
        $client=User::where('id',$id)->first();
        return view('admin.client-edit',compact('client'));
    }
    public function update_client(Request $request,$id)
    {
        $client=User::where('id',$id)->first();
        $client->name=$request->name;
        $client->upload_limit_in_mbs=$request->upload_limit_in_mbs;
        $client->max_conversations_in_inbox=$request->max_conversations_in_inbox;
        $client->max_projects_per_client=$request->max_projects_per_client;
        if($request->file('profile_picture')){
            $file = $request->file('profile_picture');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $client->profile_picture = asset("uploads/".$filename);
        }
        if($client->update())
        {
            return redirect()->back()->withSuccess('Client updated!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function projects()
    {
        $projects=Project::all();
        return view('admin.projects',compact('projects'));
    }
    public function agency_projects($id)
    {
        $projects=Project::where('user_id',$id)->get();
        return view('admin.projects',compact('projects'));
    }
    public function settings()
    {
        $settings=Setting::all();
        return view('admin.settings',compact('settings'));
    }
    public function update_settings(Request $request)
    {
        $settings_post=$request->input('settings');
        if(!empty($settings_post) && is_array($settings_post))
        {
            foreach($settings_post as $key=>$value)
            {
                $setting=Setting::where('key',$key)->first();
                $setting->value=$value;
                $setting->update();
            }
        }
        return redirect()->back()->withSuccess('Settings updated!');
    }
    public function delete_agency($id)
    {
        $projects=Project::where('user_id',$id)->get();
        $chats=Chat::where('agency_id',$id)->get();
        $user=User::where('id',$id)->first();
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
            return redirect()->back()->withSuccess('Agency deleted!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function unlink_client($chat_id,$project_id)
    {
        $project=Project::where('id',$project_id)->first();
        $chat=Chat::where('id',$chat_id)->first();
        $project->client_name="";
        $chat->client_id=0;
        if($chat->update() && $project->update())
        {
            return redirect()->back()->withSuccess('Agency deleted!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function delete_client($id)
    {
        $chats=Chat::where('client_id',$id)->get();
        $user=User::where('id',$id)->first();
        foreach($chats as $chat)
        {
            $chat->delete();
        }
        if($user->delete())
        {
            return redirect()->back()->withSuccess('Client deleted!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function approve_agency($id)
    {
        $user=User::where('id',$id)->first();
        $user->is_active=1;
        if($user->update())
        {
            return redirect()->back()->withSuccess('Agency approved!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }
    public function changeNotificationStatus($id)
    {
        $user=User::where('id',$id)->first();
        if($user->notification_status==1){
            $user->notification_status=0;
            if($user->update())
            {
                return redirect()->back()->withSuccess($user->notification_status==1 ? 'Notifications on for '.$user->name : 'Notifications off for '.$user->name);
            }
        }
        if($user->notification_status==0){
            $user->notification_status=1;
            if($user->update())
            {
                return redirect()->back()->withSuccess($user->notification_status==1 ? 'Notifications on for '.$user->name : 'Notifications off for '.$user->name);
            }
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }

    /* Mail Setting */
    public function agencyEmailSetting($id,$value){
        $user=User::where('id',$id)->first();
        $user->email_module=(int)$value;
        if($user->update())
        {
            return redirect()->back()->withSuccess('Email Module Changed!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }

    public function liteagencyEmailSetting($id,$value){
        $user=User::where('id',$id)->first();
        $user->email_module=(int)$value;
        if($user->update())
        {
            return redirect()->back()->withSuccess('Email Module Changed!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }

    public function userEmailSetting($id,$value){
        $user = User::where('id',$id)->first();
        $user->email_module = (int)$value;
        if($user->update())
        {
            return redirect()->back()->withSuccess('Email Module Changed!');
        }
        return redirect()->back()->withErrors('error','Something went wrong!');
    }

    public function directory(){
        if(Auth::user()->role==2){
            return 'agency';
        }
        if(Auth::user()->role==3){
            return 'client';
        }
        if(Auth::user()->role==4){
            return 'lite-agency';
        }
    }

    /* Contacts */
    
    public function contacts(){
        $contacts = \DB::table('contacts')->where('user_id',Auth::user()->id)->get();
        return view($this->directory().'.contacts.list',compact('contacts'));
    }

    public function add_contact(){
        $companies = \DB::table('companies')->where('user_id',Auth::user()->id)->get();
        return view($this->directory().'.contacts.add',compact('companies'));
    }

    public function store_contact(Request $request){
        $profile_picture = '';
        if($request->file('profile')){
            $file = $request->file('profile');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $profile_picture = asset("uploads/".$filename);
        }
        $res = \DB::table('contacts')->insert([
            "user_id" => Auth::user()->id,
            "company_id" => $request->company_id,
            "profile_picture" => $profile_picture,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "job_title" => $request->title
        ]);
        if($res)
        {
            return redirect('/contacts')->withSuccess('New Contact Saved!');
        }
        return redirect()->back()->withErrors('error','Contact Cannot Save!');
    }

    public function edit_contact($id){
        $companies = \DB::table('companies')->where('user_id',Auth::user()->id)->get();
        $contact = \DB::table('contacts')->where('id',$id)->first();
        return view($this->directory().'.contacts.edit',compact('contact','companies'));
    }

    public function update_contact(Request $request){
        $contact = \DB::table('contacts')->where('id',$request->id)->first();
        $profile_picture = $contact->profile_picture;
        if($request->file('profile')){
            $file = $request->file('profile');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $profile_picture = asset("uploads/".$filename);
        }
        $res = \DB::table('contacts')->where('id',$request->id)->update([
            "company_id" => $request->company_id,
            "user_id" => Auth::user()->id,
            "profile_picture" => $profile_picture,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,
            "job_title" => $request->title
        ]);
        if($res)
        {
            return redirect('/contacts')->withSuccess('Contact Updated!');
        }
        return redirect()->back()->withErrors('error','Contact Cannot Update!');
    }

    public function delete_contact($id){
        $res = \DB::table('contacts')->where('id',$id)->delete();
        if($res)
        {
            return redirect('/contacts')->withSuccess('Contact Deleted!');
        }
        return redirect()->back()->withErrors('error','Contact Cannot Delete!');
    }
    

    /* End Contacts */

    /* companies */

    public function companies(){
        $companies = \DB::table('companies')->where('user_id',Auth::user()->id)->get();
        return view($this->directory().'.companies.list',compact('companies'));
    }

    public function add_company(){
        return view($this->directory().'.companies.add');
    }

    public function store_company(Request $request){
        $profile_picture = '';
        if($request->file('profile')){
            $file = $request->file('profile');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $profile_picture = asset("uploads/".$filename);
        }
        $res = \DB::table('companies')->insert([
            "user_id" => Auth::user()->id,
            "profile_picture" => $profile_picture,
            "name" => $request->name
        ]);
        if($res)
        {
            return redirect('/companies')->withSuccess('New Company Saved!');
        }
        return redirect()->back()->withErrors('error','Company Cannot Save!');
    }

    public function edit_company($id){
        $company = \DB::table('companies')->where('id',$id)->first();
        return view($this->directory().'.companies.edit',compact('company'));
    }

    public function update_company(Request $request){
        $company = \DB::table('companies')->where('id',$request->id)->first();
        $profile_picture = $company->profile_picture;
        if($request->file('profile')){
            $file = $request->file('profile');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $profile_picture = asset("uploads/".$filename);
        }
        $res = \DB::table('companies')->where('id',$request->id)->update([
            "user_id" => Auth::user()->id,
            "profile_picture" => $profile_picture,
            "name" => $request->name
        ]);
        if($res)
        {
            return redirect('/companies')->withSuccess('Company Updated!');
        }
        return redirect()->back()->withErrors('error','Company Cannot Update!');
    }

    public function delete_company($id){
        $res = \DB::table('companies')->where('id',$id)->delete();
        if($res)
        {
            return redirect('/companies')->withSuccess('Company Deleted!');
        }
        return redirect()->back()->withErrors('error','Company Cannot Delete!');
    }
    

    /* End companies */

    /* Tasks */

    public function tasks(){
        $contacts_list = \DB::table('contacts')->where('user_id',Auth::user()->id)->get();
        $tasks = \DB::table('tasks')->where('user_id',Auth::user()->id)->get();
        return view($this->directory().'.tasks.list',compact('tasks','contacts_list'));
    }

    public function store_task(Request $request){
        $res = \DB::table('tasks')->insert([
            "user_id" => Auth::user()->id,
            "contact_id" => $request->contact_id,
            "title" => $request->task,
            "status" => $request->status,
            "end_date" => $request->end_date
        ]);
        if($res)
        {
            return redirect()->back()->withSuccess('New Task Saved!');
        }
        return redirect()->back()->withErrors('error','Task Cannot Save!');
    }

    public function update_task(Request $request){
        $res = \DB::table('tasks')->where('id',$request->id)->update([
            "user_id" => Auth::user()->id,
            "contact_id" => $request->contact_id,
            "title" => $request->task,
            "status" => $request->status,
            "end_date" => $request->end_date
        ]);
        if($res)
        {
            return redirect()->back()->withSuccess('Task Updated!');
        }
        return redirect()->back()->withErrors('error','Task Cannot Update!');
    }

    public function delete_task($id){
        $res = \DB::table('tasks')->where('id',$id)->delete();
        if($res)
        {
            return redirect()->back()->withSuccess('Task Deleted!');
        }
        return redirect()->back()->withErrors('error','Task Cannot Delete!');
    }
    

    /* End Tasks */

    /* CRM Module */
    public function crm_module($userId,$val){
        $user = User::find($userId);
        $user->enable_crm = (int)$val;
        if($user->save())
        {
            return redirect()->back()->withSuccess('CRM Module Updated!');
        }
        return redirect()->back()->withErrors('error','CRM Module Cannot Update!');
    }
}
?>
