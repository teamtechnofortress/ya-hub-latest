@extends('layouts.lite-agency') @section('content') <div class="border-bottom m-list px-sm-4 px-3 py-3 sticky-top bg-white">
@if($chatLimit == true)
    <style>
        .swal2-title,.swal2-content{
            margin-top: 10px !important;
            margin-bottom: 20px !important;
        }
    </style>
        <script>
           Swal.fire({
                icon: 'error',
                title: 'Inbox Limit Error !',
                html: 'Usage limit reached. Please contact our <a target="_blank" href="https://ya-hub.com/contact">sales team</a> to upgrade your account.',
            })
        </script>
    @endif  
    <h1 style="display: inline;">Inbox</h1>
    <a href="{{route('lite-agency-inbox-archived')}}"
        style="float:right"
        class="btn n-project"><i class="fa fa-archive"></i> Archived</a>
</div> @if(isset($chats) && count($chats)>0) @foreach($chats as $chat) @if(!empty($chat->client)) @php $message=\DB::table('messages')->where('chat_id',$chat->id)->orderBy('id','desc')->first(); @endphp <div class="px-sm-3 px-2 d-flex border-bottom pb-2 pt-2 pl-5 pr-5 main">
    <div class="dot-pos">
        @if($chat->client->profile_picture)
            <span style="display: block;width: 70px;height: 70px;border-radius: 50%;overflow: hidden;">
                <img style="width:100%" src="{{$chat->client->profile_picture}}" alt="" srcset="">
            </span> 
        @else
            <span>
                <i style="font-size:70px;" class="fas fa-3x fa-user-circle"></i>
            </span> 
        @endif
        @if($chat->client->is_online==1) 
            <div class="dot" style="right: auto;background: #44c544;"></div> 
        @else 
            <div class="dot" style="right: auto;"></div> 
        @endif
    </div>
    <div class="d-flex justify-content-between align-items-center w-100 py-2 ml-3 pr-2">
        <a href="{{url('chat/'.$chat->id)}}"
            class="d-flex align-items-center message pl-2">
            <div>
                <h2>@if(!empty($message)) @if($message->sender_id!=\Auth::user()->id && $message->is_delivered==0)<strong> {{!empty($chat->client)? $chat->client->name:""}} | {{!empty($chat->project) ? $chat->project->project_title:""}} </strong> @else {{!empty($chat->client)? $chat->client->name:""}} | {{!empty($chat->project) ? $chat->project->project_title:""}} @endif @else {{!empty($chat->client)? $chat->client->name:""}} | {{!empty($chat->project) ? $chat->project->project_title:""}} @endif
                </h2>
                <p class="wrap-text">@if(!empty($message)) @if($message->sender_id!=\Auth::user()->id && $message->is_delivered==0) <strong>{{$message->message}}</strong> @else {{$message->message}} @endif @endif </p>
            </div>
        </a>
        <div style="color: #7d7d7d;font-size: 14px;"> <span class="delete-chat mr-3"
                style="cursor:pointer;"
                data-id="{{$chat->id}}"><i class="fa fa-trash"></i> Delete</span> @if($chat->archived==0) <span class="archive mr-3"
                style="cursor:pointer;"
                data-id="{{$chat->id}}"><i class="fa fa-archive"></i> Archive</span> @endif <span>{{date("H:i",strtotime(!empty($message)? $message->created_at:$chat->created_at))}}</span>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"
        integrity="sha512-bZS47S7sPOxkjU/4Bt0zrhEtWx0y0CRkhEp8IckzK+ltifIIE9EMIMTuT/mEzoIMewUINruDBIR/jJnbguonqQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script>
    $('.archive').click(function() {
        var formdata = new FormData()
        formdata.append('chat_id', $(this).data('id'))
        axios.post("{{route('lite-archive-post')}}", formdata).then(res => {
            if (res.data.success) {
                $(this).parents('.main').remove()
            } else {
                alert('error')
            }
        })
    })
    $('.delete-chat').click(function() {
        Swal.fire({
            title: "Delete Chat",
            text: "Are you sure?",
            icon: "warning",
            confirmButtonText: "Yes",
            showCancelButton: true
        }).then((value) => {
            if (value.isConfirmed) {
                var formdata = new FormData()
                formdata.append('chat_id', $(this).data('id'))
                axios.post("{{route('lite-delete-chat')}}", formdata).then(res => {
                    if (res.data.success) {} else {
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong, Try again!",
                            icon: "warning",
                            confirmButtonText: "Ok"
                        })
                    }
                })
            }
        });
    })
    </script>
</div> @endif @endforeach @endif @endsection
