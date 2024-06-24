 <div class="header d-flex justify-content-between align-items-center py-3 px-sm-4 px-3 border-bottom">
     <div>
        @if($logo)
            <img src="{{$logo}}"
             class="img-fluid"
             alt="logo" />
        @else
            <img src="{{asset('frontend/Pics/logo.png')}}"
             class="img-fluid"
             alt="logo" />
        @endif
         <br /> <a href="{{route('lite-agency-dashboard')}}"
             class="mt-4 btn n-project btn-sm"><i class="far fa-arrow-alt-circle-left"></i> Projects</a>
     </div>
     <div>
            <a href="{{route('lite-agency-newproject')}}" class="btn n-project btn-sm">+ New Project</a>
            @php 
                $enable_crm = \DB::table('users')->where('id',\Auth::user()->id)->first()->enable_crm;
            @endphp
            @if($enable_crm==1)
                <a href="{{url('companies')}}" class="btn btn-light btn-sm">Companies</a>
                <a href="{{url('contacts')}}" class="btn btn-light btn-sm">Contacts</a>
                <a href="{{url('tasks')}}" class="btn btn-light btn-sm">Tasks</a>
            @endif
            <a href="{{url('logout')}}" class="btn btn-secondary btn-sm"><i class="fa fa-sign-out"></i></a>
     </div>
 </div>
