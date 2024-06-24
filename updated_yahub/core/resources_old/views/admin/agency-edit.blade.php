@extends('layouts.admin') @section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="p-sm-4 p-3 project">
    <h1>Edit Agency</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form method="post"
                    action="{{url('admin/agency/'.$agency->id)}}" enctype="multipart/form-data"> @csrf @method('put') <div class="form-group col-md-12">
                        <label>Agency Name</label>
                        <input type="text"
                            name="name"
                            class="form-control"
                            value="{{$agency->name}}"
                            required />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Profile Picture</label>
                        <input type="file"
                            accept="image/*"
                            name="profile_picture"
                            class="form-control"
                            required />
                    </div>
                    <div class="col-md-12 my-2">
                        <input type="submit"
                            name="submit"
                            value="Update Agency"
                            class="btn n-project" />
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div> @endsection
