@extends('layouts.agency') @section('content') <div class="p-sm-4 p-3 project">
    <h1>New Project</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <form method="post" action="{{url('projects')}}"> @csrf @method('post') <div class="row">
                        <div class="form-group col-md-12">
                            <label>Project Title</label>
                            <input type="text" name="project_title" class="form-control" required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Client Name</label>
                            <input type="text" name="client_name" class="form-control" required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Project Budget</label>
                            <input type="text" name="project_budget" class="form-control" required />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Project Description</label>
                            <textarea type="text" name="project_description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Internal Notes</label>
                            <textarea type="text" name="internal_notes" class="form-control" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Assign to</label>
                            <select name="user_id" class="form-control">
                                <option value="0">--SELECT CLIENT--</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 my-2">
                            <input type="submit" name="submit" value="Add New Project" class="btn n-project" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> @endsection
