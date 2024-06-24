<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;
use App\Models\MainTemplateforDep;
use App\Models\NoteTemplateforDep;

class departmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function departments_view(){

        $data = DB::table('departments')->where('created_by',Auth::user()->id)->get();
        return view('department.index',compact('data'));
    }

    public function add_view(){
        return view('department.create');
    }

    public function add_departments(Request $request){ 
        if ($request->hasFile('dept_logo')){
            
            $file= $request->file('dept_logo');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $logo = asset("uploads/".$filename);
                $query =  DB::table('departments')->insert([

                    'department_id'    => $request->dept_id,
                    'department_name'  => $request->dept_name,
                    'department_logo'  => $logo,
                    'theme_style'      => '{"bg_color":null,"font_color":null,"heading":null,"paragraph":null,"lable":null,"border":"#ffffff","btn_primary_bg_color":"#2a9ff8","btn_primary_font_color":null,"btn_secondary_bg_color":null,"btn_secondary_font_color":null,"btn_danger_bg_color":null,"btn_danger_font_color":null,"btn_info_bg_color":null,"btn_info_font_color":null,"text_bg_color":null,"text_font_color":null,"text_border":null,"icon_color":null,"active_icon_color":"#2a9ff8"}',
                    'created_by'       => Auth::user()->id,
                    'created_at'       => now(),
 
                ]);
            }else{
                $query =  DB::table('departments')->insert([

                    'department_id'    => $request->dept_id,
                    'department_name'  => $request->dept_name,
                    // 'department_logo'  => $request->dept_logo,
                    'theme_style'      => '{"bg_color":null,"font_color":null,"heading":null,"paragraph":null,"lable":null,"border":"#ffffff","btn_primary_bg_color":"#2a9ff8","btn_primary_font_color":null,"btn_secondary_bg_color":null,"btn_secondary_font_color":null,"btn_danger_bg_color":null,"btn_danger_font_color":null,"btn_info_bg_color":null,"btn_info_font_color":null,"text_bg_color":null,"text_font_color":null,"text_border":null,"icon_color":null,"active_icon_color":"#2a9ff8"}',
                    'created_by'       => Auth::user()->id,
                    'created_at'       => now(),
 
                ]);
            }
        if($query){
            return redirect()->back()->withSuccess('New Department Added!');
        }else{
            return redirect()->back()->withErrors('Something Went Wrong');
        }
    }
    public function delete_departments($id){
        $query = DB::table('departments')->where('id',$id)->delete();

        if($query){
            return redirect('/departments')->withSuccess('Department Deleted');
        }else{
            return redirect('/departments')->withErrors('Something Went Wrong');
        }
    }
    

    public function updatedepartments($id){

        $data = DB::table('departments')->where('id',$id)->first();

        return view('department.edit',compact('data'));

    }

    public function saveupdatedepartments(Request $request){
        if ($request->hasFile('dept_logo')){
            
            $file= $request->file('dept_logo');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move('uploads', $filename);
            $logo = asset("uploads/".$filename);
                $query =  DB::table('departments')->where('id', $request->id)
                ->update([

                    'department_id'    => $request->dept_id,
                    'department_name'  => $request->dept_name,
                    'department_logo'  => $logo,
                    'updated_by' => Auth::user()->id,
                    'updated_at' => now(),
 
                ]);
            }else{
                DB::table('departments')
                ->where('id', $request->id)
                ->update([
                    
                    'department_id'    => $request->dept_id,
                    'department_name'  => $request->dept_name,
                
                    'updated_by' => Auth::user()->id,
                    'updated_at' => now(),
                ]);
            }       
        return redirect('/departments')->withSuccess('Department Updated!');

    }

    public function inside_departments($id){

        $data = DB::table('departments')->where('id',$id)->first();

        $active = DB::table('departments')->where('id',$id)->update([
            'active'  => 1,
        ]);

        $deactive = DB::table('departments')->where('created_by',$data->created_by)->where('id','!=',$id)->update([
            'active' => 0,
        ]);
        $temp = MainTemplateforDep::where('depid',$id)->get();
        $notetemp = NoteTemplateforDep::where('depid',$id)->get();
        // return $temp;



        return view('department.view_departments',compact('data','temp','notetemp'));

    }
    public function create_main_temp(Request $request)
    {
        // $request->validate([
        //     'temp_name' => 'required|string|max:255',
        //     'ref_number' => 'required|string|max:255',
        //     'payment_typ' => 'required|string|max:255',
        //     'due_date' => 'required|date',
        //     'notesid' => 'nullable|string|max:255',
        // ]);
        $dept = DB::table('departments')
                                        ->where('created_by', Auth::user()->id)
                                        ->where('active', 1)
                                        ->first();
        // Create a new instance of MainTemplateforDep
        $temp = new MainTemplateforDep();
        $temp->tempName = $request->input('temp_name');
        $temp->refnumber = $request->input('ref_number');
        $temp->paymentType = $request->input('payment_typ');
        $temp->dueDate = $request->input('due_date');
        $temp->Notes = $request->input('notesid');
        $temp->depid = $dept->id;
    
        // Save the instance and check if it was successful
        if ($temp->save()) {
            return back()->with('success', 'Template Layout Saved!');
        } else {
            return back()->with('error', 'Template Layout Not Saved!');
        }
        
        
    }
    public function updatemaintemp($id){

        // $data = DB::table('departments')->where('id',$id)->first();

        $dept = DB::table('departments')
                                        ->where('created_by', Auth::user()->id)
                                        ->where('active', 1)
                                        ->first();
        $notetemp = NoteTemplateforDep::where('depid',$dept->id)->get();


        $temp = MainTemplateforDep::where('id',$id)->where('depid',$dept->id)->first();
        return view('template.editmaintemp',compact('temp','notetemp'));

    }
    public function deletemaintemp($id){
        // $query = DB::table('departments')->where('id',$id)->delete();
        $dept = DB::table('departments')
        ->where('created_by', Auth::user()->id)
        ->where('active', 1)
        ->first();
        // $notetemp = MainTemplateforDep::where('depid',$dept->id)->get();
        $temp = MainTemplateforDep::where('id',$id)->where('depid',$dept->id)->delete();

        if($temp){
            return back()->withSuccess('Template Deleted');
        }else{
            return back()->withErrors('Something Went Wrong');
        }
    }
    public function create_note_temp(Request $request)
    {
        // $request->validate([
        //     'temp_name' => 'required|string|max:255',
        //     'ref_number' => 'required|string|max:255',
        //     'payment_typ' => 'required|string|max:255',
        //     'due_date' => 'required|date',
        //     'notesid' => 'nullable|string|max:255',
        // ]);
        $dept = DB::table('departments')
                                        ->where('created_by', Auth::user()->id)
                                        ->where('active', 1)
                                        ->first();
        // Create a new instance of MainTemplateforDep
        $temp = new NoteTemplateforDep();
        $temp->notename = $request->input('note_name');
        $temp->note = $request->input('note');
        $temp->depid = $dept->id;
        // Save the instance and check if it was successful
        if ($temp->save()) {
            return back()->with('success', 'Note Layout Saved!');
        } else {
            return back()->with('error', 'Note Layout Not Saved!');
        }
        
        
    }

    public function departmentChangeTheme(Request $request,$id){
        $setting_json = json_encode($request->except(['_token']));
        $department = DB::table('departments')->where('id',$id)->update([
            'theme_style' => $setting_json,
        ]);
        
        if($department){
            return true;
        }
    }

    public function defaultUi(){
       $query = DB::table('departments')->where('created_by',Auth::user()->id)->update([
            'active' => 0,

        ]);

        if($query){
            return redirect('/departments')->withSuccess('Defalut Layout!');
        }else{
            return redirect('/departments')->withErrors('Something Went Wrong');
        }
    }
}
