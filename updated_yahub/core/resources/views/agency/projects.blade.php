@extends('layouts.agency') @section('content') 

<style>
        .toolTip{
            position: absolute;
            box-shadow: rgb(0 0 0 / 13%) 0px 0px 5px 0px; 
            /* padding: 10px 30px; */
            right: 30px;
            top: 50px;
            width: 120px;
            border-radius: 8px;
            overflow: hidden;
            z-index: 1;
            display: none;
        }
        .toolTip > ul > li > a{
            width: 100%;
            font-size: 15px;
            text-align: left;
        }
        button.btn-light{
            color: #7e7c7c
        }
        
        .swal2-warning{
            margin-left: auto !important;
            margin-right: auto !important;
        }
        .swal2-confirm{
            margin-right: 20px !important;
        }
        .swal2-html-container{
            margin-bottom: 20px !important;
        }
        .swal2-title{
            margin-top: 10px !important;
        }
        .swal2-icon-show{
            margin: 0 auto !important;
        }
    </style>
    @if($projectsLimit == true)
    <style>
        /* .swal2-title,.swal2-content{
            margin-top: 10px !important;
            margin-bottom: 20px !important;
        } */
    </style>
        <script>
        //    Swal.fire({
        //         icon: 'error',
        //         title: 'Project Limit Error !',
        //         html: 'Usage limit reached. Please contact our <a href="https://ya-hub.com/contact">sales teams</a> to upgrade your account.',
        //     })
        </script>
    @endif
