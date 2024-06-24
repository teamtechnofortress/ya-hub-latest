@extends('layouts.agency') 
@section('content') 

<style>
    .modal-dialog-centered{
        max-width: 100% !important;
        justify-content: center
    }
    .modal-content{
        max-width: 500px;
    }
    .modal-header .close {
        padding: 0
    }
    .select2-selection__choice{
        margin-right: 5px !important;
        margin-top: 5px !important;
        margin-left: 5px !important;
    }
    .swal2-warning{
            margin-left: auto !important;
            margin-right: auto !important;
        }
    .swal2-confirm{
        margin-right: 20px !important;
    }
    .swal2-html-container{
        margin-bottom: 20px !important;
    }
    .swal2-title{
        margin-top: 10px !important;
    }
    .paginate_button{
        padding: 0px !important;
    }
    /* div.dt-buttons{
        position: absolute!important;
        top: -51px !important;
        right: 32% !important;


    }
    .dataTables_wrapper .dt-buttons .buttons-pdf    {
        border-radius: 3px; 
        padding: 3px 12px;
        margin-right: 10px;
        font-size: 12px !important;
        border-color: #007BFF !important;   
        color: #fff !important;  
        background-color: #007BFF !important; 
        background-image: -webkit-linear-gradient(top, #007BFF 0%, #007BFF 100%) !important;
    }
    .dataTables_wrapper .dt-buttons .buttons-pdf:hover{
        background-color: #007BFF; 
        color: #fff; 
    } */
    /* .dataTables_paginate{
        display: none !important;
    } */
    .btn-filter
    {
        right: 51px !important;
        position: relative !important;
    }
    .table-responsive{
        overflow-x: hidden !important;
    }
    .dataTables_filter input{
        border: 1px solid #ced4da;
    border-radius: 0.25rem !important;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.25rem !important;
    }
    .dataTables_wrapper{
        position: static !important;
    }
    .modal-header.dt-button, div.dt-button, a.dt-button {
        background-image: url("") !important;
    }
    
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        float: none;
        display: inline-block;
        margin-right: 10px;
    }

    .dataTables_wrapper div.dataTables_filter {
        float: inline-end;
    }
    .dt-buttons{
        display:none;
    }
</style>
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
                <a href="#0" class="btn btn-primary btn-sm btn-pdf d-none"onclick="$('.table').DataTable().buttons('.buttons-pdf').trigger()">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm d-none" onclick="$('.table').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a  href="{{url('defaultUi')}}"  class="btn btn-primary btn-sm">Defalut UI</a>
                <a  href="#0" data-toggle="modal" data-target="#addTaskModal" class="btn btn-primary btn-sm add">Add Departments +</a>
            </div>
        </div>
        <div class="col-md-12 task-form" style="display:none">
        <form action="{{url('add_departments')}}" method="post" id="form-task" data-action="" enctype="multipart/form-data">
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
        <div class="row mt-4">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered" id="department_table" style="position: static;">
                        <thead>
                            <tr>
                            	@php

                            	
                            	@endphp
                                <th>ID</th>
                                <th>Department Name</th>
                                <th>Department Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($data as $department)
                            <tr>
                                <td><a href="{{url('inside_departments/'.$department->id)}}">{{$department->department_id}}</a></td>
                                <td><a href="{{url('inside_departments/'.$department->id)}}">{{$department->department_name}}</a></td>
                                <td><img src="{{$department->department_logo}}" alt="abcd" width=40px height=auto></td>
                                <td>
                                <a href="{{ url('updatedepartments/' . $department->id) }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a  href="{{url('delete_departments/'.$department->id)}}"
                                class="btn bg-danger deleteDept btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr> 
                            @endforeach
                           
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-sm-12" style="position: relative">
                <div class="total_info"></div>
            </div>
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

<script>
     var deleteStatus = false;
    var _token = $("input[name='_token']").val()
    $(".deleteDept").on("click", function(e) {
        var $this = $(this);
        if (deleteStatus == false) {
            e.preventDefault();
            Swal.fire({
                title: "Delete Department",
                text: "Are you sure?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: 'No, cancel!'
            }).then((value) => {
                if (value.isConfirmed) {
                    deleteStatus = true;
                    window.location.href = $this.attr("href");
                }
            });
        }
    })
    
$('.add,.cancel').click(function(){
        $('.table').toggle('slow')
        $('.task-form').toggle('slow')
        clear_form()
    })
$(document).ready(function() {
   // $.fn.dataTable.moment('YYYY-MM-DD');
    $('#department_table').DataTable( {
        
        dom: 'lBfrtip',
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [
            {
                extend: 'pdf',
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                        doc.content[1].table.widths = 
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        },
                    text: 'Export PDF',
                    exportOptions: {
                        columns: [0, 1, 2]  // Specify the columns you want to include in the PDF export (0-indexed)
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    exportOptions: {
                        columns: [0, 1, 2]  // Specify the columns you want to include in the PDF export (0-indexed)
                    }
                },
            'copyHtml5',
            'excelHtml5',
        ],
        order: [[2, 'asc']], // Sort by the third column (index 2), which is the Date column
            columnDefs: [
                { targets: [0, 1], orderable: true }, // Disable sorting for all columns except the Date column
            ]

    } );
} );

</script>
<div class="tb-hidden" style="display: none">
    <table class="table-hidden-estimate table-stripped table-bordered" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Estimate No.</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>Company</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody> 
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> 
          
        </tbody>
    </table>
</div>

@endsection