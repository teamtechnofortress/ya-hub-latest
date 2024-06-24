@extends('layouts.app') @section('content') <div class="container">
    <div class="row align-items-center form-pos">
        <div class="col-12 col-md-6">
            <div class="text-center py-5">
                <img src="{{asset('frontend/Pics/logo.png')}}"
                    height="75%"
                    width="75%"
                    class="img-fluid"
                    alt="logo" />
            </div>
        </div>
        <div class="col-12 col-md-6 px-0">
            <div class="form-box p-4">
                <div class="d-flex pt-3 px-md-3">
                    <h3 class="text-center text-white w-100">{{ __('Reset Password') }}</h3>
                </div>
                <div class="content">
                    <div class="ya-form text-white px-md-3 pb-3 content-1 active"> @if (\Session::has('success')) <div class="alert alert-success"> {!! \Session::get('success') !!} </div>@endif <form method="POST"
                            action="{{ route('password.update') }}"> @csrf <input type="hidden"
                                name="token"
                                value="{{ $token }}">
                            <div class="form-group">
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
                            <div class="form-group">
                                <label class="text-uppercase">Confirm Password</label>
                                <input type="password"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                    class="form-control border-0"
                                    placeholder="Confirm Password  " /> @error('password') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <button type="submit"
                                class="btn bg-white w-100 mt-3 s-btn text-uppercase"> {{ __('Reset Password') }} </button>
                            <a href="{{url('/')}}"
                                class="btn btn-sm bg-white w-100 mt-3 s-btn text-uppercase"> Sign In/Sign Up </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> @endsection
