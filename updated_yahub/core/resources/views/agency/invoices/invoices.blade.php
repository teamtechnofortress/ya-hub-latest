@extends('layouts.agency') 
@section('content') 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<div class="p-sm-4 p-3 project">
    <style>
        .modal-dialog-centered{
            max-width: 100% !important;
            justify-content: center
        }
        .modal-content{
            max-width: 600px;
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
        .modal-header .close {
            padding: 0
        }
        .select2-selection__choice{
            margin-right: 5px !important;
            margin-top: 5px !important;
            margin-left: 5px !important;
        }
        @media (min-width: 993px) {
            /* styles for screens with a maximum width of 992 pixels go here */
            /* .total_info{
                position: relative;
                top: -60px;
                left: 450px;
            } */
        }
        .ex{
            width: 100%;
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
        div.dt-buttons{
            position: absolute !important;
            bottom: 340px !important;
            left: 65.7% !important;

        }
        .dataTables_wrapper .dt-buttons .buttons-pdf    {
            /* position: absolute; */
            color: #fff; 
            border-radius: 3px; 
            padding: 3px 12px;
            margin-right: 10px;
            font-size: 12px !important;
            border-color: #007BFF;     /* Your custom border color */
            color: #fff;  
            background-color: #007BFF !important; 
        }
        .dataTables_wrapper .dt-buttons .buttons-pdf:hover{
            background-color: #007BFF; 
            color: #fff; 
        }
        /* .dataTables_paginate{
            display: none !important;
        } */
        /* .btn-filter
        {
            right: 51px !important;
            position: relative !important;
        } */
        .table-responsive{
        overflow-x: hidden !important;
    }
    .dataTables_filter input{
        outline: #454d55!important;
        border: 1px solid #ced4da;
        margin-left: 8px !important;
    }
        div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.25rem !important;
    }


    </style>
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Invoices</h1>
            </div>
            <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                <a href="#0" class="btn btn-light btn-sm btn-filter" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a>
                <a href="#0" class="btn btn-primary btn-sm btn-pdf">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a href="{{route('addInvoice',['lang' => 'en'])}}" class="btn btn-primary btn-sm">New English Invoice</a>
                <a href="{{route('addInvoice', ['lang' => 'fr'])}}" class="btn btn-primary btn-sm">New French Invoice</a>
            </div>
        </div>
        <div class="row mt-2 advanceFilter" style="display: none">
            <div class="col-lg-6">
                <form action="" method="get">
                    <div class="form-group">
                        <label for="sale_month">Sale amount per month</label>
                        <input type="month" name="month" class="form-control" id="sale_month" required value="{{ request('month') }}">
                    </div>
                    <div class="form-group mt-2">
                        <input type="submit" class="btn btn-light" value="Apply">
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <form action="" method="get">
                    <div class="form-group">
                        <label for="sale_year">Sale amount per year</label>
                        <input type="number" name="year" class="form-control" id="sale_year" min="1900" max="2099" placeholder="Type year min 1900 and max current year" required value="{{request('year')}}" >
                    </div>
                    <div class="form-group mt-2">
                        <input type="submit" class="btn btn-light" value="Apply">
                    </div>
                </form>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Invoice No.</th>
                                <td>Project</td>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Assign</th>
                                <th>Company</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($invoices as $invoice)
                                @php 
                                    if($invoice->currency=='usd'){$currency = '$';}
                                    if($invoice->currency=='eur'){$currency = '€';}
                                    if($invoice->currency=='gbp'){$currency = '£';}
                                @endphp
                                <tr>
                                    <td>{{$invoice->id}}</td>
                                    <td>{{$invoice->invoice_no}}</td>
                                    <td>
                                        <form action="{{route('changeProject',$invoice->id)}}" method="POST" class="projectForm">
                                            @csrf
                                            <select name="project" class="form-control selectP" style="max-width: 160px">
                                                <option value="">Select Project</option>
                                                @if(count(Auth::user()->projects) > 0)
                                                    @foreach(Auth::user()->projects as $pr)
                                                        <option value="{{$pr->id}}" {{$invoice->project_id==$pr->id ? 'selected' : ''}}>{{$pr->project_title}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </form>
                                        <script>$('.selectP').change(function(){$(this).parent('.projectForm').submit()})</script>
                                    </td>
                                    <td>
                                        <form action="{{route('changeCurrency',$invoice->id)}}" method="POST" class="currencyForm">
                                            @csrf
                                            <select name="currency" class="form-control selectC">
                                                <option value="usd" {{$invoice->currency=='usd' ? 'selected' : ''}}>USD</option>
                                                <option value="eur" {{$invoice->currency=='eur' ? 'selected' : ''}}>EUR</option>
                                                <option value="gbp" {{$invoice->currency=='gbp' ? 'selected' : ''}}>GBP</option>
                                            </select>
                                        </form>
                                        <script>$('.selectC').change(function(){$(this).parent('.currencyForm').submit()})</script>
                                    </td>
                                    @php 
                                        $totalVat = \DB::table('template_items')->where('data_id',$invoice->id)->sum('vat_amount');
                                    @endphp
                                    <td class="currency-amount">{{$currency}} {{$invoice->total-$totalVat}}</td>
                                    <td style="display: flex;">
                                        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal-assign-{{$invoice->id}}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form action="{{url('assignInvoices/'.$invoice->id)}}" method="post">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Assign to Clients</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @php 
                                                                  $clients = \DB::table('assign_invoice')->where('invoice_id',$invoice->id)->get();
                                                                $allClients = \DB::table('users')->where('role',3)->get();
                                                                $client = [];
                                                                foreach ($clients as $key => $value) {
                                                                    array_push($client,$value->client_id);
                                                                }
                                                            @endphp
                                                            <label for="selectM{{$invoice->id}}" style="margin-bottom:10px !important"> Select Clients</label>
                                                            <select class="form-control multipleS" id="selectM{{$invoice->id}}" name="users[]" multiple="multiple" style="width: 100%;padding: 5px">
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
                                                        $(document).ready(function() {$('#selectM{{$invoice->id}}').select2()})
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-text-container">
                                            <!-- <div class="text">
                                                @foreach($allClients as $key => $val)
                                                    @if(in_array($val->id, $client))
                                                        <p style="font-size:larger">{{$val->username}}</p>
                                                    @endif
                                                @endforeach
                                            </div> -->
                                            <div class="button">
                                                <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal-assign-{{$invoice->id}}" >
                                                    <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 20 20" height="18px" viewBox="0 0 20 20" width="18px" fill="#FFFFFF"><g><rect fill="none" height="20" width="20"/></g><g><g><g><path d="M9.6 14H6v-1.5h4.11c.35-.68.82-1.27 1.41-1.75H6v-1.5h8v.35c.32-.06.66-.1 1-.10.71 0 1.38.14 2 .38V4.5c0-.83-.67-1.5-1.5-1.5h-3.57c-.22-.86-1-1.5-1.93-1.5-.93 0-1.71.64-1.93 1.5H4.5C3.67 3 3 3.67 3 4.5v11c0 .83.67 1.5 1.5 1.5h5.38c-.24-.62-.38-1.29-.38-2 0-.34.04-.68.1-1zM10 3c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5zM6 6h8v1.5H6V6z"/></g><path d="M15 11c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm2 4.5h-1.5V17h-1v-1.5H13v-1h1.5V13h1v1.5H17v1z"/></g></g></svg>
                                                </button>
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        @php
                                            if($invoice->company_id){
                                                $company = \DB::table('users')->where('id',$invoice->company_id)->first();
                                            }
                                        @endphp
                                        {{$invoice->company_id && $company ? $company->name : $invoice->company_name}} 
                                        <a href="#0" data-toggle="modal" data-target="#exampleModal-comp-{{$invoice->id}}"><i class="fa fa-edit"></i></a>
                                        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal-comp-{{$invoice->id}}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form action="{{url('updateCompany/'.$invoice->id)}}" method="post">
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
                                                                        <option value="{{$u_c->id}}" {{$invoice->company_id==$u_c->id ? 'selected' : ''}}>{{$u_c->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" name="company_name" id="" value="{{$invoice->company_id ? $company->name : $invoice->company_name}}" class="form-control" placeholder="Company name">
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
                                    <td>{{$invoice->created_at}}</td>
                                    <td style="display: flex">
                                        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal{{$invoice->id}}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php 
                                                            $clients_in = \DB::table('assign_invoice')->where('invoice_id',$invoice->id)->get();
                                                            $allClients = [];
                                                            foreach ($clients_in as $key => $value_in) {
                                                                $user_a = \DB::table('users')->where('id',$value_in->client_id)->first();
                                                                if($user_a){
                                                                    array_push($allClients,$user_a);
                                                                }
                                                            }
                                                        @endphp
                                                        <table class="ex" style="width: 100%">
                                                            <thead>
                                                                <tr>
                                                                    <th>Client</th>
                                                                    <th>Mail</th>
                                                                    <th>Mark Paid</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($allClients as $item)
                                                                    @php
                                                                        $check = \DB::table('accepted_invoices')->where(['user_id'=>$item->id,'data_id' => $invoice->id])->first();
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$item->username}}</td>
                                                                        <td>
                                                                            <a href="{{url('agency/mail?email='.$item->email)}}" class="btn btn-primary btn-sm">
                                                                                <i class="fa fa-paper-plane"></i>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            @if($check)
                                                                                <a href="{{!$check->paid ? url('markPaid/'.$invoice->id.'/'.$item->id) : '#0'}}" class="btn btn-{{ !$check->paid ? 'primary' : 'secondary' }} btn-sm">
                                                                                    {{ !$check->paid ? 'Paid' : 'Paid' }}
                                                                                </a>
                                                                            @else
                                                                                <a href="{{url('markPaid/'.$invoice->id.'/'.$item->id)}}" class="btn btn-primary btn-sm">
                                                                                    Paid
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php 
                                            $dt = \DB::table('template_data')->where('id',$invoice->id)->first();
                                        @endphp
                                        @if($dt->paid==0)
                                            <a href="{{url('/changePaymentStatus/'.$invoice->id.'/1')}}" class="btn btn-light btn-sm" style="font-weight: bold">{{$currency}}</a>
                                        @else
                                            <a href="{{url('/changePaymentStatus/'.$invoice->id.'/0')}}" class="btn btn-success btn-sm" style="font-weight: bold">{{$currency}}</a>
                                        @endif
                                        <style>.dropdown-menu > a{width: 100%;}.dropdown-menu > button{width: 100%;}.dropdown-menu{border: 0;box-shadow: 0px 0px 10px 0px #00000030;margin-top: 10px !important;}</style>
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              Action
                                            </button>
                                            <div class="dropdown-menu" style="padding: 10px" aria-labelledby="actionDropdown">
                                                @if($invoice->is_saved==1)
                                                <a href="{{$invoice->template_id==1 ? url('agency/viewInvoice/'.$invoice->id) : url('agency/viewInvoice/'.$invoice->id.'?lang=fr')}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{url('agency/copyInvoice/'.$invoice->id)}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-copy"></i>
                                                </a>
                                                @endif
                                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal{{$invoice->id}}">Details</button>
                                                <a href="{{$invoice->template_id==1 ? url('agency/editInvoice/'.$invoice->id) : url('agency/editInvoice/'.$invoice->id.'?lang=fr')}}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{url('agency/deleteInvoice/'.$invoice->id)}}" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
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
            <div class="col-md-12 col-sm-12" style="position: relative">
                <div class="total_info"></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<!-- <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script> -->
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script>
    $(document).ready(function(){

        $('.ex').DataTable({
            "pageLength": 5,
        })

        let totals = {'$': 0, '€': 0, '£': 0} // initialize totals object

        // loop through each table cell with a currency class
        $('.table .currency-amount').each(function() {
            let text = $(this).text(); // get the text content of the cell
            let currency = text.substring(0, 1); // get the currency symbol from the text
            let amount = parseFloat(text.substring(2)); // get the numerical amount from the text
            totals[currency] += amount; // add the amount to the correct currency's total
        })

        // build the totals string and append to the table
        function updateTotals() {
            let totalsString = 'Total&nbsp;&nbsp;'
            for (let currency in totals) {
                totalsString += currency + '' + totals[currency].toLocaleString() + `${currency!='£' ? '&nbsp;&nbsp;' : ''}`
            }
            // totalsString = totalsString.substring(0, totalsString.length - 2) // remove trailing comma and space
            $('.total_info').append('' + totalsString)
            console.log(totals['£'].toLocaleString())
        }

        setTimeout(function() {
            updateTotals()
        }, 500)

    })
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    var deleteBtns = document.querySelectorAll('.btn-danger');
    deleteBtns.forEach(function(deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault(); 

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                focusConfirm: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = e.target.href;
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
    var globalTable;

    toDataURL('https://ya-hub.com/app/frontend/Pics/logo.png', function(dataUrl) {
        globalTable = $('.table').DataTable({
            destroy: true,
            dom: '<"clear">lBfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            const totalColumns = $(node).parents('table').find('thead th').length;
                            if (idx === 5 || idx === 8) {
                                return false;
                            }
                            return true;
                        },
                        format: {
                            body: function (data, rowIdx, columnIdx, node) {
                                if (columnIdx === 2 || columnIdx === 3) {
                                    var selectedText = $(data).find('option:selected').text();
                                    return selectedText;
                                }
                                if (columnIdx === 5) {
                                    var textContent = $('<div>').html(data).text();
                                    var assignIndex = textContent.indexOf("Company");
                                    if (assignIndex !== -1) {
                                        textContent = textContent.substring(0, assignIndex);
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
                        doc.content[1].table.headerRows = 0;
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
                                if (content.includes(wordToRemove)) {
                                    content = content.replace(wordToRemove, '');
                                }
                            }
                            return content;
                        }
                        doc.content = removeWordFromContent(doc.content, 'Ya-Hub.com');
                    }
                },
            ]
        });
        globalTable.buttons('.buttons-pdf').nodes().css("display", "none");

        $(document).on('click', '.btn-pdf', function() {
            if (globalTable) {
                globalTable.button('.buttons-pdf').trigger();
            } else {
                console.error("DataTable is not initialized yet.");
            }
        });
    });
});


</script>
<div class="tb-hidden" style="display: none">
    <table class="table-hidden-estimate table-stripped table-bordered" style="width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Invoice No.</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>Company</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody> 
            @foreach($invoices as $invoice) 
                @php 
                    if($invoice->currency=='usd'){$currency = '$';}
                    if($invoice->currency=='eur'){$currency = '€';}
                    if($invoice->currency=='gbp'){$currency = '£';}
                @endphp
                <tr>
                    <td>{{$invoice->id}}</td>
                    <td>{{$invoice->invoice_no}}</td>
                    <td>{{\Str::upper($invoice->currency)}}</td>
                    <td class="currency-amount">{{$currency}} {{$invoice->total}}</td>
                    <td>
                        @php
                            if($invoice->company_id){
                                $company = \DB::table('users')->where('id',$invoice->company_id)->first();
                            }
                        @endphp
                        {{$invoice->company_id && $company ? $company->name : $invoice->company_name}} 
                    </td>
                    <td>{{$invoice->created_at}}</td>
                </tr> 
            @endforeach
        </tbody>
    </table>
</div>
 @endsection
