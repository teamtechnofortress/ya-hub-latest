@extends('layouts.client') @section('content') 
<style>
        .toolTip{
            position: absolute;
            /* padding: 10px 30px; */
            right: 30px;
            top: 50px;
            width: 120px;
            border-radius: 8px;
            overflow: hidden;
            z-index: 1;
            display: none;
            box-shadow: rgb(0 0 0 / 13%) 0px 0px 5px 0px;    
        }
        .toolTip > ul > li > a{
            width: 100%;
            font-size: 15px;
            text-align: left;
        }
        button.btn-light{
            color: #7e7c7c
        }
    </style>
    @if($projectsLimit == true)
    <style>
        .swal2-title,.swal2-content{
            margin-top: 10px !important;
            margin-bottom: 20px !important;
        }
    </style>
        <script>
           Swal.fire({
                icon: 'error',
                title: 'Project Limit Error !',
                html: 'Usage limit reached. Please contact our <a target="_blank" href="https://ya-hub.com/contact">sales team</a> to upgrade your account.',
            })
        </script>
    @endif
<div class="p-sm-4 p-3 project">
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
                                    <a href="#0" class="btn showToolTip" data-id="{{$project->project_id}}"><i class="fas fa-ellipsis-v"></i></a>
                                </div>
                                @php 
                                    $chats = \DB::table('chats')->where('project_id','=',$project->project_id)->where('client_id','=',\Auth::user()->id)->first();
                                @endphp
                                <div class="toolTip" data-id="{{$project->project_id}}">
                                    <ul style="padding:0">
                                    
                                        <!-- <li><a href="{{url('lite-agency/project/'.$project->id.'/edit')}}" class="btn btn-light btn-sm"><i class="fa fa-edit"></i> Edit</a></li> -->
                                        @if(empty($chats)) 
                                            <li><a href="{{url('client/startchat/'.$project->project_id)}}" class="btn btn-light btn-sm"><i class="fa fa-comment"></i> Start Chat</a></li>
                                        @else
                                            <li><a href="{{url('chat/'.$chats->id)}}" class="btn btn-light btn-sm"><i class="fa fa-comment"></i> Chat</a></li>
                                        @endif
                                        <?php /* <li>
                                            <form method="post"
                                                class="deleteProject"
                                                action="{{url('projects/'.$project->project_id)}}"> 
                                                @csrf 
                                                @method('delete') 
                                                <button class="btn btn-sm btn-light text-left" type="submit" style="width:100%"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </li> */ ?>
                                    </ul>
                                </div>
                            </div>
                            <p>{{$project->project_description}}</p>
                            <div class="budget d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>Budget : <span> {{$project->project_budget}}</span></h3>
                                </div>
                                <div>
                                    <a href="{{url('projects/'.$project->project_id)}}"
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
                                    <a href="#0" class="btn showToolTip" data-id="{{$project->id}}"><i class="fas fa-ellipsis-v"></i></a>
                                </div>
                                @php 
                                    $chats = \DB::table('chats')->where('project_id','=',$project->project_id)->where('client_id','=',\Auth::user()->id)->first();
                                @endphp
                                <div class="toolTip" data-id="{{$project->project_id}}">
                                    <ul style="padding:0">
                                    
                                        <!-- <li><a href="{{url('lite-agency/project/'.$project->id.'/edit')}}" class="btn btn-light btn-sm"><i class="fa fa-edit"></i> Edit</a></li> -->
                                        @if(empty($chats)) 
                                            <li><a href="{{url('client/startchat/'.$project->project_id)}}" class="btn btn-light btn-sm"><i class="fa fa-comment"></i> Start Chat</a></li>
                                        @else
                                            <li><a href="{{url('chat/'.$chats->id)}}" class="btn btn-light btn-sm"><i class="fa fa-comment"></i> Chat</a></li>
                                        @endif
                                        <?php /* <li>
                                            <form method="post"
                                                class="deleteProject"
                                                action="{{url('projects/'.$project->project_id)}}"> 
                                                @csrf 
                                                @method('delete') 
                                                <button class="btn btn-sm btn-light text-left" type="submit" style="width:100%"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </li> */ ?>
                                    </ul>
                                </div>
                            </div>
                            <p>{{$project->project_description}}</p>
                            <div class="budget d-flex justify-content-between align-items-center">
                                <div>
                                    <h3>Budget : <span> {{$project->project_budget}}</span></h3>
                                </div>
                                <div>
                                    <a href="{{url('projects/'.$project->project_id)}}"
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
<script>
    $('.toolTip > ul > li > a').click(function(){
        window.location.href = $(this).attr('href')
    })
    $('.deleteProject > button').click(function(){
        $(this).parent('form.deleteProject').submit()
    })

    $('.showToolTip').click(function(e){
        console.log($(this).data('id'))
        var dataId = $(this).data('id')
        e.stopPropagation()
        $("div").find(`div.toolTip[data-id='${dataId}']`).toggle()
    })
    $(document).click(function() {
        $('.toolTip').hide()
    })
    $(".toolTip").click(function(e) {
        e.stopPropagation()
        return false
    })
</script>
@endsection
