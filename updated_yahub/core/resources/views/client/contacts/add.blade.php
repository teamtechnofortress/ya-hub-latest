@extends('layouts.client') 
@section('content') 

    <div class="row mt-4">
        <div class="col-md-12">
            <strong style="font-size: 22px">Add Contact</strong>
        </div>
        <div class="col-md-12 mt-3">
            <form action="{{route('store_contact')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Contact Name">
                        </div>
                        <div class="form-group mt-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Contact Email">
                        </div>
                        <div class="form-group mt-2">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Contact Phone">
                        </div>
                        <div class="form-group mt-2">
                            <label for="phone2" class="form-label">Second Phone (optional)</label>
                            <input type="tel" name="phone2" id="phone2" class="form-control" placeholder="Second Phone">
                        </div>
                        <div class="form-group mt-2">
                            <label for="social" class="form-label">Social Link</label>
                            <input type="tel" name="social" id="social" class="form-control" placeholder="Social profile link">
                        </div>
                        <div class="form-group mt-2">
                            <label for="profile" class="form-label">Profile Picture</label>
                            <input type="file" name="profile" id="profile" class="form-control" placeholder="Contact Profile">
                        </div>
                        <div class="form-group mt-2">
                            <label for="company" class="form-label">Company</label>
                            <select name="company_id" id="company" class="form-control">
                                <option value="0">Select Company</option>
                                @if($companies)
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="title" class="form-label">Job Title</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Job title">
                        </div>
                        <div class="form-group mt-2">
                            <label for="note" class="form-label">Notes</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Notes"></textarea>
                        </div>
                        <div class="form-group mt-4">
                            <input type="submit" value="Add Contact" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection