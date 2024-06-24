 <div
     class="header d-flex justify-content-between align-items-center py-3 px-sm-4 px-3 border-bottom">
     <div>
        @if($logo)
            <img src="{{$logo}}"
             class="img-fluid"
             alt="logo" />
        @else
            <img src="{{asset('frontend/Pics/logo.png')}}" class="img-fluid"
             alt="logo" />
        @endif
     </div>
     <div>
        <a href="{{url('logout')}}" class="btn btn-secondary btn-sm"><i class="fa fa-sign-out"></i></a>
     </div>
     <style>
         table.dataTable thead th, table.dataTable thead td{
            border: none !important;
            text-align: center;
         }
         table.dataTable.no-footer{
            border-bottom: none !important;
            text-align: center;
         }
     </style>
 </div>
