@extends('layouts.agency') 
@section('content') 

    <div class="row mt-4">
        <div class="col-md-12">
            <strong style="font-size: 22px">Add Company</strong>
        </div>
        <div class="col-md-12 mt-3">
            <form action="{{route('store_company')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-2">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Company Name">
                        </div>
                        <div class="form-group mt-2">
                            <label for="profile" class="form-label">Profile Picture</label>
                            <input type="file" name="profile" id="profile" class="form-control" placeholder="Company Profile">
                        </div>
                        <div class="form-group mt-4">
                            <input type="submit" value="Add Company" class="btn btn-primary btn-block">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection