@extends('layouts.admin') @section('content') <div class="p-sm-4 p-3 project">
    <h1>Admin</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>Manage</th>
                                <th>ID</th>
                                <th>Profile</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>CRM Module</th>
                                <th>Delete?</th>
                                <th>Projects</th>
                                <th>Approve?</th>
                                <th>Notifications?</th>
                                <th>Theme Settings?</th>
                                <th>Email Module?</th>
                            </tr>
                        </thead>
                        <tbody> @foreach($agencies as $user) <tr>
                                <td>
                                    <a href="{{url('admin/agency/edit/'.$user->id)}}"
                                        class="btn n-project"><i class="fa fa-tasks"></i> Manage</a>
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
                                <td>
                                    @if($user->enable_crm==0) 
                                        <a href="{{url('/crm_module/'.$user->id.'/1')}}" class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Enable</a> 
                                    @else
                                        <a href="{{url('/crm_module/'.$user->id.'/0')}}" class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Disable</a> 
                                    @endif 
                                </td>
                                <td>
                                    <a href="{{url('admin/agency/'.$user->id)}}" class="deleteAgency btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
                                </td>
                                <td> 
                                    <a href="{{url('admin/agency/projects/'.$user->id)}}"
                                        class="btn btn-success btn-sm"><i class="fa fa-link"></i> Projects</a>
                                </td>
                                <td> 
                                    @if($user->is_active==0) 
                                        <a href="{{url('admin/approve/agency/'.$user->id)}}" class="btn btn-success btn-sm"><i class="fa fa-check"></i> Approve</a> 
                                    @endif
                                </td>
                                <td> @if($user->notification_status==0) <a href="{{url('admin/notification/'.$user->id)}}"
                                        class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Turn On</a> @endif @if($user->notification_status==1) <a href="{{url('admin/notification/'.$user->id)}}"
                                        class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Turn Off</a> @endif 
                                </td>
                                <td> 
                                    @if($user->theme_setting==0) 
                                        <a href="{{url('/admin/agencyThemeSetting/'.$user->id.'/1')}}" class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Enable</a> 
                                    @endif @if($user->theme_setting==1) 
                                        <a href="{{url('/admin/agencyThemeSetting/'.$user->id.'/0')}}" class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Disable</a> 
                                    @endif 
                                </td>
                                <td> 
                                    @if($user->email_module==0) 
                                        <a href="{{url('/admin/agency/emailModule/'.$user->id.'/1')}}" class="btn btn-success btn-sm"><i class="fas fa-toggle-off"></i> Enable</a> 
                                    @endif @if($user->email_module==1) 
                                        <a href="{{url('/admin/agency/emailModule/'.$user->id.'/0')}}" class="btn btn-danger btn-sm"><i class="fas fa-toggle-on"></i> Disable</a> 
                                    @endif 
                                </td>
                            </tr> @endforeach </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var deleteStatus = false;
$(".deleteAgency").on("click", function(e) {
    var $href = $(this).attr("href");
    if (deleteStatus == false) {
        e.preventDefault();
        Swal.fire({
            title: "Delete Agency",
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
