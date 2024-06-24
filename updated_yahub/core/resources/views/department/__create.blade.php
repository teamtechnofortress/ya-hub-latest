@extends('layouts.agency') 
@section('content') 


<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Departments</h1>
            </div>
            <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                <!-- <a href="#0" class="btn btn-light btn-sm btn-filter" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a> -->
                <a href="#0" class="btn btn-primary btn-sm btn-pdf"onclick="$('.table').DataTable().buttons('.buttons-pdf').trigger()">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a  href="#0" data-toggle="modal" data-target="#addTaskModal" class="btn btn-primary btn-sm add">Add Departments +</a>
            </div>
        </div>
        <div class="col-md-12 task-form">
        <form action="{{url('add_departments')}}" method="post" id="form-task" data-action="">
            @csrf
            <div class="row">
            <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Department ID</label>
                        <input type="text" class="form-control task_date" name="dept_id" id="dept_id" placeholder="ID" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contact">Department Name</label>
                        <input type="text" name="dept_name" id="dept_name" class="form-control contact_task_id" required />
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="dept_logo">Department Logo</label>
                        <input type="file" name="dept_logo" id="dept_logo" class="form-control" required />
                    </div>
                </div>


                
                <div class="col-md-6">
                    <div class="form-group mt-4 text-right">
                        <a href="#0" class="btn btn-light cancel">Cancel</a>
                        <input type="submit" class="btn btn-secondary" value="Submit">
                    </div>
                </div>
            </div>
        </form>
    </div>
       
    </div>
</div>
<script>
   
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>




@endsection