<div
    class="border-top d-flex d-sm-none justify-content-around bg-white w-100 mobile-nav bg-white">
    <div>
        <a href="{{route('client-dashboard')}}" class="btn nav-active"><i class="fas fa-home"></i></a>
    </div>
    <div>
        <a href="{{route('client-search')}}" class="btn icon"><i class="fas fa-search"></i></a>
    </div>
    @if(\Auth::user()->email_module==1)
    <div>
        <a href="{{route('client-mail')}}"
            class="btn icon"><i class="fas fa-envelope"></i></a>
    </div>
    @endif
    <div>
        <a href="{{route('client-inbox')}}" class="btn icon"><i class="fas fa-comment-alt"></i></a>
    </div>
    <div>
        <a href="{{route('client-profile')}}" class="btn icon"><i class="fas fa-user"></i></a>
    </div>
</div>
