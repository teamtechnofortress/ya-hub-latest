@extends('layouts.client') @section('content') <div class="border-bottom m-list px-sm-4 px-3 py-3 sticky-top bg-white">
    <h1 style="display: inline;">Inbox</h1>
    <a href="{{route('client-inbox')}}"
        style="float:right"
        class="btn n-project"><i class="fa fa-archive"></i> Unarchived</a>
    <a href="{{url('chats/backup')}}"
        style="float:right"
        class="btn n-project bg-success mx-1"><i class="fa fa-download"></i> Backup</a>
</div>
<div class="px-sm-3 px-2 d-flex border-bottom pb-2 pt-2 pl-5 pr-5 main"> @if(isset($chats) && count($chats)>0) @foreach($chats as $chat) @if(!empty($chat->client)) @php $message=\DB::table('messages')->where('chat_id',$chat->id)->orderBy('id','desc')->first(); @endphp <div class="dot-pos">
        @if($chat->agency->profile_picture)
            <span style="display: block;width: 70px;height: 70px;border-radius: 50%;overflow: hidden;">
                <img style="width:100%" src="{{$chat->agency->profile_picture}}" alt="" srcset="">
            </span> 
        @else
            <span>
                <i style="font-size:70px;" class="fas fa-3x fa-user-circle"></i>
            </span> 
        @endif
        @if($chat->agency->is_online==1) <div class="dot"
            style="right: auto;background: #44c544;bottom:5px;"></div> @else <div class="dot"
            style="right: auto;bottom:5px;"></div> @endif
    </div>
    <div class="d-flex justify-content-between align-items-center w-100 py-2 ml-3 pr-2">
        <a href="{{url('chat/'.$chat->id)}}"
            class="d-flex align-items-center message pl-2">
            <div>
                <h2>{{!empty($chat->client)? $chat->agency->name:""}} | {{!empty($chat->project) ? $chat->project->project_title:""}}</h2>
            </div>
        </a>
        <div style="color: #7d7d7d;font-size: 14px;"> @if($chat->archived==1) <span class="archive mr-3"
                style="cursor:pointer;"
                data-id="{{$chat->id}}"><i class="fa fa-archive"></i> Unarchived</span> @endif <span>{{date("H:i",strtotime(!empty($message)? $message->created_at:$chat->created_at))}}</span>
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
        axios.post("{{route('client-unarchive-post')}}", formdata).then(res => {
            if (res.data.success) {
                $(this).parents('.main').remove()
            } else {
                alert('error')
            }
        })
    })
    </script> @endif @endforeach @endif
</div> @endsection
