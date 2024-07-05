@extends('layouts.departments') 
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
                <img src="{{isset($dept) ? $dept->department_logo : ''}}" alt="logo" width=120px height=auto>
            </div>
            <div style="display: flex; justify-content: center; align-items: center;">
                <h1>{{ isset($dept) ? $dept->department_name : '' }}</h1>
                {{-- <div class="" style="margin-left: 8rem!important">
                    <button>Add Main Template</button>
                    <button>Add Notes Template</button>
                </div> --}}
                
            </div>                
        </div>       
    </div>
</div>
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
                {{-- <a  href="{{url()}}"  class="btn btn-primary btn-sm">View Note Template</a> --}}
                {{-- <a  href="#0"  class="btn btn-primary btn-sm addnotetemp">Add Note Template</a> --}}
                {{-- <a  href="#0" data-toggle="modal" data-target="#addTaskModal" class="btn btn-primary btn-sm addmaintemp">Add Main Template</a> --}}
            </div>
        </div>
        <div class="col-md-12 note-form" style="display:none">
            <form action="{{url('create_note_temp')}}" method="post" id="form-task" data-action="" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="end_date">Note Name</label>
                            <input type="text" class="form-control task_date" name="note_name" id="dept_name" placeholder="Note Name" required>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="exampleFormControlTextarea1">Note</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="note" placeholder="Note" rows="3"></textarea>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Payment Type</label>
                            <input type="text" name="payment_typ" id="payment_typ" class="form-control contact_task_id" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Payment Type</label>
                            <input type="date" name="due_date" id="payment_typ" class="form-control contact_task_id" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Notes</label>
                            <select class="custom-select" name="notesid">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                        </div>
                    </div>      --}}
                    <div class="col-md-6">
                        <div class="form-group mt-5 pt-5">
                            <a href="#0" class="btn btn-light cancelnote">Cancel</a>
                            <input type="submit" class="btn btn-secondary" value="Submit">
                        </div>
                    </div>
                </div>
            </form>
         </div>
        <div class="col-md-12 task-form" style="display:none">
            <form action="{{url('create_main_temp')}}" method="post" id="form-task" data-action="" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="end_date">Template Name</label>
                            <input type="text" class="form-control task_date" name="temp_name" id="dept_name" placeholder="Template Name" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="dept_logo">Refrence Number</label>
                            <input type="text" name="ref_number" id="ref_number" class="form-control" placeholder="Estimate No. D/202211011" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Payment Type</label>
                            <input type="text" name="payment_typ" id="payment_typ" class="form-control contact_task_id" placeholder="pay_typ" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Due Date</label>
                            <input type="date" name="due_date" id="payment_typ" class="form-control contact_task_id" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label for="contact">Notes</label>
                            <select class="custom-select" name="notesid">
                                <option value="">Open this select menu</option>
                                @foreach($notetemp as $item)
                                <option value="{{ $item->id }}">{{ $item->notename }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>     
                    <div class="col-md-6">
                        <div class="form-group mt-5">
                            <a href="#0" class="btn btn-light cancelmain">Cancel</a>
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
                                <th>Note Name</th>
                                <th>Note</th>
                                <th>Note For</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($notetemp as $item)
                                <tr>
                                    {{-- <td><a href="{{url('inside_departments/'.$department->id)}}">{{$department->department_id}}</a></td> --}}
                                    <td>{{$item->notename}}</td>
                                    <td>{{$item->note}}</td>
                                    <td>{{$item->notefor}}</td>
                                    {{-- <td>{{$item->dueDate}}</td> --}}
                                    {{-- <td><a href="">sasa</a></td> --}}
                                    {{-- <td><img src="" alt="abcd" width=40px height=auto></td> --}}
                                    <td>
                                    <a href="{{ url('updatenotetemp/' . $item->id) }}" class="btn btn-light btn-sm">
                                            <i class="fas fa-edit"></i>
                                    </a>
                                        {{-- <a  href="{{ url('deletenotetemp/' . $item->id) }}"
                                    class="btn bg-danger deleteDept btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a> --}}
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
    $('.addmaintemp').click(function(){
        $('.table').toggle('slow')
        $('.task-form').toggle('slow')
        $('.note-form').hide('slow')
        clear_form()
    })
    $('.cancelmain').click(function(){
        $('.table').toggle('slow')
        $('.task-form').toggle('slow')
        $('.note-form').hide('slow')
        clear_form()
    })
    $('.addnotetemp').click(function(){
        $('.table').toggle('slow')
        $('.note-form').toggle('slow')
        $('.task-form').hide('slow')
        clear_form()
    })
    $('.cancelnote').click(function(){
        $('.table').toggle('slow') 
        $('.note-form').toggle('slow')
        $('.task-form').hide('slow')
        clear_form()
    })
   
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