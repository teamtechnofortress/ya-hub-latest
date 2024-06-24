@extends('layouts.'.$role) 
<style>
.swal2-title {
    margin-bottom: 40px !important;
}

.grey-emoji {
    margin-right: 10px !important;
}
.media-toggle{
    border-radius: 20px !important;
    top: 50px !important;
    box-shadow: 0px 0px 10px 0px #00000042 !important;
    border: 0 !important;
    padding: 10px !important;
}
.context-menu-list{
    border: 0px !important;
    border-radius: 15px !important;
    overflow: hidden !important;
}
.context-menu-list > li{
    font-size: 12px;
}
.li-a{
    font-size: 12px
}

</style>
<style>
    .tooltip-link {
        position: relative;
        font-size: 25px !important;
        padding: 15px;
    }

    .tooltip-link > .tooltiptext {
        visibility: hidden;
        width: 60px;
        top: -15px;
        left: 0;
        background-color: white;
        color: #4c4c4c !important;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        font-size: 10px !important;
    }

    .tooltip-link:hover > .tooltiptext {
        visibility: visible;
    }
    .img_profile{
        display: block;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }
    #sendMessage{
        padding: 6px 10px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.46);
        width: 55% !important;
        border: 1px solid rgba(0, 0, 0, 0.08) !important;
    }
    #toggle-media{
        display:inline
    }
    .media-list{
        position: absolute;
        user-select: auto;
        display: none;
        width: 48px;
        top: -115px;
        background: white;
        border-radius: 10px;
        left: 18px;
        box-shadow: 0px 0px 10px 0px #00000033;
    }
    @media only screen and (max-width: 992px) {
        .user_name {
            font-size: 10px !important;
        }
        .arrow-left{
            font-size: 10px;
            padding: 10px;
        }
        .img_profile{
            width: 35px;
            height: 35px;
        }
        .btn , a , .tooltip-link{
            font-size: 8px !important;
            padding: 8px !important
        }
        a > i,  button > i{
            font-size: 14px !important;
        }
        .media-list{
            position: absolute;
            user-select: auto;
            display: none;
            width: 33px;
            top: -100px;
            background: white;
            border-radius: 10px;
            left: 18px;
        }
        #toggle-media{
            display:inline
        }
        .tooltip-link > .tooltiptext {
            visibility: hidden;
            width: 60px;
            top: -25px;
            left: -50px;
        }
    }
</style>
@section('content') 
<div class="sticky-top bg-white">
    <div class="user chat-head px-sm-3 px-1 py-2 d-flex justify-content-between align-items-center">
        <div class="d-flex">
            <a class="arrow-left" href="{{route($role.'-inbox')}}"
                class="btn text-white d-flex" style="align-items:center"><i class="fas fa-arrow-left"></i></a>
            <div style="display: flex;justify-content: center;align-items: center;">
                @if(isset($user))
                    @if(isset($user->name))
                        @if($user->profile_picture)
                            <span class="img_profile">
                                <img style="width:100%" src="{{$user->profile_picture}}" alt="" srcset="">
                            </span> 
                        @else
                            <span>
                                <i class="fas fa-3x fa-user-circle"></i>
                            </span> 
                        @endif
                        <h1 class="user_name" style="margin-left: 10px !important;">{{$user->name}}</h1>
                    @endif
                @endif
                <!-- <span>Active Now</span> -->
            </div>
        </div>
        <div>
            <a class="tooltip-link" href="{{url('projects/'.$project->id)}}"
                class="btn bg-white mx-sm-1 py-1">🚧
                <span class="tooltiptext">Project</span>
            </a>
            <a class="tooltip-link" onclick="$('.media-toggle').toggle()" href="#0"
                class="btn media-toggle-btn bg-white mx-sm-1 py-1">🗂
                <span class="tooltiptext">Media</span>
            </a>
            <ul class="media-toggle"> 
                @if(isset($media)) 
                @foreach($media as $md) 
                <li>
                    <a class="downloadLink li-a" target="_blank" href="{{$md->message}}">
                        <i class="far fa-arrow-alt-circle-down"></i> 
                        {{str_replace(url("/uploads/chat/")."/","",$md->message)}} - Download
                    </a>
                </li> 
                @endforeach 
                @endif 
            </ul>
        </div>
    </div>
    <div class="strip py-1 text-center">
        <p>{{$project->project_title}}</p>
    </div>
