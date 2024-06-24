<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Chat;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class ProjectsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function getRole()
    {
        if(\Auth::user()->role==2){
            return "agency";
        }else if(\Auth::user()->role==3){
            return "client";
        }
        else if(\Auth::user()->role==1){
            return "admin";
        }
        else if(Auth::user()->role==4){
            return "lite-agency";
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $project=new Project();
        $project->project_title=$request->input('project_title');
        $project->client_name=$request->input('client_name');
        $project->project_description=$request->input('project_description');
        $project->project_budget=$request->input('project_budget');
        $project->internal_notes=$request->input('internal_notes');
        $project->user_id=\Auth::user()->id;
         $check=Chat::where('client_id',$request->input('user_id'))->where('project_id',$project->id)->count();
        if(\Auth::user()->role==3||\Auth::user()->role==4)
        {
             if(\Auth::user()->max_projects_per_client==$check)
            {
                return redirect()->back()->withErrors('error','This client has already reached projects assigned limit.');
            }
        }
        $project->save();


        $chat=new Chat();
        $chat->agency_id=\Auth::user()->id;
        $chat->project_id=$project->id;
        $chat->client_id=$request->input('user_id');
        $chat->save();
        if($project)
        {
            return redirect()->back()->withSuccess('Your project has been saved!');
        }
         return redirect()->back()->withErrors('Something went wrong!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view($this->getRole().'.project-show',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $users=User::where('role',3)->get();
        $chat=Chat::where('project_id',$project->id)->first();
        return view($this->getRole().'.edit-project',compact('project','users','chat'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $project->project_title=$request->input('project_title');
        $project->client_name=$request->input('client_name');
        $project->project_description=$request->input('project_description');
        $project->project_budget=$request->input('project_budget');
        $project->status=$request->input('status');
        $project->internal_notes=$request->input('internal_notes');
        $chat=Chat::where('project_id',$project->id)->first();
        if(!empty($request->input('user_id')) || intval($request->input('user_id'))!=0 )
        {
            $chat->client_id=$request->input('user_id');
        }
        $chat->update();
        if($project->update())
        {
            return redirect()->back()->withSuccess('Your project has been updated!');
        }
         return redirect()->back()->withErrors('Something went wrong!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        
        $chat=Chat::where('project_id',$project->id)->first();
        if($chat)
        {
            $chat->delete();
        }
        if($project->delete())
        {
            if($this->getRole()=="admin")
            {
                return redirect()->back()->withSuccess('Your project has been deleted!');
            }
            return redirect(route($this->getRole().'-dashboard'))->withSuccess('Your project has been deleted!');
        }
         return redirect()->back()->withErrors('Something went wrong!');
    }
}
