@extends('layouts.agency') @section('content') <div class="p-sm-4 p-3 project">
    <h1>Clients</h1>
    <div class="py-4">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Delete?</th>
                            </tr>
                        </thead>
                        <tbody> @foreach($users as $user) <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td><a href="{{url('agency/client/'.$user->id)}}"
                                        class="deleteClient btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a></td>
                            </tr> @endforeach </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
var deleteStatus = false;
$(".deleteClient").on("click", function(e) {
    var $this = $(this);
    if (deleteStatus == false) {
        e.preventDefault();
        Swal.fire({
            title: "Delete Client",
            text: "Are you sure?",
            icon: "warning",
            button: "Yes",
        }).then((value) => {
            if (value.isConfirmed) {
                deleteStatus = true;
                window.location.href = $this.attr("href");
            }
        });
    }
})
</script> @endsection
