@extends('layouts.client') @section('content') <div class="p-sm-4 p-3 project">
    <h1>  
        <a href="javascript:history.back()" class="btn btn-light btn-sm mr-2"><i class="fa fa-angle-left"></i> Back</a>
        {{$project->project_title}}
    </h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <tr>
                            <th>ID</th>
                            <td>{{$project->id}}</td>
                        </tr>
                        <tr>
                            <th>Project Title</th>
                            <td>{{$project->project_title}}</td>
                        </tr>
                        <tr>
                            <th>Client Name</th>
                            <td>{{$project->client_name}}</td>
                        </tr>
                        <tr>
                            <th>Project Description</th>
                            <td>{{$project->project_description}}</td>
                        </tr>
                        <tr>
                            <th>Project Budget</th>
                            <td>{{$project->project_budget}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>@if($project->status==1) In Progress @endif @if($project->status==2) Completed @endif</td>
                        </tr>
                        <tr>
                            <th>Are you interested?</th>
                            <td> 
                                @if(\DB::table('chats')->where('project_id','=',$project->id)->where('client_id','!=',\Auth::user()->id)->first()) 
                                    <div class="d-flex">
                                        <a href="{{url('/client/startchat/'.$project->id)}}"
                                            class="btn btn-primary"><i class="far fa-comment-alt"></i> Are you interested in this project?</a>
                                    </div> 
                                @else 
                                    <strong>Interested Shown Already</strong> 
                                @endif 
                            </td>
                        </tr> 
                        @if(!empty($project->chat)) 
                        <tr>
                            <th>Chat</th>
                            <td>
                                <a href="{{url('/chat/'.$project->chat->id)}}"
                                    class="btn btn-primary"><i class="far fa-comment-alt"></i> Chat</a>
                            </td>
                        </tr> 
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> @endsection
