@extends('layouts.admin') @section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
    rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="p-sm-4 p-3 project">
    <h1>Edit Lite Agency</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form method="post"
                    action="{{url('admin/lite-agency/'.$agency->id)}}" enctype="multipart/form-data"> @csrf @method('put') <div class="form-group col-md-12">
                        <label>Lite-Agency Name</label>
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
                    <div class="form-group col-md-12">
                        <label>Assigned Clients</label>
                        <select name="assigned_clients[]"
                            class="form-control select2"
                            multiple="multiple"> @foreach($clients as $client) <option value="{{$client->id}}"
                                @if(in_array($client->id,$assigned_clients)) selected @endif>{{$client->name}}</option> @endforeach </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Upload Limit in MBs</label>
                        <input type="number"
                            min="1"
                            name="upload_limit_in_mbs"
                            class="form-control"
                            value="{{$agency->upload_limit_in_mbs}}"
                            required />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Max Conversations in Inbox</label>
                        <input type="number"
                            min="1"
                            name="max_conversations_in_inbox"
                            class="form-control"
                            value="{{$agency->max_conversations_in_inbox}}"
                            required />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Max Projects</label>
                        <input type="number"
                            min="1"
                            name="max_projects_per_client"
                            class="form-control"
                            value="{{$agency->max_projects_per_client}}"
                            required />
                    </div>
                    <div class="col-md-12 my-2">
                        <input type="submit"
                            name="submit"
                            value="Update Lite Agency"
                            class="btn n-project" />
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script> @endsection