</div>
<div class="p-4 chat-box" style="padding-bottom: 3.5rem !important;"> 
    @if(count($messages) > 0)
    @foreach($messages as $message) 
    <?php
    $messageValue = "";
    if ($message->is_text) {
        $messageValue = $message->message;
    }
    if ($message->is_image) {
        $messageValue = '<img src="'.$message->message.'" class="img img-responsive" height="150px"/>';
    }
    if ($message->is_video) {
        $messageValue = '<video controls heigth="300px">
        <source src="'.$message->message.'">
        </video>';
    }
    if ($message->is_file) {
        $messageValue = '<a target="_blank" class="downloadLink p-0" href="'.$message->message.'"><i class="far fa-arrow-alt-circle-down"></i> '.str_replace(url("/uploads/chat/")."/","",$message->message).'</a>';
    }
   ?> 
   @if($message->sender_id==$AuthUser->id) 
   <div id="message-{{$message->id}}"
        class="d-flex flex-column justify-content-end text-right">
        <div class="s-msg">
            <span class="py-1 px-sm-3 px-1 rounded">@php echo $messageValue @endphp</span>
        </div>
        <span class="ml-2 time">
        @if(!$message->is_image && !$message->is_video && !$message->is_file)
            <span><a class="edit_msg" href="#0" data-id="{{$message->id}}" style="color: #fc5c29;">Edit <i class="fa fa-edit"></i></a></span>
        @endif
            <span class="date-text">{{date("H:i d m Y",strtotime($message->created_at))}}</span> 
            <span class="read-status">{{$message->is_delivered==1?"Read":"Sent"}}</span>
        </span>
    </div> 
    @else 
    <div id="message-{{$message->id}}">
        <div class="r-msg">
            <span class="py-1 px-sm-3 px-1 rounded">@php echo $messageValue @endphp</span>
        </div>
        <span class="ml-2 time"><span class="date-text">{{date("H:i d m Y",strtotime($message->created_at))}}</span> <span class="read-status">{{$message->is_delivered==1?"Read":"Sent"}}</span></span>
    </div> 
    @endif 
    @endforeach 
    @else
        <div class="row" id="message-info">
            <div class="col-lg-12">
                <div class="alert alert-info">No chat created for this project.</div>
            </div>
        </div>
    @endif
</div>
<form class="type bg-white w-100 d-flex align-items-center"
    enctype="multipart/form-data"
    id="messageForm"
    method="POST">
    <span class="hover-file-name-error"></span>
<div class="media-list">
    <a href="#"
        class="sendFile btn text-secondary"><i class="fas fa-paperclip"></i></a>
    <a href="#"
        class="sendImage btn text-secondary"><i class="fas fa-image"></i></a>
    <a href="#"
        class="sendVideo btn text-secondary"><i class="fas fa-video"></i></a>
    <input type="hidden" name="is_update" id="is_update" value="0">
    <input type="file"
        name="sendFile[]"
        class="hidden fileSubmit"
        id="sendFile"
        multiple />
    <input type="file"
        name="sendImage"
        class="hidden fileSubmit"
        id="sendImage"
        accept="image/*" />
    <input type="file"
        name="sendVideo"
        class="hidden fileSubmit"
        id="sendVideo"
        accept="video/*" />
</div>
    <a href="#0" id="toggle-media" class="btn text-secondary"><i class="fa fa-angle-right"></i></a>
    <input type="text"
        id="sendMessage"
        class="border-0"
        style="width:75%!important;"
        placeholder="Write message here...">
    <div style="text-align:right;">
        <button href="#"
            class="sendBtn btn text-secondary"><i class="fas fa-paper-plane"></i></button>
        <button href="#" style="display:none"
            class="updateBtn btn btn-light"><i class="fa fa-refresh"></i> Update</button>
        <a href="#0" style="display:none;font-size: 14px !important;" id="resetBtn">Cancel</a>
    </div> 
    @csrf
