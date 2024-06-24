<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible"
        content="IE=edge" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0" />
    <title>Ya-Hub.com</title>
    <link rel="icon"
        href="{{asset('frontend/favicon-32x32.png')}}"
        sizes="16x16"
        type="image/png">
    <link rel="stylesheet"
        href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
        crossorigin="anonymous" />
    <link rel="stylesheet"
        href="{{asset('frontend/assets/css/bootstrap.css')}}" />
    <link rel="stylesheet"
        href="{{asset('frontend/Style/project.css')}}" />
    <link rel="stylesheet"
        href="{{asset('frontend/Style/conversation.css')}}" />
    <link rel="stylesheet"
        href="{{asset('frontend/Style/message.css')}}" />
    <script src="{{asset('frontend/assets/js/jquery.slim.min.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css" />
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    <style>
    .swal2-title {
        margin-bottom: 40px !important;
    }

    </style>
</head>

<body>
    <section>
        <div class="">
            <div class="sticky-top bg-white">
                <div class="user px-sm-3 px-1 py-2 d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <a href="{{$currentAgencyId>0?route($role.'-inbox',['currentAgencyId'=>$currentAgencyId]):route($role.'-inbox')}}"
                            class="btn text-white"><i class="fas fa-arrow-left"></i></a>
                        <div>
                            <h1>{{$user->name}}</h1>
                            <!-- <span>Active Now</span> -->
                        </div>
                    </div>
                    <div class="user-btn">
                        <a href="{{$currentAgencyId>0?url('lite-agency/project/'.$currentAgencyId.'/'.$project->id):url('projects/'.$project->id)}}"
                            class="btn bg-white mx-sm-1 py-1">Go to Project</a>
                        <a href="#"
                            class="btn media-toggle-btn bg-white mx-sm-1 py-1">Media</a>
                        <ul class="media-toggle"> @if(isset($media)) @foreach($media as $md) <li><a class="downloadLink"
                                    target="_blank"
                                    href="{{$md->message}}"><i class="far fa-arrow-alt-circle-down"></i> {{str_replace(url("/uploads/chat/")."/","",$md->message)}} - Download</a></li> @endforeach @endif </ul>
                    </div>
                </div>
                <div class="strip py-1 text-center">
                    <p>{{$project->project_title}}</p>
                </div>
            </div>
            <div class="p-4 chat-box"> @foreach($messages as $message) <?php
    $messageValue = "";
    if ($message->is_text) {
        $messageValue = $message->message;
    }
    if ($message->is_image) {
        $messageValue = '<img src="'.$message->message.'" class="img-fluid img-responsive" height="150px"/>';
    }
    if ($message->is_video) {
        $messageValue = '<video controls heigth="300px">
        <source src="'.$message->message.'">
        </video>';
    }
    if ($message->is_file) {
        $messageValue = '<a target="_blank" class="downloadLink p-0" href="'.$message->message.'"><i class="far fa-arrow-alt-circle-down"></i> '.str_replace(url("/uploads/chat/")."/","",$message->message).'</a>';
    }
   ?> @if($message->sender_id==$AuthUser->id) <div id="message-{{$message->id}}"
                    class="d-flex flex-column justify-content-end text-right">
                    <div class="s-msg">
                        <span class="py-1 px-sm-3 px-1 rounded">@php echo $messageValue @endphp</span>
                    </div>
                    <span class="ml-2 time"><span class="date-text">{{date("H:i d m Y",strtotime($message->created_at))}}</span> <span class="read-status">{{$message->is_delivered==1?"Read":"Sent"}}</span></span>
                </div> @else <div id="message-{{$message->id}}">
                    <div class="r-msg">
                        <span class="py-1 px-sm-3 px-1 rounded">@php echo $messageValue @endphp</span>
                    </div>
                    <span class="ml-2 time"><span class="date-text">{{date("H:i d m Y",strtotime($message->created_at))}}</span> <span class="read-status">{{$message->is_delivered==1?"Read":"Sent"}}</span></span>
                </div> @endif @endforeach </div>
        </div>
    </section>
    <script src="{{asset('frontend/assets/js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('frontend/Script/Script.js')}}"></script>
</body>

</html>
