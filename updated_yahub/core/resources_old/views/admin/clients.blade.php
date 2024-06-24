@extends('layouts.admin') @section('content') <div class="p-sm-4 p-3 project">
    <h1>Clients</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Agency Linked</th>
                                <th>Usage</th>
                                <th>CRM Module</th>
                                <th>Delete?</th>
                                <th>Notifications?</th>
                                <th>Theme Settings?</th>
                                <th>Email Module?</th>
                            </tr>
                        </thead>
                        <tbody> @foreach($users as $user) <tr>
                                <td>
                                    <a href="{{url('admin/client/edit/'.$user->id)}}"
                                            class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                </td>
                                <td>{{$user->id}}</td>
                                <td style="display: flex;justify-content: center;">
                                    @if($user->profile_picture)
                                        <div style="display: flex;width: 40px;height: 40px;border-radius: 50%;overflow: hidden;">
                                            <img style="width:100%" src="{{$user->profile_picture}}" alt="" srcset="">
                                        </div> 
                                    @else
                                        <span>
                                            <i style="font-size:40px" class="fas fa-3x fa-user-circle"></i>
                                        </span> 
                                    @endif
                                </td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td> @if(!empty($user->projectToMe)) @if(!empty($user->projectToMe->agency)) {{$user->projectToMe->agency->name}} @endif @endif </td>
                                <td>
                                    <a href="{{url('admin/usage/'.$user->id)}}"
                                            class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Usage</a>
                                </td>
                                <td>
                                    @if($user->enable_crm==0) 
                                        <a href="{{url('/crm_module/'.$user->id.'/1')}}" class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Enable</a> 
                                    @else
                                        <a href="{{url('/crm_module/'.$user->id.'/0')}}" class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Disable</a> 
                                    @endif 
                                </td>
                                <td>
                                    <a href="{{url('admin/client/'.$user->id)}}"
                                            class="deleteClient btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                                <td> 
                                    @if($user->notification_status==0) <a href="{{url('admin/notification/'.$user->id)}}"
                                        class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Turn On</a> 
                                    @endif @if($user->notification_status==1) <a href="{{url('admin/notification/'.$user->id)}}"
                                        class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Turn Off</a> 
                                    @endif 
                                </td>
                                <td> 
                                    @if($user->theme_setting==0) 
                                        <a href="{{url('/admin/clients/userThemeSetting/'.$user->id.'/1')}}" class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Enable</a> 
                                    @endif @if($user->theme_setting==1) 
                                        <a href="{{url('/admin/clients/userThemeSetting/'.$user->id.'/0')}}" class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Disable</a> 
                                    @endif 
                                </td>
                                <td> 
                                    @if($user->email_module==0) 
                                        <a href="{{url('/admin/clients/emailModule/'.$user->id.'/1')}}" class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Enable</a> 
                                    @endif @if($user->email_module==1) 
                                        <a href="{{url('/admin/clients/emailModule/'.$user->id.'/0')}}" class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Disable</a> 
                                    @endif 
                                </td>
                            </tr> 
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var deleteStatus = false;
$(".deleteClient").on("click", function(e) {
    var $href = $(this).attr("href");
    if (deleteStatus == false) {
        e.preventDefault();
        Swal.fire({
            title: "Delete Client",
            text: "Are you sure?",
            icon: "warning",
            confirmButtonText: "Yes, Delete",
        }).then((value) => {
            if (value.isConfirmed) {
                deleteStatus = true;
                window.location.href = $href;
            }
        });
    }
})
</script> @endsection