<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-12 text-right">
                <a href="#0" class="btn btn-secondary" id="search_btn">Search</a>
                <a href="#0" class="btn btn-secondary" style="display:none" id="close_btn">Close</a>
            </div>
        </div>
        <div id="projects">
            <h1>My Projects</h1>
            <div class="py-4">
                <div class="d-flex">
                    <button class="p-opt-btn btn w-50">In Progress</button>
                    <button class="p-opt-btn btn w-50">Past Work</button>
                </div>
                <div class="pro-content">
                    <div class="pro-row content-1 active">
                        <div class="row pb-3 my-3"> @if(count($inprogress)>0) @foreach($inprogress as $project) <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                                <div class="project-box border shadow py-2 px-3">
                                    <div class="d-flex justify-content-between project-h">
                                        <div>
                                            <h2>{{$project->project_title}}</h2>
                                            <span>{{$project->client_name}}</span>
                                        </div>
                                        <div>
                                            <a href="#0"
                                                class="btn showToolTip" data-id="{{$project->id}}"><i class="fas fa-ellipsis-v"></i></a>
                                        </div>
                                        <div class="toolTip" data-id="{{$project->id}}">
                                            <ul style="padding:0">
                                                <li><a href="{{url('projects/'.$project->id.'/edit')}}" class="btn btn-light btn-sm"><i class="fa fa-edit"></i> Edit</a></li>
                                                @php 
                                                    $role = false;
                                                    if(auth()->user()->role==2) $role = 'agency';
                                                    if(auth()->user()->role==3) $role = 'client';
                                                    if(auth()->user()->role==4) $role = 'lite-agency';
                                                @endphp
                                                @if($role)
                                                    <li><a href="{{url($role.'/invoices?project='.$project->id)}}" class="btn btn-light btn-sm"><i class="fa fa-list"></i> Invoices</a></li>
                                                    <li><a href="{{url($role.'/estimates?project='.$project->id)}}" class="btn btn-light btn-sm"><i class="fa fa-list"></i> Estimates</a></li>
                                                    <li><a href="{{url($role.'/purchaseOrder?project='.$project->id)}}" class="btn btn-light btn-sm"><i class="fa fa-list"></i> POs</a></li>
                                                @endif
                                                @if(!empty($project->chat))
                                                    <li><a href="{{url('chat/'.$project->chat->id)}}" class="btn btn-light btn-sm"><i class="fa fa-comment"></i> Chat</a></li>
                                                @endif
                                                <li>
                                                    <!-- <a href="#0" class="btn btn-light btn-sm"><i class="fa fa-trash"></i> Delete</a> -->
                                                    <form method="post"
                                                        class="deleteProject"
                                                        action="{{url('projects/'.$project->id)}}"> 
                                                        @csrf 
                                                        @method('delete') 

                                                     <div class="confirm-trigger">
                                                        <button class="btn btn-sm btn-light text-left" style="width:100%"><i class="fa fa-trash"></i> Delete</button>
                                                    </div>

                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p>{{$project->project_description}}</p>
                                    <div class="budget d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3>Budget : <span> {{$project->project_budget}}</span></h3>
                                        </div>
                                        <div>
                                            <a href="{{url('projects/'.$project->id)}}"
                                                class="btn">View Project</a>
                                        </div>
                                    </div>
                                </div>
                            </div> @endforeach @else <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                                <div class="project-box border shadow py-2 px-3">
                                    <div class="d-flex justify-content-between project-h">
                                        <div>
                                            <h2>No projects found.</h2>
                                        </div>
                                    </div>
                                    <div class="budget d-flex justify-content-between align-items-center">
                                    </div>
                                </div>
                            </div> @endif </div>
                    </div>
                    <div class="pro-row content-2">
                        <div class="row pb-3 my-3">@if(count($previous)>0) @foreach($previous as $project) <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                                <div class="project-box border shadow py-2 px-3">
                                    <div class="d-flex justify-content-between project-h">
                                        <div>
                                            <h2>{{$project->project_title}}</h2>
                                            <span>{{$project->client_name}}</span>
                                        </div>
                                        <div>
                                        <a href="#0"
                                            class="btn showToolTip" data-id="{{$project->id}}"><i class="fas fa-ellipsis-v"></i></a>
                                    </div>
                                    <div class="toolTip" data-id="{{$project->id}}">
                                        <ul style="padding:0">
                                            <li><a href="{{url('projects/'.$project->id.'/edit')}}" class="btn btn-light btn-sm"><i class="fa fa-edit"></i> Edit</a></li>
                                            @php 
                                                $role = false;
                                                if(auth()->user()->role==2) $role = 'agency';
                                                if(auth()->user()->role==3) $role = 'client';
                                                if(auth()->user()->role==4) $role = 'lite-agency';
                                            @endphp
                                            @if($role)
                                                <li><a href="{{url($role.'/invoices?project='.$project->id)}}" class="btn btn-light btn-sm"><i class="fa fa-list"></i> Invoices</a></li>
                                                <li><a href="{{url($role.'/estimates?project='.$project->id)}}" class="btn btn-light btn-sm"><i class="fa fa-list"></i> Estimates</a></li>
                                                <li><a href="{{url($role.'/purchaseOrder?project='.$project->id)}}" class="btn btn-light btn-sm"><i class="fa fa-list"></i> POs</a></li>
                                            @endif
                                            @if(!empty($project->chat))
                                                <li><a href="{{url('chat/'.$project->chat->id)}}" class="btn btn-light btn-sm"><i class="fa fa-comment"></i> Chat</a></li>
                                            @endif
                                            <li>
                                                <!-- <a href="#0" class="btn btn-light btn-sm"><i class="fa fa-trash"></i> Delete</a> -->
                                                <form method="post"
                                                    class="deleteProject"
                                                    action="{{url('projects/'.$project->id)}}"> 
                                                    @csrf 
                                                    @method('delete') 
                                                    <div class="confirm-trigger">
                                                        <button class="btn btn-sm btn-light text-left" style="width:100%"><i class="fa fa-trash"></i> Delete</button>
                                                    </div>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                    </div>
                                    <p>{{$project->project_description}}</p>
                                    <div class="budget d-flex justify-content-between align-items-center">
                                        <div>
                                            <h3>Budget : <span> {{$project->project_budget}}</span></h3>
                                        </div>
                                        <div>
                                            <a href="{{url('projects/'.$project->id)}}"
                                                class="btn">View Project</a>
                                        </div>
                                    </div>
                                </div>
                            </div> @endforeach @else <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                                <div class="project-box border shadow py-2 px-3">
                                    <div class="d-flex justify-content-between project-h">
                                        <div>
                                            <h2>No projects found.</h2>
                                        </div>
                                    </div>
                                    <div class="budget d-flex justify-content-between align-items-center">
                                    </div>
                                </div>
                            </div> @endif </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="search_projects" style="display:none">
            <h1>Search Projects</h1>
            <div class="py-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control search-input" placeholder="Search here..." name="search" id="search" />
                        </div>
                    </div>
                </div>
                <div class="pro-content">
                    <div class="pro-row">
                        <div class="row pb-3 my-3 search-area">
                            <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                                <div class="project-box border shadow py-2 px-3">
                                    <div class="d-flex justify-content-between project-h">
                                        <div>
                                            <h2>Type in the search bar.</h2>
                                        </div>
                                    </div>
                                    <div class="budget d-flex justify-content-between align-items-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script>
    // Add a click event handler to the delete button
    $('.deleteProject button').click(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get the form element for the project deletion
        var $form = $(this).closest('form');
        Swal.fire({
            icon: 'question',
            title: 'Are you sure?',
            text: 'You are about to delete this project.',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it',
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, submit the form to delete the project
                $form.submit();
            } else if (result.isDismissed) {
                // If the user dismisses the dialog, no action required
            }
        });
    });
