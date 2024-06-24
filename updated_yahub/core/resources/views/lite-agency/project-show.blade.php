@extends('layouts.lite-agency') @section('content') <div class="p-sm-4 p-3 project">
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
                        </tr> @if($project->user_id==\Auth::user()->id) <tr>
                            <th>Actions</th>
                            <td>
                                <div class="d-flex"
                                    style="justify-content:space-between;">
                                    <form method="post"
                                        id="deleteProject"
                                        action="{{url('projects/'.$project->id)}}"> 
                                        @csrf @method('delete') <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                    <a href="{{url('lite-agency/project/'.$project->id.'/edit')}}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a> 
                                        @if(!empty($project->chat)) 
                                            <a href="{{url('chat/'.$project->chat->id)}}" class="btn btn-sm btn-primary"><i class="fa fa-comment"></i> Chat</a> 
                                        @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Internal Notes</th>
                            <td>{{$project->internal_notes}}</td>
                        </tr> @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var deleteStatus = false;
$("#deleteProject").on("submit", function(e) {
    if (deleteStatus == false) {
        e.preventDefault();
        Swal.fire({
            title: "Delete",
            text: "Are you sure?",
            icon: "warning",
            button: "Yes",
        }).then((value) => {
            if (value.isConfirmed) {
                deleteStatus = true;
                $("#deleteProject").submit();
            }
        });
    }
})
</script> @endsection
