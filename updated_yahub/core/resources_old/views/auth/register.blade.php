@extends('layouts.app') @section('content') 
<div class="container">
    <div class="row align-items-center form-pos">
        <div class="col-12 col-md-6 text-center py-5">
            <a href="https://ya-hub.com/">
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
                    <button class="opt-btn btn w-100">Sign Up</button>
                </div>
                <div class="content">
                    <div class="ya-form text-white px-3 pb-3 content-2 active">
                        <div class="text-center my-4 py-2">
                            <h2>Register Your account</h2>
                        </div> @if (\Session::has('success')) <div class="alert alert-success"> {!! \Session::get('success') !!} </div> @endif @if ($errors->any()) <div class="alert alert-danger"> {!! $errors->first() !!} </div> @endif <form method="POST"
                            action="{{ route('register') }}"> @csrf <div class="form-group">
                                <label class="text-uppercase">FIRSTNAME - SURNAME</label>
                                <input type="text"
                                    name="name"
                                    class="form-control border-0"
                                    placeholder="Enter your Full Name" /> @error('name') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <!-- <div class="agency-name-area form-group">
                                <label class="text-uppercase">Agency Contact</label>
                                <input type="text"
                                    name="agency_contact"
                                    class="form-control border-0"
                                    placeholder="Enter Agency Contact" /> @error('name') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div> -->
                            <div class="form-group">
                                <label class="text-uppercase">username</label>
                                <input type="text"
                                    name="username"
                                    class="form-control border-0"
                                    placeholder="Enter your username" /> @error('username') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="text-uppercase">email</label>
                                <input type="email"
                                    name="email"
                                    class="form-control border-0"
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
                                    placeholder="Enter your password" /> @error('password') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="text-uppercase">Account Type</label>
                                <select class="form-control role-toggle border-0"
                                    name="role">
                                    <option value="3">Client</option>
                                    <option value="4">Agency</option>
                                    <option value="2">Admin</option>
                                </select> @error('role') <div class="alert alert-danger"
                                    role="alert">
                                    <strong>{{ $message }}</strong>
                                </div> @enderror
                            </div>
                            <div class="form-group code-area-agency">
                                <label class="text-uppercase">Enter Code</label>
                                <input type="text"
                                    name="code"
                                    class="form-control border-0"
                                    placeholder="Enter your code" />
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox"
                                    class="form-check-input terms-agree-btn" />
                                <label class="ml-2">I agree to terms and conditions</label>
                            </div>
                            <div class="d-flex"
                                style="justify-content: space-between;">
                                <div class="text-left">
                                    <a target="_blank"
                                        href="{{env('MAIN_APP_URL').'/terms'}}"
                                        class="btn text-white">
                                        << Terms
                                            and
                                            Conditions</a>
                                </div>
                            </div>
                            <button type="submit"
                                class="btn bg-white w-100 s-btn text-uppercase register-submit"
                                disabled> register </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> @endsection
