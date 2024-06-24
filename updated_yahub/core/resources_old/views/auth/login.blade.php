@extends('layouts.app') @section('content') <div class="container">
    <div class="row align-items-center form-pos">
        <div class="col-12 col-md-6 text-center py-5">
            <a href="{{env('MAIN_APP_URL')}}"
                class="">
                <img src="{{asset('frontend/Pics/logo.png')}}"
                    height="75%"
                    width="75%"
                    class="img-fluid"
                    alt="logo" />
            </a>
        </div>
        <div class="col-12 col-md-6 px-0">
            <div class="form-box p-4">
                <div class="d-flex pt-3 px-md-3">
                    <h3 class="text-center text-white w-100">Sign In</h3>
                </div>
                <div class="content">
                    <div class="ya-form text-white px-md-3 pb-3 content-1 active">
                        <div class="text-center my-4 py-2">
                            <h2>Login to your Ya-Hub account</h2>
                        </div> @if (\Session::has('success')) <div class="alert alert-success"> {!! \Session::get('success') !!} </div> @endif @if (\Session::has('status')) <div class="alert alert-success"> {!! \Session::get('status') !!} </div>@endif <form method="POST"
                            action="{{ route('login') }}"> @csrf <div class="form-group">
                                <label class="text-uppercase">email</label>
                                <input type="email"
                                    class="form-control border-0"
                                    name="email"
                                    placeholder="Enter your email" /> @error('email') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="text-uppercase">Password</label>
                                <input type="password"
                                    name="password"
                                    class="form-control border-0"
                                    placeholder="Enter your password  " /> @error('password') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <div class="d-flex"
                                style="justify-content: space-between;">
                                <div class="text-left">
                                    <a href="{{env('MAIN_APP_URL')}}"
                                        class="btn text-white">
                                        << Main
                                            Website</a>
                                </div>
                                <div class="text-right">
                                    <a href="{{ route('password.request') }}"
                                        class="btn text-white">Forgot Password?</a>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn bg-white w-100 mt-3 s-btn text-uppercase"> Login </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> @endsection
