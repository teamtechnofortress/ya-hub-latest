@extends('layouts.client') 
@section('content') 

    <div class="row mt-4">
        <div class="col-md-12">
            <strong style="font-size: 22px">Edit Company</strong>
        </div>
        <div class="col-md-12 mt-3">
            <form action="{{route('update_company')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" name="id" value="{{$company->id}}">
                        <div class="form-group mt-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" value="{{$company->name}}" class="form-control" placeholder="Company Name">
                        </div>
                        <div class="form-group mt-2">
                            <label for="address" class="form-label">Address</label>
                            <textarea name="address" id="address" class="form-control" placeholder="Address">{!! $company->address !!}</textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="note" class="form-label">Notes</label>
                            <textarea name="note" id="note" class="form-control" placeholder="Notes">{!! $company->note !!}</textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="profile" class="form-label">Profile Picture</label>
                            <input type="file" name="profile" id="profile" class="form-control" placeholder="Company Profile">
                        </div>
                        <div class="form-group mt-4">
                            <input type="submit" value="Update Company" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection