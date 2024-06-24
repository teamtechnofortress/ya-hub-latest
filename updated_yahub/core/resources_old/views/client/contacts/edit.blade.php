@extends('layouts.client') 
@section('content') 

    <div class="row mt-4">
        <div class="col-md-12">
            <strong style="font-size: 22px">Edit Contact</strong>
        </div>
        <div class="col-md-12 mt-3">
            <form action="{{route('update_contact')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="id" value="{{$contact->id}}">
                        <div class="form-group mt-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{$contact->name}}" class="form-control" placeholder="Contact Name">
                        </div>
                        <div class="form-group mt-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{$contact->email}}" class="form-control" placeholder="Contact Email">
                        </div>
                        <div class="form-group mt-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" name="phone" id="phone" value="{{$contact->phone}}" class="form-control" placeholder="Contact Phone">
                        </div>
                        <div class="form-group mt-2">
                            <label for="profile" class="form-label">Profile Picture</label>
                            <input type="file" name="profile" id="profile" class="form-control" placeholder="Contact Profile">
                        </div>
                        <div class="form-group mt-2">
                            <label for="company" class="form-label">Company</label>
                            <select name="company_id" id="company" class="form-control">
                                <option value="0" {{$contact->company_id==0 ? 'selected' : ''}}>Select Company</option>
                                @if($companies)
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}" {{$contact->company_id==$company->id ? 'selected' : ''}}>{{$company->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="title" class="form-label">Job Title</label>
                            <input type="text" name="title" id="title" value="{{$contact->job_title}}" class="form-control" placeholder="Job title">
                        </div>
                        <div class="form-group mt-4">
                            <input type="submit" value="Update Contact" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection