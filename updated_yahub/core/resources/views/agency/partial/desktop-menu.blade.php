<div
    class="side-nav text-center border-right d-sm-flex d-none flex-column justify-content-around position-fixed">
    <div class="my-4">
        <a href="{{route('agency-dashboard')}}" class="btn nav-active"><i class="fas fa-2x fa-home"></i></a>
    </div>
    <div class="my-4">
        <a href="{{route('clients-search')}}" class="btn icon"><i class="fas fa-2x fa-users"></i></a>
    </div>
    <div class="my-4">
        <a href="{{route('agency-search')}}" class="btn icon"><i class="fas fa-2x fa-search"></i></a>
    </div>
    <div class="my-4">
        <a href="{{route('agency-newproject')}}" class="btn icon"><i class="fas fa-2x fa-plus-circle"></i></a>
    </div>
    @if(\Auth::user()->email_module==1)
    <div class="my-4">
        <a href="{{route('agency-mail')}}"
            class="btn icon"><i class="fas fa-2x fa-envelope"></i></a>
    </div>
    @endif
    <div class="my-4">
        <a href="{{route('agency-inbox')}}" class="btn icon"><i class="fas fa-2x fa-comment-alt"></i></a>
    </div>
    <div class="my-4">
        <a href="{{route('agency-profile')}}" class="btn icon"><i class="fas fa-2x fa-user"></i></a>
    </div>
</div>
