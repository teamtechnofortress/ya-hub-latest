@extends('layouts.admin') @section('content') <div class="p-sm-4 p-3 project">
    <h1>New Project</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form method="post"
                    action="{{url('projects/'.$project->id)}}"> @csrf @method('put') <div class="row">
                        <div class="form-group col-md-12">
                            <label>Project Title</label>
                            <input type="text"
                                name="project_title"
                                class="form-control"
                                value="{{$project->project_title}}"
                                required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Client Name</label>
                            <input type="text"
                                name="client_name"
                                class="form-control"
                                value="{{$project->client_name}}"
                                required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Project Budget</label>
                            <input type="text"
                                name="project_budget"
                                class="form-control"
                                value="{{$project->project_budget}}"
                                required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Project Description</label>
                            <textarea type="text"
                                name="project_description"
                                class="form-control"
                                required>{{$project->project_description}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Internal Notes</label>
                            <textarea type="text"
                                name="internal_notes"
                                class="form-control"
                                required>{{$project->internal_notes}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Project Status</label>
                            <select name="status"
                                class="form-control">
                                <option value="1"
                                    @if($project->status==1) selected @endif>In Progress</option>
                                <option value="2"
                                    @if($project->status==2) selected @endif>Completed</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Assign to</label>
                            <select name="user_id"
                                class="form-control">
                                <option value="0"
                                    @if($chat->client_id==0) selected @endif>--SELECT CLIENT--</option> @foreach($users as $user) <option value="{{$user->id}}"
                                    @if($chat->client_id==$user->id) selected @endif>{{$user->name}}</option> @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 my-2">
                            <input type="submit"
                                name="submit"
                                value="Update Project"
                                class="btn n-project" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> @endsection
