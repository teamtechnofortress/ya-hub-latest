@extends('layouts.admin') @section('content') <div class="p-sm-4 p-3 project">
    <h1>Edit Client</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <form method="post"
                    action="{{url('admin/client/update/'.$client->id)}}" enctype="multipart/form-data"> @csrf @method('put') <div class="form-group col-md-12">
                        <label>Name</label>
                        <input type="text"
                            name="name"
                            class="form-control"
                            value="{{$client->name}}"
                            required />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Profile Picture</label>
                        <input type="file"
                            accept="image/*"
                            name="profile_picture"
                            class="form-control"
                             />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Upload Limit in MBs</label>
                        <input type="number"
                            min="1"
                            name="upload_limit_in_mbs"
                            class="form-control"
                            value="{{$client->upload_limit_in_mbs}}"
                            required />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Max Conversations in Inbox</label>
                        <input type="number"
                            min="1"
                            name="max_conversations_in_inbox"
                            class="form-control"
                            value="{{$client->max_conversations_in_inbox}}"
                            required />
                    </div>
                    <div class="form-group col-md-12">
                        <label>Max Projects</label>
                        <input type="number"
                            min="1"
                            name="max_projects_per_client"
                            class="form-control"
                            value="{{$client->max_projects_per_client}}"
                            required />
                    </div>
                    <div class="col-md-12 my-2">
                        <input type="submit"
                            name="submit"
                            value="Update Client"
                            class="btn n-project" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> @endsection
