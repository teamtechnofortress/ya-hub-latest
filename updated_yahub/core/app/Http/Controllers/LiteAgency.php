<?php

namespace App\Http\Controllers;
use App\Models\Project;
use Auth;
// use Mail;
use App\Models\User;
use App\Models\Chat;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use App\Models\MainTemplateforDep;
use App\Models\NoteTemplateforDep;
use App\Models\Refrencetable;
use Illuminate\Support\Facades\DB;

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
        return redirect()->back()->withErrors('Something went wrong!');
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
        return redirect()->back()->withErrors('Something went wrong!');
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
        return redirect()->back()->withErrors('Something went wrong!');
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
        return redirect()->back()->withErrors('Something went wrong!');
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
            
            $res=Mail::send('emails.email', $data, function($message)use($data, $files){
                // $message->from(Auth::user()->email, Auth::user()->name)
                        $message->to($data["email"], $data["email"])
                        ->subject($data["title"]);
                if(count($files)){
                    foreach ($files as $file){
                        $message->attach($file);
                    }
                }
            });
            return $res;
            return redirect()->back()->withSuccess('Mail Sent Successfully!');
        }
        catch(Throwable $e){
            return $e;
        }
    }



        /********** Invoices  ************/

    /* Invoices */
    public function invoices(){

        $month = request('month');
        $year = request('year');
        $year = (int)$year;

        $invoicesQuery = \DB::table('template_data')->where(['template_id'=>1,'user_id'=> Auth::user()->id]);
        if($month){
            $startMonth = Carbon::parse($month)->startOfMonth();
            $endMonth = Carbon::parse($month)->endOfMonth();
            $invoicesQuery->whereBetween('created_at', [$startMonth, $endMonth]);
        }
        $project_id = request('project');
        if($project_id){
            $invoicesQuery->where('project_id',$project_id);
        }
        if($year){
            $invoicesQuery->whereYear('created_at', '>=', $year)->whereYear('created_at', '<=', $year);
        }
        $invoices = $invoicesQuery->get();
        return view('lite-agency.invoices.invoices',compact('invoices'));
    }

    /* Add New Invoice */
    public function addInvoice(){
        $template = \DB::table('templates')->where('id',1)->get();

        $dept = DB::table('departments')
            ->where('created_by', Auth::user()->id)
            ->where('active', 1)
            ->first();

        $temp = MainTemplateforDep::where('depid',$dept->id)->get();
        $notetemp = NoteTemplateforDep::where('depid',$dept->id)->where('notefor','invoice')->get();

        return view('lite-agency.invoices.addInvoice',compact('template','temp','notetemp'));
    }

    /* Add post new Invoice */
    public function addNewInvoice(Request $request){
        try{
            $check = \DB::table('template_data')->where(['user_id'=>Auth::user()->id,'template_id',1])->count();
            if($check < 1){
                $total = 0;
                if($request->has('item') && count($request->item) > 0){
                    foreach($request->total_gross as $key=>$value){
                        $total = (float)$total + (float)$value;
                    }
                }
                $signature = '';
                if($request->file('seller_signature')){
                    $file = $request->file('seller_signature');
                    $filename = date('YmdHi').$file->getClientOriginalName();
                    $file->move('uploads', $filename);
                    $signature = asset("uploads/".$filename);
                }

                $temp = MainTemplateforDep::where('id',$request->input('maintempid'))->first();
                $notetemp = NoteTemplateforDep::where('id',$temp->Notesforinvoice)->first();
                $check = $temp->refnumber;
                // $extractedDigits = substr($check, 0, -3);

                    // Extract the numeric part from the end of the string
                    $numericPart = substr($check, -3);
                    
                    // Increment the numeric part
                    $newNumericPart = (int)$numericPart + 1;
                    
                    // Construct the new refnumber with the incremented numeric part
                    $newref = substr($check, 0, -3) . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
                    
                    // Update the $temp object with the new refnumber
                    $temp->refnumber = $newref;
                    $temp->save();

                // $template_id = \DB::table('template_data')->insertGetId([
                //     "user_id" => Auth::user()->id,
                //     "template_id" => 1,
                //     "invoice_no" => $request->invoice_no,
                //     "date_desc" => $request->date_desc,
                //     "info" => $request->info,
                //     "info2" => $request->info2,
                //     "table_title" => $request->table_title,
                //     "payment_type" => $request->payment_type,
                //     "due_date" => $request->due_date,
                //     "total" => $total,
                //     "notes" => $request->notes,
                //     "seller_signature" => $signature,
                //     "footer" => $request->footer,
                //     "is_saved" => 1
                // ]);
                $template_id = \DB::table('template_data')->insertGetId([
                    "user_id" => Auth::user()->id,
                    "template_id" => 1,
                    // "invoice_no" => $request->invoice_no,
                    "date_desc" => $request->date_desc,
                    "info" => $request->info,
                    "info2" => $request->info2,
                    "total" => $total,
                    "table_title" => $request->table_title,
                    "payment_type" => $temp->paymentType,
                    "due_date" => $temp->dueDate,
                    "notes" => $notetemp->note,
                    "invoice_no" => $temp->refnumber,
                    "seller_signature" => $signature,
                    "footer" => $request->footer,
                    "is_saved" => 1,
                    "maintempid" => $request->input('maintempid'),
                    "Notesforinvoice" => $notetemp->id
                ]);

                $dept = DB::table('departments')
                    ->where('created_by', Auth::user()->id)
                    ->where('active', 1)
                    ->first();

                $refrancetable = new Refrencetable;
                $refrancetable->tmpidfromtemptable = $template_id;
                $refrancetable->depid = $dept->id;
                $refrancetable->maintempid = $request->input('maintempid');
                $refrancetable->notetempid = $request->input('notestempid');
                $refrancetable->user_id = Auth::user()->id;
                $refrancetable->save();

                if($request->has('item') && count($request->item) > 0){
                    foreach($request->item as $key=>$val){
                        $res = \DB::table('template_items')->insert([
                            "data_id" => $template_id,
                            "item" => $request->item[$key],
                            "ref" => $request->ref[$key],
                            "qty" => $request->qty[$key],
                            "unit_price" => $request->unit_price[$key],
                            "total_net" => $request->total_net[$key],
                            "vat" => $request->vat[$key],
                            "vat_amount" => $request->vat_amount[$key],
                            "total_gross" => $request->total_gross[$key]
                        ]);
                    }
                    if($res){
                        return redirect()->back()->withSuccess('Document saved!');
                    }
                    else{
                        return redirect()->back()->withErrors('Something went wrong!');
                    }
                }
                else{
                    return redirect()->back()->withSuccess('Document saved!');
                }
            }
            else{
                return redirect()->back()->withErrors('error','You can only create one document!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /* Edit Invoice */
    public function editInvoices($invoiceId){
        $template_data = \DB::table('template_data')->where(['id'=>$invoiceId,'user_id' => Auth::user()->id])->get();
        if($template_data[0]->is_saved==0){
            $template = \DB::table('templates')->where('id',$template_data[0]->template_id)->get();
        }
        else{ $template = $template_data;}
        $template_items = \DB::table('template_items')->where('data_id',$invoiceId)->get();
        return view('lite-agency.invoices.editInvoice',compact('template_data','template','template_items'));
    }

    /* View Invoice */
    public function viewInvoice($invoiceId){
        $template_data = \DB::table('template_data')->where(['id'=>$invoiceId,'user_id' => Auth::user()->id])->get();
        if($template_data[0]->is_saved==0){
            $template = \DB::table('templates')->where('id',$template_data[0]->template_id)->get();
        }
        else{ $template = $template_data;}
        $template_items = \DB::table('template_items')->where('data_id',$invoiceId)->get();
        return view('lite-agency.invoices.viewInvoice',compact('template_data','template','template_items'));
    }

    /* Save Invoice */
    public function saveInvoice(Request $request){
        try{
            $total = 0;
            if($request->has('item') && count($request->item) > 0){
                foreach($request->total_gross as $key=>$value){
                    $total = (float)$total + (float)$value;
                }
            }
            $templateData = \DB::table('template_data')->where('id',$request->invoice_id)->get();
            $signature = $templateData[0]->seller_signature;
            if($request->file('seller_signature')){
                $file = $request->file('seller_signature');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move('uploads', $filename);
                $signature = asset("uploads/".$filename);
            }
            if($request->maintempid)
            {
                
                $temp = MainTemplateforDep::where('id',$request->input('maintempid'))->first();
                // return $temp;
                $notetemp = NoteTemplateforDep::where('id',$temp->Notesforinvoice)->first();
                $check = $temp->refnumber;
                 // $extractedDigits = substr($check, 0, -3);

                // Extract the numeric part from the end of the string
                $numericPart = substr($check, -3);
                
                // Increment the numeric part
                $newNumericPart = (int)$numericPart + 1;
                
                // Construct the new refnumber with the incremented numeric part
                $newref = substr($check, 0, -3) . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
                
                // Update the $temp object with the new refnumber
                $temp->refnumber = $newref;
                $temp->save();

                $template_data = \DB::table('template_data')->where('id',$request->invoice_id)->update([
                    "invoice_no" => $temp->refnumber,
                    "date_desc" => $request->date_desc,
                    "info" => $request->info,
                    "info2" => $request->info2,
                    "table_title" => $request->table_title,
                    "payment_type" => $request->payment_type,
                    "total" => $total,
                    "due_date" => $temp->dueDate,
                    "notes" => $notetemp->note,
                    "seller_signature" => $signature,
                    "footer" => $request->footer,
                    "is_saved" => 1,
                    "maintempid" => $request->maintempid,
                    "Notesforinvoice" => $notetemp->id
                ]);
                
            }
            else {
                $template_data = \DB::table('template_data')->where('id',$request->invoice_id)->update([
                    "invoice_no" => $request->invoice_no,
                    "date_desc" => $request->date_desc,
                    "info" => $request->info,
                    "info2" => $request->info2,
                    "table_title" => $request->table_title,
                    "payment_type" => $request->payment_type,
                    "total" => $total,
                    "due_date" => $request->due_date,
                    "notes" => $request->notes,
                    "seller_signature" => $signature,
                    "footer" => $request->footer,
                    "is_saved" => 1
                ]);
            }
            // $template_data = \DB::table('template_data')->where('id',$request->invoice_id)->update([
            //     "invoice_no" => $request->invoice_no,
            //     "date_desc" => $request->date_desc,
            //     "info" => $request->info,
            //     "info2" => $request->info2,
            //     "table_title" => $request->table_title,
            //     "payment_type" => $request->payment_type,
            //     "total" => $total,
            //     "due_date" => $request->due_date,
            //     "notes" => $request->notes,
            //     "seller_signature" => $signature,
            //     "footer" => $request->footer,
            //     "is_saved" => 1
            // ]);
            $template_items = \DB::table('template_items')->where('data_id',$request->invoice_id)->delete();
            if($request->has('item') && count($request->item) > 0){
                foreach($request->item as $key=>$val){
                    $res = \DB::table('template_items')->insert([
                        "data_id" => $request->invoice_id,
                        "item" => $request->item[$key],
                        "ref" => $request->ref[$key],
                        "qty" => $request->qty[$key],
                        "unit_price" => $request->unit_price[$key],
                        "total_net" => $request->total_net[$key],
                        "vat" => $request->vat[$key],
                        "vat_amount" => $request->vat_amount[$key],
                        "total_gross" => $request->total_gross[$key]
                    ]);
                }
                if($res){
                    return redirect()->back()->withSuccess('Document saved!');
                }
                else{
                    return redirect()->back()->withErrors('Something went wrong!');
                }
            }
            else{
                return redirect()->back()->withSuccess('Document saved!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /* Copy Invoice */
    public function copyInvoice($invoiceId){
        $template_data = \DB::table('template_data')->where(['id'=>$invoiceId,'user_id' => Auth::user()->id])->first();
        $template_id = \DB::table('template_data')->insertGetId([
            "user_id" => Auth::user()->id,
            "template_id" => $template_data->template_id,
            "invoice_no" => $template_data->invoice_no,
            "date_desc" => $template_data->date_desc,
            "info" => $template_data->info,
            "currency" => $template_data->currency,
            "info2" => $template_data->info2,
            "table_title" => $template_data->table_title,
            "payment_type" => $template_data->payment_type,
            "due_date" => $template_data->due_date,
            "total" => $template_data->total,
            "notes" => $template_data->notes,
            "seller_signature" => $template_data->seller_signature,
            "footer" => $template_data->footer,
            "is_saved" => $template_data->is_saved
        ]);
        $template_items = \DB::table('template_items')->where('data_id',$invoiceId)->get();
        if(count($template_items) > 0){
            foreach($template_items as $key=>$val){
                $res = \DB::table('template_items')->insert([
                    "data_id" => $template_id,
                    "item" => $val->item,
                    "ref" => $val->ref,
                    "qty" => $val->qty,
                    "qty_unit" => $val->qty_unit,
                    "discount" => $val->discount,
                    "unit_price" => $val->unit_price,
                    "total_net" => $val->total_net,
                    "vat" => $val->vat,
                    "vat_amount" => $val->vat_amount,
                    "total_gross" => $val->total_gross
                ]);
            }
            if($res){
                return redirect()->back()->withSuccess('Document Copied!');
            }
            else{
                return redirect()->back()->withErrors('Something went wrong!');
            }
        }
        else{
            return redirect()->back()->withSuccess('Document Copied!');
        }
    }

    /* delete Invoice */
    public function deleteInvoice($invoiceId){
        try{
            $res = \DB::table('template_data')->where('id',$invoiceId)->delete();
            if($res){
                $count = \DB::table('template_items')->where('data_id',$invoiceId)->count();
                if($count > 0){
                    $ress = \DB::table('template_items')->where('data_id',$invoiceId)->delete();
                    if($ress){
                        return redirect()->back()->withSuccess('Document Deleted!');
                    }
                    else{
                        return redirect()->back()->withErrors('Something went wrong!');
                    }
                }
                else{
                    return redirect()->back()->withSuccess('Document Deleted!');
                }
            }
            else{
                return redirect()->back()->withErrors('Something went wrong!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /********** End Invoices  ************/


    /********** Estimates  ************/

    /* Estimates */
    public function estimates(){
        $estimates = \DB::table('template_data')->where(['template_id'=>2,'user_id'=> Auth::user()->id]);
        $project_id = request('project');
        if($project_id){
            $estimates->where('project_id',$project_id);
        }
        $estimates = $estimates->get();
        return view('lite-agency.estimates.estimates',compact('estimates'));
    }

    /* Add New Estimate */
    public function addEstimate(){
        $template = \DB::table('templates')->where('id',2)->get();
        $dept = DB::table('departments')
        ->where('created_by', Auth::user()->id)
        ->where('active', 1)
        ->first();

        $temp = MainTemplateforDep::where('depid',$dept->id)->get();
        $notetemp = NoteTemplateforDep::where('depid',$dept->id)->where('notefor','estimate')->get();

        return view('lite-agency.estimates.addEstimate',compact('template','temp','notetemp'));
    }

    /* Add post new Estimate */
    public function addNewEstimate(Request $request){
        try{
            $check = \DB::table('template_data')->where(['user_id'=>Auth::user()->id,'template_id',2])->count();
            if($check < 1){
                $total = 0;
                if($request->has('item') && count($request->item) > 0){
                    foreach($request->total_gross as $key=>$value){
                        $total = (float)$total + (float)$value;
                    }
                }
                $signature = '';
                if($request->file('seller_signature')){
                    $file = $request->file('seller_signature');
                    $filename = date('YmdHi').$file->getClientOriginalName();
                    $file->move('uploads', $filename);
                    $signature = asset("uploads/".$filename);
                }
                $temp = MainTemplateforDep::where('id',$request->input('maintempid'))->first();
                $notetemp = NoteTemplateforDep::where('id',$temp->notesestimateid)->first();
                // return $notetemp;
                $check = $temp->refnumber;
                // $extractedDigits = substr($check, 0, -3);

                    // Extract the numeric part from the end of the string
                    $numericPart = substr($check, -3);
                    
                    // Increment the numeric part
                    $newNumericPart = (int)$numericPart + 1;
                    
                    // Construct the new refnumber with the incremented numeric part
                    $newref = substr($check, 0, -3) . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
                    
                    // Update the $temp object with the new refnumber
                    $temp->refnumber = $newref;
                    $temp->save();

                // $template_id = \DB::table('template_data')->insertGetId([
                //     "user_id" => Auth::user()->id,
                //     "template_id" => 2,
                //     "invoice_no" => $request->invoice_no,
                //     "date_desc" => $request->date_desc,
                //     "info" => $request->info,
                //     "info2" => $request->info2,
                //     "table_title" => $request->table_title,
                //     "total" => $total,
                //     "payment_type" => $request->payment_type,
                //     "due_date" => $request->due_date,
                //     "notes" => $request->notes,
                //     "seller_signature" => $signature,
                //     "footer" => $request->footer,
                //     "is_saved" => 1
                // ]);
                $template_id = \DB::table('template_data')->insertGetId([
                    "user_id" => Auth::user()->id,
                    "template_id" =>  2,
                    "date_desc" => $request->date_desc,
                    "info" => $request->info,
                    "info2" => $request->info2,
                    "total" => $total,
                    "table_title" => $request->table_title,
                    "invoice_no" => $temp->refnumber,
                    "payment_type" => $temp->paymentType,
                    "notes" => $notetemp->note,
                    "maintempid" => $request->input('maintempid'),
                    "notesestimateid" => $notetemp->id,
                    "seller_signature" => $signature,
                    "footer" => $request->footer,
                    "is_saved" => 1
                    
                ]);
    
                $dept = DB::table('departments')
                    ->where('created_by', Auth::user()->id)
                    ->where('active', 1)
                    ->first();
    
                $refrancetable = new Refrencetable;
                $refrancetable->tmpidfromtemptable = $template_id;
                $refrancetable->depid = $dept->id;
                $refrancetable->maintempid = $request->input('maintempid');
                $refrancetable->notetempid = $request->input('notestempid');
                $refrancetable->user_id = Auth::user()->id;
                $refrancetable->save();

                if($request->has('item') && count($request->item) > 0){
                    foreach($request->item as $key=>$val){
                        $res = \DB::table('template_items')->insert([
                            "data_id" => $template_id,
                            "item" => $request->item[$key],
                            "ref" => $request->ref[$key],
                            "qty" => $request->qty[$key],
                            "discount" => $request->discount[$key],
                            "unit_price" => $request->unit_price[$key],
                            "total_net" => $request->total_net[$key],
                            "vat" => $request->vat[$key],
                            "vat_amount" => $request->vat_amount[$key],
                            "total_gross" => $request->total_gross[$key]
                        ]);
                    }
                    if($res){
                        return redirect()->back()->withSuccess('Document saved!');
                    }
                    else{
                        return redirect()->back()->withErrors('Something went wrong!');
                    }
                }
                else{
                    return redirect()->back()->withSuccess('Document saved!');
                }
            }
            else{
                return redirect()->back()->withErrors('error','You can only create one document!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /* Edit Estimate */
    public function editEstimate($estimateId){
        $template_data = \DB::table('template_data')->where(['id'=>$estimateId,'user_id' => Auth::user()->id])->get();
        if($template_data[0]->is_saved==0){
            $template = \DB::table('templates')->where('id',$template_data[0]->template_id)->get();
        }
        else{ $template = $template_data;}
        $template_items = \DB::table('template_items')->where('data_id',$estimateId)->get();
        return view('lite-agency.estimates.editEstimate',compact('template_data','template','template_items'));
    }

    /* View Estimate */
    public function viewEstimate($estimateId){
        $template_data = \DB::table('template_data')->where(['id'=>$estimateId,'user_id' => Auth::user()->id])->get();
        if($template_data[0]->is_saved==0){
            $template = \DB::table('templates')->where('id',$template_data[0]->template_id)->get();
        }
        else{ $template = $template_data;}
        $template_items = \DB::table('template_items')->where('data_id',$estimateId)->get();
        return view('lite-agency.estimates.viewEstimate',compact('template_data','template','template_items'));
    }

    /* Save Estimate */
    public function saveEstimate(Request $request){
        try{
            $total = 0;
            if($request->has('item') && count($request->item) > 0){
                foreach($request->total_gross as $key=>$value){
                    $total = (float)$total + (float)$value;
                }
            }
            $templateData = \DB::table('template_data')->where('id',$request->estimate_id)->get();
            $signature = $templateData[0]->seller_signature;
            if($request->file('seller_signature')){
                $file = $request->file('seller_signature');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move('uploads', $filename);
                $signature = asset("uploads/".$filename);
            }
            $template_data = \DB::table('template_data')->where('id',$request->estimate_id)->update([
                "invoice_no" => $request->invoice_no,
                "date_desc" => $request->date_desc,
                "info" => $request->info,
                "info2" => $request->info2,
                "table_title" => $request->table_title,
                "payment_type" => $request->payment_type,
                "total" => $total,
                "due_date" => $request->due_date,
                "notes" => $request->notes,
                "seller_signature" => $signature,
                "footer" => $request->footer,
                "is_saved" => 1
            ]);
            $template_items = \DB::table('template_items')->where('data_id',$request->estimate_id)->delete();
            if($request->has('item') && count($request->item) > 0){
                foreach($request->item as $key=>$val){
                    $res = \DB::table('template_items')->insert([
                        "data_id" => $request->estimate_id,
                        "item" => $request->item[$key],
                        "ref" => $request->ref[$key],
                        "qty" => $request->qty[$key],
                        "discount" => $request->discount[$key],
                        "unit_price" => $request->unit_price[$key],
                        "total_net" => $request->total_net[$key],
                        "vat" => $request->vat[$key],
                        "vat_amount" => $request->vat_amount[$key],
                        "total_gross" => $request->total_gross[$key]
                    ]);
                }
                if($res){
                    return redirect()->back()->withSuccess('Document saved!');
                }
                else{
                    return redirect()->back()->withErrors('Something went wrong!');
                }
            }
            else{
                return redirect()->back()->withSuccess('Document saved!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /* Copy Estimate */
    public function copyEstimate($estimateId){
        $template_data = \DB::table('template_data')->where(['id'=>$estimateId,'user_id' => Auth::user()->id])->first();
        $template_id = \DB::table('template_data')->insertGetId([
            "user_id" => Auth::user()->id,
            "template_id" => $template_data->template_id,
            "invoice_no" => $template_data->invoice_no,
            "date_desc" => $template_data->date_desc,
            "info" => $template_data->info,
            "currency" => $template_data->currency,
            "info2" => $template_data->info2,
            "table_title" => $template_data->table_title,
            "payment_type" => $template_data->payment_type,
            "due_date" => $template_data->due_date,
            "total" => $template_data->total,
            "notes" => $template_data->notes,
            "seller_signature" => $template_data->seller_signature,
            "footer" => $template_data->footer,
            "is_saved" => $template_data->is_saved
        ]);
        $template_items = \DB::table('template_items')->where('data_id',$estimateId)->get();
        if(count($template_items) > 0){
            foreach($template_items as $key=>$val){
                $res = \DB::table('template_items')->insert([
                    "data_id" => $template_id,
                    "item" => $val->item,
                    "ref" => $val->ref,
                    "qty" => $val->qty,
                    "qty_unit" => $val->qty_unit,
                    "discount" => $val->discount,
                    "unit_price" => $val->unit_price,
                    "total_net" => $val->total_net,
                    "vat" => $val->vat,
                    "vat_amount" => $val->vat_amount,
                    "total_gross" => $val->total_gross
                ]);
            }
            if($res){
                return redirect()->back()->withSuccess('Document Copied!');
            }
            else{
                return redirect()->back()->withErrors('Something went wrong!');
            }
        }
        else{
            return redirect()->back()->withSuccess('Document Copied!');
        }
    }

    /* delete estimate */
    public function deleteEstimate($estimateId){
        try{
            $res = \DB::table('template_data')->where('id',$estimateId)->delete();
            if($res){
                $count = \DB::table('template_items')->where('data_id',$estimateId)->count();
                if($count > 0){
                    $ress = \DB::table('template_items')->where('data_id',$estimateId)->delete();
                    if($ress){
                        return redirect()->back()->withSuccess('Document Deleted!');
                    }
                    else{
                        return redirect()->back()->withErrors('Something went wrong!');
                    }
                }
                else{
                    return redirect()->back()->withSuccess('Document Deleted!');
                }
            }
            else{
                return redirect()->back()->withErrors('Something went wrong!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /********** End Estimate  ************/

    /********** Purchase Order  ************/

    /* Purchase Order */
    public function purchaseOrder(){
        
        $month = request('month');
        $year = request('year');
        $year = (int)$year;
        
        $purchaseOrdersQuery =  \DB::table('template_data')->where(['template_id'=>3,'user_id'=> Auth::user()->id]);
        if($month){
            $startMonth = Carbon::parse($month)->startOfMonth();
            $endMonth = Carbon::parse($month)->endOfMonth();
            $purchaseOrdersQuery->whereBetween('created_at', [$startMonth, $endMonth]);
        }
        $project_id = request('project');
        if($project_id){
            $purchaseOrdersQuery->where('project_id',$project_id);
        }
        if($year){
            $purchaseOrdersQuery->whereYear('created_at', '>=', $year)->whereYear('created_at', '<=', $year);
        }
        $purchaseOrders = $purchaseOrdersQuery->get();
        return view('lite-agency.purchaseOrder.purchaseOrders',compact('purchaseOrders'));
    }

    /* Add New purchase Order */
    public function addPurchaseOrder(){
        $template = \DB::table('templates')->where('id',3)->get();

        $dept = DB::table('departments')
        ->where('created_by', Auth::user()->id)
        ->where('active', 1)
        ->first();

        $temp = MainTemplateforDep::where('depid',$dept->id)->get();
        $notetemp = NoteTemplateforDep::where('depid',$dept->id)->where('notefor','purchaseOrder')->get();

        return view('lite-agency.purchaseOrder.addPurchaseOrder',compact('template','temp','notetemp'));
    }

    /* Add post new Purchase Order */
    public function addNewPurchaseOrder(Request $request){
        try{
            $check = \DB::table('template_data')->where(['user_id'=>Auth::user()->id,'template_id',2])->count();
            if($check < 1){
                $total = 0;
                if($request->has('item') && count($request->item) > 0){
                    foreach($request->total_gross as $key=>$value){
                        $total = (float)$total + (float)$value;
                    }
                }
                $signature = '';
                if($request->file('seller_signature')){
                    $file = $request->file('seller_signature');
                    $filename = date('YmdHi').$file->getClientOriginalName();
                    $file->move('uploads', $filename);
                    $signature = asset("uploads/".$filename);
                }
                $temp = MainTemplateforDep::where('id',$request->input('maintempid'))->first();

                $notetemp = NoteTemplateforDep::where('id',$temp->notespoid)->first();

                $check = $temp->refnumber;
                // $extractedDigits = substr($check, 0, -3);

                    // Extract the numeric part from the end of the string
                    $numericPart = substr($check, -3);
                    
                    // Increment the numeric part
                    $newNumericPart = (int)$numericPart + 1;
                    
                    // Construct the new refnumber with the incremented numeric part
                    $newref = substr($check, 0, -3) . str_pad($newNumericPart, 3, '0', STR_PAD_LEFT);
                    
                    // Update the $temp object with the new refnumber
                    $temp->refnumber = $newref;
                    $temp->save();
                // $template_id = \DB::table('template_data')->insertGetId([
                //     "user_id" => Auth::user()->id,
                //     "template_id" => 3,
                //     "invoice_no" => $request->invoice_no,
                //     "date_desc" => $request->date_desc,
                //     "info" => $request->info,
                //     "info2" => $request->info2,
                //     "table_title" => $request->table_title,
                //     "payment_type" => $request->payment_type,
                //     "total" => $total,
                //     "due_date" => $request->due_date,
                //     "notes" => $request->notes,
                //     "seller_signature" => $signature,
                //     "footer" => $request->footer,
                //     "is_saved" => 1
                // ]);
                $template_id = \DB::table('template_data')->insertGetId([
                    "user_id" => Auth::user()->id,
                    "template_id" => 3,
                    "invoice_no" => $temp->refnumber,
                    "date_desc" => $request->date_desc,
                    "info" => $request->info,
                    "info2" => $request->info2,
                    "table_title" => $request->table_title,
                    "payment_type" => $temp->paymentType,
                    "due_date" => $temp->dueDate,
                    "total" => $total,
                    "notes" => $notetemp->note,
                    "seller_signature" => $signature,
                    "footer" => $request->footer,
                    "is_saved" => 1,
                    "notespoid" => $notetemp->id,
                    "maintempid" => $request->input('maintempid')
                ]);
    
                $dept = DB::table('departments')
                    ->where('created_by', Auth::user()->id)
                    ->where('active', 1)
                    ->first();
    
                $refrancetable = new Refrencetable;
                $refrancetable->tmpidfromtemptable = $template_id;
                $refrancetable->depid = $dept->id;
                $refrancetable->maintempid = $request->input('maintempid');
                $refrancetable->notetempid = $request->input('notestempid');
                $refrancetable->user_id = Auth::user()->id;
                $refrancetable->save();
                if($request->has('item') && count($request->item) > 0){
                    foreach($request->item as $key=>$val){
                        $res = \DB::table('template_items')->insert([
                            "data_id" => $template_id,
                            "item" => $request->item[$key],
                            "ref" => $request->ref[$key],
                            "qty" => $request->qty[$key],
                            "discount" => $request->discount[$key],
                            "unit_price" => $request->unit_price[$key],
                            "total_net" => $request->total_net[$key],
                            "vat" => $request->vat[$key],
                            "vat_amount" => $request->vat_amount[$key],
                            "total_gross" => $request->total_gross[$key]
                        ]);
                    }
                    if($res){
                        return redirect()->back()->withSuccess('Document saved!');
                    }
                    else{
                        return redirect()->back()->withErrors('Something went wrong!');
                    }
                }
                else{
                    return redirect()->back()->withSuccess('Document saved!');
                }
            }
            else{
                return redirect()->back()->withErrors('error','You can only create one document!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /* Edit Purchase Order */
    public function editPurchaseOrder($purchaseOrderId){
        $template_data = \DB::table('template_data')->where(['id'=>$purchaseOrderId,'user_id' => Auth::user()->id])->get();
        if($template_data[0]->is_saved==0){
            $template = \DB::table('templates')->where('id',$template_data[0]->template_id)->get();
        }
        else{ $template = $template_data;}
        $template_items = \DB::table('template_items')->where('data_id',$purchaseOrderId)->get();
        return view('lite-agency.purchaseOrder.editPurchaseOrder',compact('template_data','template','template_items'));
    }

    /* View Purchase Order */
    public function viewPurchaseOrder($purchaseOrderId){
        $template_data = \DB::table('template_data')->where(['id'=>$purchaseOrderId,'user_id' => Auth::user()->id])->get();
        if($template_data[0]->is_saved==0){
            $template = \DB::table('templates')->where('id',$template_data[0]->template_id)->get();
        }
        else{ $template = $template_data;}
        $template_items = \DB::table('template_items')->where('data_id',$purchaseOrderId)->get();
        return view('lite-agency.purchaseOrder.viewPurchaseOrder',compact('template_data','template','template_items'));
    }

    /* Save Purchase Order */
    public function savePurchaseOrder(Request $request){
        try{
            $total = 0;
            if($request->has('item') && count($request->item) > 0){
                foreach($request->total_gross as $key=>$value){
                    $total = (float)$total + (float)$value;
                }
            }
            $templateData = \DB::table('template_data')->where('id',$request->purchaseOrder_id)->get();
            $signature = $templateData[0]->seller_signature;
            if($request->file('seller_signature')){
                $file = $request->file('seller_signature');
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move('uploads', $filename);
                $signature = asset("uploads/".$filename);
            }
            $template_data = \DB::table('template_data')->where('id',$request->purchaseOrder_id)->update([
                "invoice_no" => $request->invoice_no,
                "date_desc" => $request->date_desc,
                "info" => $request->info,
                "info2" => $request->info2,
                "table_title" => $request->table_title,
                "payment_type" => $request->payment_type,
                "total" => $total,
                "due_date" => $request->due_date,
                "notes" => $request->notes,
                "seller_signature" => $signature,
                "footer" => $request->footer,
                "is_saved" => 1
            ]);
            $template_items = \DB::table('template_items')->where('data_id',$request->purchaseOrder_id)->delete();
            if($request->has('item') && count($request->item) > 0){
                foreach($request->item as $key=>$val){
                    $res = \DB::table('template_items')->insert([
                        "data_id" => $request->purchaseOrder_id,
                        "item" => $request->item[$key],
                        "ref" => $request->ref[$key],
                        "qty" => $request->qty[$key],
                        "discount" => $request->discount[$key],
                        "unit_price" => $request->unit_price[$key],
                        "total_net" => $request->total_net[$key],
                        "vat" => $request->vat[$key],
                        "vat_amount" => $request->vat_amount[$key],
                        "total_gross" => $request->total_gross[$key]
                    ]);
                }
                if($res){
                    return redirect()->back()->withSuccess('Document saved!');
                }
                else{
                    return redirect()->back()->withErrors('Something went wrong!');
                }
            }
            else{
                return redirect()->back()->withSuccess('Document saved!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /* Copy PurchaseOrder */
    public function copyPurchaseOrder($purchaseOrderId){
        $template_data = \DB::table('template_data')->where(['id'=>$purchaseOrderId,'user_id' => Auth::user()->id])->first();
        $template_id = \DB::table('template_data')->insertGetId([
            "user_id" => Auth::user()->id,
            "template_id" => $template_data->template_id,
            "invoice_no" => $template_data->invoice_no,
            "date_desc" => $template_data->date_desc,
            "info" => $template_data->info,
            "currency" => $template_data->currency,
            "info2" => $template_data->info2,
            "table_title" => $template_data->table_title,
            "payment_type" => $template_data->payment_type,
            "due_date" => $template_data->due_date,
            "total" => $template_data->total,
            "notes" => $template_data->notes,
            "seller_signature" => $template_data->seller_signature,
            "footer" => $template_data->footer,
            "is_saved" => $template_data->is_saved
        ]);
        $template_items = \DB::table('template_items')->where('data_id',$purchaseOrderId)->get();
        if(count($template_items) > 0){
            foreach($template_items as $key=>$val){
                $res = \DB::table('template_items')->insert([
                    "data_id" => $template_id,
                    "item" => $val->item,
                    "ref" => $val->ref,
                    "qty" => $val->qty,
                    "qty_unit" => $val->qty_unit,
                    "discount" => $val->discount,
                    "unit_price" => $val->unit_price,
                    "total_net" => $val->total_net,
                    "vat" => $val->vat,
                    "vat_amount" => $val->vat_amount,
                    "total_gross" => $val->total_gross
                ]);
            }
            if($res){
                return redirect()->back()->withSuccess('Document Copied!');
            }
            else{
                return redirect()->back()->withErrors('Something went wrong!');
            }
        }
        else{
            return redirect()->back()->withSuccess('Document Copied!');
        }
    }

    /* delete estimate */
    public function deletePurchaseOrder($orderId){
        try{
            $res = \DB::table('template_data')->where('id',$orderId)->delete();
            if($res){
                $count = \DB::table('template_items')->where('data_id',$orderId)->count();
                if($count > 0){
                    $ress = \DB::table('template_items')->where('data_id',$orderId)->delete();
                    if($ress){
                        return redirect()->back()->withSuccess('Document Deleted!');
                    }
                    else{
                        return redirect()->back()->withErrors('Something went wrong!');
                    }
                }
                else{
                    return redirect()->back()->withSuccess('Document Deleted!');
                }
            }
            else{
                return redirect()->back()->withErrors('Something went wrong!');
            }
        }
        catch(Throwable $e){
            return $e;
        }
    }

    /********** End Purchas eOrder  ************/
}