</script>





<script>
$('.toolTip > ul > li > a').click(function(){
    window.location.href = $(this).attr('href');
});

$('.deleteProject > button').click(function(){
    $(this).parent('form.deleteProject').submit();
});

$('.showToolTip').click(function(e){
    var dataId = $(this).data('id');
    e.stopPropagation();

    // Hide all other tooltips
    $('.toolTip').hide();

    // Show the tooltip corresponding to the clicked element
    $("div").find(`div.toolTip[data-id='${dataId}']`).toggle();
});

$(document).click(function() {
    $('.toolTip').hide();
});

$(".toolTip").click(function(e) {
    e.stopPropagation();
    return false;
});

$("#search_btn").click(function(){
    $("#projects").toggle('slow');
    $("#search_projects").toggle('slow');
    $(this).toggle('slow');
    $("#close_btn").toggle('slow');
});

$("#close_btn").click(function(){
    $("#projects").toggle('slow');
    $("#search_projects").toggle('slow');
    $(this).toggle('slow');
    $("#search_btn").toggle('slow');
});

$(".search-input").on("keyup", function() {
    var query = $(this).val();
    if (query.length == 0) {
        $(".search-area").html("");
        $('.search-area').append(` <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                    <div class="project-box border shadow py-2 px-3">
                        <div class="d-flex justify-content-between project-h">
                            <div>
                                <h2>Type in the search bar.</h2>
                            </div>
                        </div>
                        <div class="budget d-flex justify-content-between align-items-center">
                        </div>
                    </div>
                </div>`);
        return;
    }
    $.ajax({
        url: "{{route('agency-search-ajax')}}",
        data: {
            query: query
        },
        type: "POST",
        dataType: "JSON",
        success: function(result) {
            $(".search-area").html("");
            if (result.projects.length > 0) {
                var projects = result.projects;
                projects.forEach(function(project, i) {
                    $(".search-area").append(`<div id="project-${project.id}"
                    class="project-container col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                    <div class="project-box border shadow py-2 px-3">
                        <div class="d-flex justify-content-between project-h">
                            <div>
                                <h2>${project.project_title}</h2>
                                <span>${project.client_name}</span>
                            </div>
                            <div>
                                <a href="#" class="btn"><i class="fas fa-ellipsis-v"></i></a>
                            </div>
                        </div>
                        <p>${project.project_description}</p>
                        <div class="budget d-flex justify-content-between align-items-center">
                            <div>
                                <h3>Budget : <span> ${project.project_budget}</span></h3>
                            </div>
                            <div>
                                <a href="{{url('projects/')}}/${project.id}" class="btn">View Project</a>
                            </div>
                        </div>
                    </div>
                </div> `);
                });
            } else {
                $('.search-area').append(` <div class="col-12 col-md-6 col-lg-4 px-md-2 px-0 mt-3">
                    <div class="project-box border shadow py-2 px-3">
                        <div class="d-flex justify-content-between project-h">
                            <div>
                                <h2>No projects found.</h2>
                            </div>
                        </div>
                        <div class="budget d-flex justify-content-between align-items-center">
                        </div>
                    </div>
                </div>`);
            }
        },
    });
});

</script>
@endsection
