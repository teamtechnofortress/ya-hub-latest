@extends('layouts.admin') @section('content') 
<div class="p-sm-4 p-3 project">
    <h1>Projects</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Budget</th>
                                <th>Client Name</th>
                                <th>Project Title</th>
                                <th>Status</th>
                                <th>Client User Assigned to project</th>
                                <th>Agency Assigned to project</th>
                                <th>Delete?</th>
                            </tr>
                        </thead>
                        <tbody> @foreach($projects as $project) <tr>
                                <td>{{$project->id}}</td>
                                <td>{{$project->project_description}}</td>
                                <td>{{$project->project_budget}}</td>
                                <td>{{$project->client_name}}</td>
                                <td>{{$project->project_title}}</td>
                                <td>@if($project->status==1) In Progress @endif @if($project->status==2) Completed @endif</td>
                                <td> @if(!empty($project->chat)) @if(!empty($project->chat->client)) {{$project->chat->client->name}} <a href="{{url('admin/agency/unlink-client/'.$project->chat->id.'/'.$project->id)}}"
                                        class="btn btn-success btn-sm"><i class="fas fa-unlink"></i> Unlink</a> @endif @endif </td>
                                <td> @if(!empty($project->chat)) @if(!empty($project->chat->agency)) {{$project->chat->agency->name}} @endif @endif </td>
                                <td>
                                    <div class="d-flex"
                                        style="justify-content:space-between;">
                                        <form method="post"
                                            class="deleteProject"
                                            action="{{url('projects/'.$project->id)}}"> @csrf @method('delete') <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                        <a href="{{url('projects/'.$project->id.'/edit/')}}"
                                            class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                                    </div>
                                </td>
                            </tr> @endforeach </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var deleteStatus = false;
$(".deleteProject").on("submit", function(e) {
    var $this = $(this);
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
                $(this).submit();
            }
        });
    }
})
</script> @endsection
