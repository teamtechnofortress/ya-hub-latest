<div class="header border-bottom">
    <style>
        .nav-item{
            padding: 2px 5px;
        }
        .nav-item > a{
            width: 100%;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: transparent !important">
        <a class="navbar-brand" href="#">
            @if($logo)
                <img src="{{$logo}}" class="img-fluid" alt="logo" />
            @else
                <img src="{{asset('frontend/Pics/logo.png')}}" class="img-fluid" alt="logo" />
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            
          </ul>
          <ul class="navbar-nav">
            <li class="nav-item">
                <a href="{{route('client-dashboard')}}" class="btn n-project btn-sm"><i class="far fa-arrow-alt-circle-left"></i> Projects</a>
            </li>
            @if(Auth::user()->enable_invoiceTool==1)
            <li class="nav-item">
                <a href="{{route('client_invoices')}}" class="btn btn-light btn-sm">Invoices</a>
            </li>
            <li class="nav-item">
                <a href="{{route('client_estimates')}}" class="btn btn-light btn-sm">Estimates</a>
            </li>
            @endif
            @php 
                $enable_crm = \DB::table('users')->where('id',\Auth::user()->id)->first()->enable_crm;
            @endphp
            @if($enable_crm==1)
                <li class="nav-item">
                    <a href="{{url('companies')}}" class="btn btn-light btn-sm">Companies</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('contacts')}}" class="btn btn-light btn-sm">Contacts</a>
                </li>
                <li class="nav-item">
                    <a href="{{url('tasks')}}" class="btn btn-light btn-sm">Tasks</a>
                </li>
            @endif
            @if(Auth::user()->accountingTool==1)
            <li class="nav-item dropdown">
                <a href="{{url('accounting')}}" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Accounting
                </a>
             <div class="dropdown-menu" aria-lebelledby="navbarDropdown">
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('balance') }}">Balance</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{url('bookkeeping')}}">Bookkeeping</a>
                 <a class="dropdown dropdown-item btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Budget</a>
                     <div class="dropdown-menu dropdown-submenu">
                         <a class="dropdown dropdown-item btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="#">Reports</a>
                         <div class="dropdown-menu dropdown-submenu">
                             
                             <a class="dropdown-item btn btn-light btn-sm" href="{{ url('p_and_L') }}">P&L</a>
                             <a class="dropdown-item btn btn-light btn-sm" href="{{ url('vat') }}">VAT</a>
                         </div> 
                         <a class="dropdown-item btn btn-light btn-sm" href="{{ url('margin') }}">Margins</a>
                         <a class="dropdown-item btn btn-light btn-sm" href="{{ url('work_force') }}">Workforce</a>
                     </div>    
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('events') }}">Events</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('expenses') }}">Expenses</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('filling') }}">Filing</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('payments') }}">Payments</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('reconciliation') }}">Reconciliation</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('sales') }}">Sales</a>
                 <a class="dropdown-item btn btn-light btn-sm" href="{{ url('trips') }}">Trips</a>
             </div>
             </li>
             @endif
            
            <li class="nav-item">
                <a href="{{url('logout')}}" class="btn btn-secondary btn-sm"><i class="fa fa-sign-out"></i></a>
            </li>
          </ul>
        </div>
    </nav>
 </div>
<div>
<script>
          $(document).ready(function(){
                $(".dropdown-toggle").click(function(){
                    $(this).next(".dropdown-menu").toggle();
                });
            });

</script>