</form>
<script type="text/javascript"
    src="{{asset('frontend/emojionearea/emoji.js')}}"></script>
<script>
    $("#toggle-media").click(function(){
        $('.media-list').toggle()
    })
var chat_id = "{{$chat->id}}";
var sender_id = "{{$AuthUser->id}}";
var receiver_id = "{{$receiver_id}}";
var fileSizeLimit = parseInt("{{$AuthUser->upload_limit_in_mbs}}");
$(document).ready(function() {
    function resetEdit(){
        $(".sendFile").toggle()
        $(".sendImage").toggle()
        $(".sendVideo").toggle()
        $(".sendBtn").toggle()
        $(".updateBtn").toggle()
        $("#is_update").val("")
        $("#sendMessage").val("")
        $("#resetBtn").toggle()
    }
    $("#resetBtn").click(function(){
        resetEdit()
    })
    /* Edit Messages */
    $("body").delegate('.edit_msg','click',function(){
        var id = $(this).data('id')
        $(".sendFile").toggle()
        $(".sendImage").toggle()
        $(".sendVideo").toggle()
        $("#resetBtn").toggle()
        $.ajax({
                url: `{{url('chat-edit/${id}')}}`,
                type: "GET",
                dataType: "JSON",
                success: function(res) {
                    $("#is_update").val(id)
                    $("#sendMessage").val(res.message[0].message)
                    $(".sendBtn").toggle()
                    $(".updateBtn").toggle()
                }
        })
    })
    $("#sendMessage").emoji();
    $.contextMenu({
        selector: '.downloadLink',
        items: {
            download: {
                name: "Download",
                callback: function(key, opt) {
                    $(opt.$trigger).trigger("click");
                }
            },
            view: {
                name: "View",
                callback: function(key, opt) {
                    window.open(opt.$trigger.attr("href"));
                }
            }
        }
    });
    $(document).on("click", ".downloadLink", function(e) {
        e.preventDefault();
        $link = encodeURI($(this).attr("href"));
        Swal.fire({
            title: "Download File",
            text: "",
            icon: "info",
            confirmButtonText: "Yes",
        }).then((value) => {
            if (value.isConfirmed) {
                window.open("{{url('/download')}}?url=" + $link);
            }
        });
    })

    function constructDate(date) {
        var cDate = new Date(date);
        var month = cDate.getUTCMonth();
        month++;
        if (month < 10) {
            month = "0" + month;
        }
        return cDate.getUTCHours() + ":" + cDate.getUTCMinutes() + " " + cDate.getUTCDate() + " " + month + " " + cDate.getUTCFullYear();
    }
    $(".chat-box").animate({
        scrollTop: $(".chat-box")[0].scrollHeight
    }, "slow");
    $('.sendImage').on("click", function() {
        $('#sendImage').trigger("click");
    })
    $('.sendFile').on("click", function() {
        $('#sendFile').trigger("click");
    })
    $('.sendVideo').on("click", function() {
        $('#sendVideo').trigger("click");
    })
    $(".fileSubmit").on("change", function() {
        $(".hover-file-name").remove();
        $(".hover-file-name-error").hide();
        var input = $(this)[0];
        for (var i = 0; i < input.files.length; i++) {
            var file = "";
            var bottom = 40;
            if (i > 0) {
                bottom = bottom + 25;
            }
            file = `<span class="hover-file-name" style="bottom:${bottom}px;">${input.files[i].name}</span>`;
            if (input.files[i].size > (fileSizeLimit * 1000000)) {
                file = "Cannot upload a file bigger than " + fileSizeLimit + "MB";
                $(".hover-file-name-error").html(file);
                $(".hover-file-name-error").toggle(":visibility");
                input.files[i] = null;
                break;
            }
            $("#messageForm").append(file);
        }
        $(".hover-file-name").toggle(":visibility");
    })
    $(".sendBtn").on("click", function(e) {
        var messageText = $("#sendMessage").val();
        e.preventDefault();
        if (messageText.length > 0) {
            $("#sendMessage").val("");
            $.ajax({
                url: "{{route('chat-ajax')}}",
                type: "POST",
                data: {
                    chat_id: chat_id,
                    receiver_id: receiver_id,
                    sender_id: sender_id,
                    message: messageText,
                    action: "send_message",
                    _token: $("input[name=_token]").val()
                },
                dataType: "JSON",
                success: function(result) {
                    if (result.success) {
                        var message = result.message;
                        var date = constructDate(message.created_at);
                        var status = "Sent";
                        if (message.is_delivered == 1) {
                            status = "Read";
                        }
                        $("#message-info").hide()
                        $(".chat-box").
                        append(`<div id="message-${message.id}" class="d-flex flex-column justify-content-end text-right">
                                    <div class="s-msg">
                                        <span class="py-1 px-sm-3 px-1 rounded">${message.message}</span>
                                    </div>
                                    <span class="ml-2 time">
                                        <span><a class="edit_msg" href="#0" data-id="${message.id}" style="color: #fc5c29;">Edit <i class="fa fa-edit"></i></a></span>
                                        <span class="date-text">${date}</span> 
                                        <span class="read-status">${status}</span>
                                    </span>
                                </div>`);
                        $(".chat-box").animate({
                            scrollTop: $(".chat-box")[0].scrollHeight
                        }, "slow");
                    }
                },
                error: function(error) {},
            })
        } else {
            var imageFile = $("#sendImage");
            var docFile = $("#sendFile");
            var videoFile = $("#sendVideo");
            $(".hover-file-name").toggle(":visibility");
            if (imageFile[0].files.length > 0) {
                var formData = new FormData($("#messageForm")[0]);
                formData.append("sender_id", sender_id);
                formData.append("receiver_id", receiver_id);
                formData.append("chat_id", chat_id);
                formData.append("action", "send_image");
                $("#sendImage").val("");
                $.ajax({
                    url: "{{route('chat-ajax')}}",
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function(result) {
                        if (result.success) {
                            var message = result.message;
                            var date = constructDate(message.created_at);
                            var status = "Sent";
                            if (message.is_delivered == 1) {
                                status = "Read";
                            }
                            var messageValue = "";
                            if (message.is_text) {
                                messsageValue = message.message;
                            }
                            if (message.is_image) {
                                messsageValue = `<img src="${message.message}" class="img img-responsive" height="150px"/>`;
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                            }
                            if (message.is_video) {
                                messsageValue = `<video controls heigth="300px">
                                <source src="${message.message}">
                                </video>`;
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                            }
                            if (message.is_file) {
                                messsageValue = `<a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> Download</a>`;
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                            }
                                $(".chat-box").
                                append(`<div id="message-${message.id}" class="d-flex flex-column justify-content-end text-right">
                                        <div class="s-msg">
                                            <span class="py-1 px-sm-3 px-1 rounded">${messsageValue}</span>
                                        </div>
                                        <span class="ml-2 time">
                                        <span class="date-text">${date}</span> <span class="read-status">${status}</span></span>
                                    </div>`);
                            $(".chat-box").animate({
                                scrollTop: $(".chat-box")[0].scrollHeight
                            }, "slow");
                        }
                    },
                    error: function(error) {},
                })
            }
            if (docFile[0].files.length > 0) {
                var formData = new FormData($("#messageForm")[0]);
                formData.append("sender_id", sender_id);
                formData.append("receiver_id", receiver_id);
                formData.append("chat_id", chat_id);
                formData.append("action", "send_file");
                $("#sendImage").val("");
                $.ajax({
                    url: "{{route('chat-ajax')}}",
                    type: "POST",
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function(result) {
                        if (result.success) {
                            var messages = result.messages;
                            messages.map((message) => {
                                var date = constructDate(message.created_at);
                                var status = "Sent";
                                if (message.is_delivered == 1) {
                                    status = "Read";
                                }
                                var messageValue = "";
                                if (message.is_text) {
                                    messsageValue = message.message;
                                }
                                if (message.is_image) {
                                    messsageValue = `<img src="${message.message}" class="img img-responsive" height="150px"/>`;
                                    $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                }
                                if (message.is_video) {
                                    messsageValue = `<video controls heigth="300px">
                                <source src="${message.message}">
                                </video>`;
                                    $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                }
                                if (message.is_file) {
                                    messsageValue = `<a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}/","")}</a>`;
                                    $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                }
                                    $(".chat-box").
                                    append(`<div id="message-${message.id}" class="d-flex flex-column justify-content-end text-right">
                                        <div class="s-msg">
                                            <span class="py-1 px-sm-3 px-1 rounded">${messsageValue}</span>
                                        </div>
                                        <span class="ml-2 time">
                                        <span class="date-text">${date}</span> <span class="read-status">${status}</span></span>
                                    </div>`);
                            })
                            $(".chat-box").animate({
                                scrollTop: $(".chat-box")[0].scrollHeight
                            }, "slow");
                        }
                    },
                    error: function(error) {},
                })
            }
            if (videoFile[0].files.length > 0) {
                var formData = new FormData($("#messageForm")[0]);
                formData.append("sender_id", sender_id);
                formData.append("receiver_id", receiver_id);
                formData.append("chat_id", chat_id);
                formData.append("action", "send_video");
                $("#sendImage").val("");
                $.ajax({
                    url: "{{route('chat-ajax')}}",
                    type: "POST",
                    data: formData,
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function(result) {
                        if (result.success) {
                            var message = result.message;
                            var date = constructDate(message.created_at);
                            var status = "Sent";
                            if (message.is_delivered == 1) {
                                status = "Read";
                            }
                            var messageValue = "";
                            if (message.is_text) {
                                messsageValue = message.message;
                            }
                            if (message.is_image) {
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                messsageValue = `<img src="${message.message}" class="img img-responsive" height="150px"/>`;
                            }
                            if (message.is_video) {
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                messsageValue = `<video controls heigth="300px">
                                <source src="${message.message}">
                                </video>`;
                            }
                            if (message.is_file) {
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                messsageValue = `<a target="_blank" class="btn btn-secondary btn-sm p-0" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> Download</a>`;
                            }
                                $(".chat-box").
                                append(`<div id="message-${message.id}" class="d-flex flex-column justify-content-end text-right">
                                        <div class="s-msg">
                                            <span class="py-1 px-sm-3 px-1 rounded">${messsageValue}</span>
                                        </div>
                                        <span class="ml-2 time">
                                        <span class="date-text">${date}</span> <span class="read-status">${status}</span></span>
                                    </div>`);
                                $(".chat-box").animate({
                                    scrollTop: $(".chat-box")[0].scrollHeight
                                }, "slow");
                        }
                    },
                    error: function(error) {},
                })
            }
        }
    })
    /* Update Message */
    $(".updateBtn").on("click", function(e) {
        var messageText = $("#sendMessage").val();
        var message_id = $("#is_update").val()
        e.preventDefault();
        if (messageText.length > 0) {
            $("#sendMessage").val("");
            $.ajax({
                url: "{{route('chat-update')}}",
                type: "POST",
                data: {
                    is_update: $("#is_update").val(),
                    chat_id: chat_id,
                    receiver_id: receiver_id,
                    sender_id: sender_id,
                    message: messageText,
                    action: "send_message",
                    _token: $("input[name=_token]").val()
                },
                dataType: "JSON",
                success: function(result) {
                    if (result.success) {
                        resetEdit()
                        $("#message-info").hide()
                        $("#message-"+message_id).find(".s-msg").html(`<span class="py-1 px-sm-3 px-1 rounded">${messageText}</span>`)
                        // append(`<div id="message-${message.id}" class="d-flex flex-column justify-content-end text-right">
                        //             <div class="s-msg">
                        //                 <span class="py-1 px-sm-3 px-1 rounded">${message.message}</span>
                        //             </div>
                        //             <span class="ml-2 time">
                        //                 <span><a class="edit_msg" href="#0" data-id="${message.id}" style="color: #fc5c29;">Edit <i class="fa fa-edit"></i></a></span>
                        //                 <span class="date-text">${date}</span> 
                        //                 <span class="read-status">${status}</span>
                        //             </span>
                        //         </div>`);
                        // $(".chat-box").animate({
                        //     scrollTop: $(".chat-box")[0].scrollHeight
                        // }, "slow");
                    }
                },
                error: function(error) {},
            })
        }
    })
    // $("#sendMessage").on("keyup", function(e) {
    //     var messageText = $(this).val();
    //     if (e.keyCode == 13) {
    //         e.preventDefault();
    //         $(this).val("");
    //         $.ajax({
    //             url: "{{route('chat-ajax')}}",
    //             type: "POST",
    //             data: {
    //                 chat_id: chat_id,
    //                 receiver_id: receiver_id,
    //                 sender_id: sender_id,
    //                 message: messageText,
    //                 action: "send_message",
    //                 _token: $("input[name=_token]").val()
    //             },
    //             dataType: "JSON",
    //             success: function(result) {
    //                 if (result.success) {
    //                     var message = result.message;
    //                     var date = constructDate(message.created_at);
    //                     $(".chat-box").
    //                     append(`<div class="d-flex flex-column justify-content-end text-right">
    //                                 <div class="s-msg">
    //                                     <span class="py-1 px-sm-3 px-1 rounded">${message.message}</span>
    //                                 </div>
    //                                 <span class="ml-2 time"><span class="date-text">${date}</span></span>
    //                             </div>`);
    //                     $(".chat-box").animate({
    //                         scrollTop: $(".chat-box")[0].scrollHeight
    //                     }, "slow");
    //                 }
    //             },
    //             error: function(error) {},
    //         })
    //     }
    // })
    setInterval(() => {
        $.ajax({
            url: "{{route('chat-ajax')}}",
            type: "POST",
            data: {
                chat_id: chat_id,
                receiver_id: receiver_id,
                action: "fetch_messages",
                _token: $("input[name=_token]").val()
            },
            dataType: "JSON",
            success: function(result) {
                if (result.success) {
                    if (result.message_count > 0) {
                        result.messages.forEach(function(message, i) {
                            var date = constructDate(message.created_at);
                            var status = "Sent";
                            var messageValue = message.message;
                            if (message.is_text == 1) {
                                messsageValue = message.message;
                            }
                            if (message.is_delivered == 1) {
                                status = "Read";
                            }
                            if (message.is_image == 1) {
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                messsageValue = `<img src="${message.message}" class="img img-responsive" height="150px"/>`;
                            }
                            if (message.is_video == 1) {
                                $(".media-toggle").append(`
                        <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                        `);
                                messsageValue = `<video controls heigth="300px">
                                <source src="${message.message}">
                                </video>`;
                            }
                            if (message.is_file == 1) {
                                $(".media-toggle").append(`
                                <li><a class="downloadLink" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> ${message.message.replace("{{url("/uploads/chat/")}}","")}</a></li>
                                `);
                                messsageValue = `<a class="btn" href="${message.message}"><i class="far fa-arrow-alt-circle-down"></i> Download</a>`;
                            }
                            if (message.is_image == 0 && message.is_video == 0 && message.is_file == 0) {
                                $(".chat-box").
                                append(`<div id="message-${message.id}">
                                            <div class="r-msg">
                                                <span class="py-1 px-sm-3 px-1 rounded">${messageValue}</span>
                                            </div>
                                            <span class="ml-2 time">
                                            <span>
                                                <a class="edit_msg" href="#0" data-id="${message.id}" style="color: #fc5c29;">Edit <i class="fa fa-edit"></i></a>
                                            </span>
                                            <span class="date-text">${date}</span> <span class="read-status">${status}</span></span>
                                        </div>`);
                                // })
                            }
                            else{
                                $(".chat-box").
                                append(`<div id="message-${message.id}">
                                            <div class="r-msg">
                                                <span class="py-1 px-sm-3 px-1 rounded">${messageValue}</span>
                                            </div>
                                            <span class="ml-2 time">
                                            <span class="date-text">${date}</span> <span class="read-status">${status}</span></span>
                                        </div>`);
                            // }
                            // })
                        }
                        $(".chat-box").animate({
                            scrollTop: $(".chat-box")[0].scrollHeight
                        }, "slow");
                        })
                    }
                    if (result.delivered.length > 0) {
                        result.delivered.forEach(function(d, i) {
                            $($("#message-" + d).find("span.time")).find(".read-status").html("Read");
                        })
                    }
                }
            },
            error: function(error) {},
        })
    }, 3000);
})
</script> @endsection
