@php
$query = DB::table('departments')->where('created_by',Auth::user()->id)->where('active',1)->first();
@endphp

@if($query)
@include('layouts.departments')
@else
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
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>
    @php 
        $user=\DB::table('users')->where('id',\Auth::user()->id)->orderBy('id','desc')->first();
        $theme_style = $user->theme_style;
        $logo = NULL;
    @endphp
    <?php 
        $theme_style = json_decode($theme_style);
        if($user->theme_setting==1){
            $logo = $user->theme_log;
    ?>
    <style>
        body,.bg-white{
            background: <?=$theme_style->bg_color?>;
            color: <?=$theme_style->font_color?>;
        }
        .border-right {
            border-right: 1px solid <?=$theme_style->bg_color?> !important;
        }
        .border-bottom {
            border-bottom: 1px solid <?=$theme_style->bg_color?> !important;
        }
        .sticky-top{
            filter: drop-shadow(15px 5px 4px #acacac);
        }
        .user {
            background-color: <?=$theme_style->btn_secondary_bg_color?>;
        }
        .chat-head{
            background-color: <?=$theme_style->btn_primary_bg_color?> !important;
        }
        .user > a,h1{
            color: <?=$theme_style->btn_secondary_font_color?> !important;
        }
        h1,h2,h3,h4,h5,h6{
            color: <?=$theme_style->heading?> !important;
        }
        p{
            color: <?=$theme_style->paragraph?>
        }
        label,span,small,strong,.li-a{
            color: <?=$theme_style->lable?>
        }
        .border-right{
            border-right: 1px solid <?=$theme_style->border?> !important;
        }
        .border-left{
            border-left: 1px solid <?=$theme_style->border?> !important;
        }
        .border-top{
            border-top: 1px solid <?=$theme_style->border?> !important;
        }
        .border-bottom{
            border-bottom: 1px solid <?=$theme_style->border?> !important;
        }
        table,.table,tr,th,td,.table-bordered td, .table-bordered th{
            border: 1px solid <?=$theme_style->border?> !important;
        }
        table.dataTable tbody tr {
            background: <?=$theme_style->bg_color?> !important;
            color: <?=$theme_style->font_color?> !important;
        }
        .n-project,.btn-primary,.p-opt-btn.active,.budget a{
            background-color: <?=$theme_style->btn_primary_bg_color?>;
            color: <?=$theme_style->btn_primary_font_color?>;
            border: 0;
        }
        .n-project:hover,.btn-primary:hover,.p-opt-btn.active:hover,.budget a:hover{
            background-color: <?=$theme_style->btn_primary_bg_color?> !important;
            color: <?=$theme_style->btn_primary_font_color?> !important;
            border: 0;
        }
        .p-opt-btn{
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .p-opt-btn:hover{
            color: #fff;
            background-color: #5a6268;
            border-color: #545b62;
        }
        .btn-secondary,.p-opt-btn{
            background-color: <?=$theme_style->btn_secondary_bg_color?>;
            color: <?=$theme_style->btn_secondary_font_color?>;
            border: 0;
        }
        .btn-secondary:hover,.p-opt-btn:hover{
            background-color: <?=$theme_style->btn_secondary_bg_color?> !important;
            color: <?=$theme_style->btn_secondary_font_color?> !important;
            border: 0;
        }
        .bg-danger,.btn-danger{
            background-color: <?=$theme_style->btn_danger_bg_color?>;
            color: <?=$theme_style->btn_danger_font_color?>;
            border: 0;
        }
        .bg-danger:hover,.btn-danger:hover{
            background-color: <?=$theme_style->btn_danger_bg_color?> !important;
            color: <?=$theme_style->btn_danger_font_color?> !important;
            border: 0;
        }
        .btn-info{
            background-color: <?=$theme_style->btn_info_bg_color?>;
            color: <?=$theme_style->btn_info_font_color?>;
            border: 0;
        }
        .btn-info:hover{
            background-color: <?=$theme_style->btn_info_bg_color?> !important;
            color: <?=$theme_style->btn_info_font_color?>;
            border: 0;
        }
        .form-control,select,input[type="search"]{
            background-color: <?=$theme_style->text_bg_color?>;
            color: <?=$theme_style->text_font_color?>;
            border: <?=$theme_style->text_border?>;
        }
        .form-control:focus{
            background-color: <?=$theme_style->text_bg_color?>;
            color: <?=$theme_style->text_font_color?>;
            border: <?=$theme_style->text_border?>;
        }
        .form-control::placeholder {
            color: <?=$theme_style->lable?>
        }
        .fas{
            color: <?=$theme_style->icon_color?>;
        }
        .nav-active > i{
            color: <?=$theme_style->active_icon_color?>;
        }
    </style>
    <?php }?>
</head>

<body>
    <section> @include('lite-agency.partial.desktop-menu') <div class="other-cont"> @include('lite-agency.partial.top-bar') @include('messages') @yield('content') </div> @include('lite-agency.partial.mobile-menu') </section>
    <script src="{{asset('frontend/assets/js/bootstrap.bundle.js')}}"></script>
    <script src="{{asset('frontend/Script/Script.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('.table').DataTable();
        $('.dataTables_filter > label').contents().filter(function() {
            return this.nodeType === 3; // Filter out non-text nodes
        }).replaceWith('Search: ')

        $('.table-hidden-estimate').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'csv',
                    text: 'Export as CSV',
                    charset: 'UTF-8'
                },
                {
                    extend: 'excel',
                    text: 'Export as Excel',
                    charset: 'UTF-8'
                }
            ]
        })
    });
    </script>
</body>

</html>
@endif
