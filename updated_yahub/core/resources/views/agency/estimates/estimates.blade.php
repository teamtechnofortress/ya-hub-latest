@extends('layouts.agency') 
@section('content') 
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
</style>
<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Estimates</h1>
            </div>
            <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                <a href="#0" class="btn btn-primary btn-sm btn-pdf">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a href="{{route('addEstimate',['lang' => 'en'])}}" class="btn btn-primary btn-sm">New English Estimate</a>
                <a href="{{route('addEstimate',['lang' => 'fr'])}}" class="btn btn-primary btn-sm">New French Estimate</a>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered " style="position: static;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estimate No.</th>
                                <th>Project</th>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Company</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($estimates as $estimate) 
                            @php 
                                if($estimate->currency=='usd'){$currency = '$';}
                                if($estimate->currency=='eur'){$currency = '€';}
                                if($estimate->currency=='gbp'){$currency = '£';}
                            @endphp
                            <tr>
                                <td>{{$estimate->id}}</td>
                                <td>{{$estimate->invoice_no}}</td>
                                <td>
                                    <form action="{{route('changeProject',$estimate->id)}}" method="POST" class="projectForm">
                                        @csrf
                                        <select name="project" class="form-control selectP" style="max-width: 160px">
                                            <option value="">Select Project</option>
                                            @if(count(Auth::user()->projects) > 0)
                                                @foreach(Auth::user()->projects as $pr)
                                                    <option value="{{$pr->id}}" {{$estimate->project_id==$pr->id ? 'selected' : ''}}>{{$pr->project_title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </form>
                                    <script>$('.selectP').change(function(){$(this).parent('.projectForm').submit()})</script>
                                </td>
                                <td>
                                    <form action="{{route('changeCurrency',$estimate->id)}}" method="POST" class="currencyForm">
                                        @csrf
                                        <select name="currency" class="form-control selectC">
                                            <option value="usd" {{$estimate->currency=='usd' ? 'selected' : ''}}>USD</option>
                                            <option value="eur" {{$estimate->currency=='eur' ? 'selected' : ''}}>EUR</option>
                                            <option value="gbp" {{$estimate->currency=='gbp' ? 'selected' : ''}}>GBP</option>
                                        </select>
                                    </form>
                                    <script>$('.selectC').change(function(){$(this).parent('.currencyForm').submit()})</script>
                                </td>
                                <td>{{$currency}} {{$estimate->total}}</td>
                                {{-- <td> --}}
                                    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal{{$estimate->id}}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="{{url('assignEstimates/'.$estimate->id)}}" method="post">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Assign to Clients</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php 
                                                            $clients = \DB::table('assign_estimate')->where('estimate_id',$estimate->id)->get();
                                                            $allClients = \DB::table('users')->where('role',3)->get();
                                                            $client = [];
                                                            foreach ($clients as $key => $value) {
                                                                array_push($client,$value->client_id);
                                                            }
                                                        @endphp
                                                        <label for="selectM{{$estimate->id}}" style="margin-bottom:10px !important"> Select Clients</label>
                                                        <select class="form-control multipleS" id="selectM{{$estimate->id}}" name="users[]" multiple="multiple" style="width: 100%;padding: 5px">
                                                            @foreach($allClients as $key=>$val)
                                                                <option value="{{$val->id}}" {{in_array($val->id, $client) ? 'selected' : ''}}>{{$val->username}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary btn-sm mr-2" type="submit" id="customsubmit">Save</button>
                                                        <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</a>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {$('.multipleS').select2()})
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{$estimate->id}}">Assign to Clients</button>
                                </td> --}}
                                <td>
                                    @php
                                        if($estimate->company_id){
                                            $company = \DB::table('users')->where('id',$estimate->company_id)->first();
                                        }
                                    @endphp
                                    {{$estimate->company_id && $company ? $company->name : $estimate->company_name}} 
                                    <a href="#0" data-toggle="modal" data-target="#exampleModal-comp-{{$estimate->id}}"><i class="fa fa-edit"></i></a>
                                    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal-comp-{{$estimate->id}}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="{{url('updateCompany/'.$estimate->id)}}" method="post">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Company</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php $user_c = \DB::table('users')->where('role',3)->get();@endphp
                                                        <div class="form-group mb-2">
                                                            <select name="company_id" id="" class="form-control">
                                                                <option value="">Select Existing</option>
                                                                @foreach($user_c as $key=>$u_c)
                                                                    <option value="{{$u_c->id}}" {{$estimate->company_id==$u_c->id ? 'selected' : ''}}>{{$u_c->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" name="company_name" id="" value="{{$estimate->company_id ? $company->name : $estimate->company_name}}" class="form-control" placeholder="Company name">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary btn-sm mr-2" type="submit">Submit</button>
                                                        <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{$estimate->created_at}}</td>
                                <td>
                                    @if($estimate->is_saved==1)
                                    @php 
                                        $check_assign = \DB::table('assign_estimate')->where('estimate_id',$estimate->id)->first();
                                        $check_approved = \DB::table('accepted_invoices')->where('data_id',$estimate->id)->first();
                                    @endphp
                                    <style>.dropdown-menu > a{width: 100%;}.dropdown-menu > button{width: 100%;}.dropdown-menu{border: 0;box-shadow: 0px 0px 10px 0px #00000030;margin-top: 10px !important;}</style>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Action
                                        </button>
                                        <div class="dropdown-menu" style="padding: 10px" aria-labelledby="actionDropdown">
                                            @if($check_assign)
                                                <a href="{{url('/changeAssignStatus/'.$estimate->id.'/0')}}" class="btn btn-light btn-sm" style="background-color: #6f42c1;color: white" style="font-weight: bold"><i class="fa fa-users"></i></a>
                                                @if($check_approved)
                                                    <a href="{{url('/changeAcceptStatus/'.$estimate->id.'/0')}}" class="btn btn-warning btn-sm" style="font-weight: bold"><i class="fa fa-check"></i></a>
                                                @else
                                                    <a href="{{url('/changeAcceptStatus/'.$estimate->id.'/1')}}" class="btn btn-light btn-sm" style="font-weight: bold"><i class="fa fa-check"></i></a>
                                                @endif
                                            @else 
                                                <a href="#0" class="btn btn-light btn-sm" data-toggle="modal" data-target="#exampleModal{{$estimate->id}}" style="font-weight: bold"><i class="fa fa-users"></i></a>
                                            @endif
                                            <a href="{{$estimate->template_id==2 ? url('agency/viewEstimate/'.$estimate->id) : url('agency/viewEstimate/'.$estimate->id.'?lang=fr')}}" class="btn btn-light btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{url('agency/copyEstimate/'.$estimate->id)}}" class="btn btn-light btn-sm">
                                                <i class="fa fa-copy"></i>
                                            </a>
                                            @endif
                                            <a href="{{$estimate->template_id==2 ? url('agency/editEstimate/'.$estimate->id) : url('agency/editEstimate/'.$estimate->id.'?lang=fr')}}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a data-action="{{url('agency/deleteEstimate/'.$estimate->id)}}" class="btn btn-danger btn-sm action-link">
                                                <i class="fa fa-trash"></i>
                                            </a>

                                            <a href="{{url('convertToEstimate/'.$estimate->id)}}" class="btn btn-primary btn-sm">
                                                {{-- <i class="fa fa-arrow-right"></i> --}}
                                                I
                                            </a>
                                            </a>
                                            <a href="{{url('convertToPos/'.$estimate->id)}}" class="btn btn-primary btn-sm">
                                                {{-- <i class="fa fa-arrow-right"></i> --}}
                                                P
                                            </a>
                                        </div>
                                    </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    let actionLinks = document.querySelectorAll('.action-link');
    actionLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            let actionURL = this.getAttribute('data-action');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = actionURL;
                }
            });
        });
    });
});
function toDataURL(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function() {
        var reader = new FileReader();
        reader.onloadend = function() {
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}
$(document).ready(function() {
    toDataURL('https://ya-hub.com/app/frontend/Pics/logo.png', function(dataUrl) {
        var globalTable;
        globalTable = $('.table').DataTable({
            destroy: true,
            dom: '<"clear">lBfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)',
                        format: {
                            body: function (data, rowIdx, columnIdx, node) {
                                if (columnIdx === 2 || columnIdx === 3) {
                                    var selectedText = $(data).find('option:selected').text();
                                    return selectedText;
                                }
                                if (columnIdx === 5) {
                                    var textContent = $('<div>').html(data).text();
                                    var companyIndex = textContent.indexOf("Company");
                                    if (companyIndex !== -1) {
                                        textContent = textContent.substring(0, companyIndex);
                                        textContent = textContent.trim();
                                    }

                                    return textContent;
                                }

                                return data;
                            }
                        }
                    },
                    customize: function(doc) {
                        doc.watermark = null;
                        var logo = dataUrl;
                        doc.content.splice(0, 0, {
                            margin: [10, 10, 10, 10],
                            alignment: 'center',
                            width: 50,
                            image: logo
                        });
                        doc.defaultStyle = {
                            alignment: 'center'
                        };
                        function removeWordFromContent(content, wordToRemove) {
                            if (content instanceof Array) {
                                for (var i = 0; i < content.length; i++) {
                                    content[i] = removeWordFromContent(content[i], wordToRemove);
                                }
                            } else if (content instanceof Object) {
                                for (var key in content) {
                                    if (content.hasOwnProperty(key)) {
                                        content[key] = removeWordFromContent(content[key], wordToRemove);
                                    }
                                }
                            } else if (typeof content === 'string') {
                                // Check if the content contains the word to remove
                                if (content.includes(wordToRemove)) {
                                    // Remove the word
                                    content = content.replace(wordToRemove, '');
                                }
                            }
                            return content;
                        }
                        doc.content = removeWordFromContent(doc.content, 'Ya-Hub.com');
                    }
                }
            ]
        });
        $('.btn-pdf').on('click', function() {
            if (globalTable) {
                globalTable.buttons('.buttons-pdf').trigger();
            } else {
                console.error("DataTable is not initialized yet.");
            }
        });
        globalTable.buttons().container().hide();
    });
});


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
            @foreach($estimates as $estimate) 
            @php 
                if($estimate->currency=='usd'){$currency = '$';}
                if($estimate->currency=='eur'){$currency = '€';}
                if($estimate->currency=='gbp'){$currency = '£';}
            @endphp
            <tr>
                <td>{{$estimate->id}}</td>
                <td>{{$estimate->invoice_no}}</td>
                <td>{{\Str::upper($estimate->currency)}}</td>
                <td>{{$currency}} {{$estimate->total}}</td>
                <td>
                    @php
                        if($estimate->company_id){
                            $company = \DB::table('users')->where('id',$estimate->company_id)->first();
                        }
                    @endphp
                    {{$estimate->company_id && $company ? $company->name : $estimate->company_name}} 
                </td>
                <td>{{$estimate->created_at}}</td>
            </tr> 
            @endforeach 
        </tbody>
    </table>
</div>
 @endsection
