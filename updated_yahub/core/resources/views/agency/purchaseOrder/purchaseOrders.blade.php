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
        bottom: 411px !important;
        left: 76.3% !important;


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
    .table-responsive{
        overflow: none;
    }
    .dataTables_filter input{
        border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: 0.25rem !important;
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
                <h1>Purchase Orders</h1>
            </div>
            <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                <a href="#0" class="btn btn-light btn-sm btn-filter" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a>
                <a href="#0" class="btn btn-primary btn-sm btn-pdf">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a href="{{route('addPurchaseOrder')}}" class="btn btn-primary btn-sm">New Purchase Order</a>
            </div>
        </div>
        <div class="row mt-2 advanceFilter" style="display: none">
            <div class="col-lg-6">
                <form action="" method="get">
                    <div class="form-group">
                        <label for="sale_month">PO amount per month</label>
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
                        <label for="sale_year">PO amount per year</label>
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
                                <th>PO No</th>
                                <th>Project</th>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Supplier</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($purchaseOrders as $purchaseOrder) 
                            <tr>
                                @php 
                                    if($purchaseOrder->currency=='usd'){$currency = '$';}
                                    if($purchaseOrder->currency=='eur'){$currency = '€';}
                                    if($purchaseOrder->currency=='gbp'){$currency = '£';}
                                @endphp
                                <td>{{$purchaseOrder->id}}</td>
                                <td>{{$purchaseOrder->invoice_no}}</td>
                                <td>
                                    <form action="{{route('changeProject',$purchaseOrder->id)}}" method="POST" class="projectForm">
                                        @csrf
                                        <select name="project" class="form-control selectP" style="max-width: 160px">
                                            <option value="">Select Project</option>
                                            @if(count(Auth::user()->projects) > 0)
                                                @foreach(Auth::user()->projects as $pr)
                                                    <option value="{{$pr->id}}" {{$purchaseOrder->project_id==$pr->id ? 'selected' : ''}}>{{$pr->project_title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </form>
                                    <script>$('.selectP').change(function(){$(this).parent('.projectForm').submit()})</script>
                                </td>
                                <td>
                                    <form action="{{route('changeCurrency',$purchaseOrder->id)}}" method="POST" class="currencyForm">
                                        @csrf
                                        <select name="currency" class="form-control selectC">
                                            <option value="usd" {{$purchaseOrder->currency=='usd' ? 'selected' : ''}}>USD</option>
                                            <option value="eur" {{$purchaseOrder->currency=='eur' ? 'selected' : ''}}>EUR</option>
                                            <option value="gbp" {{$purchaseOrder->currency=='gbp' ? 'selected' : ''}}>GBP</option>
                                        </select>
                                    </form>
                                    <script>$('.selectC').change(function(){$(this).parent('.currencyForm').submit()})</script>
                                </td>
                                @php 
                                    $totalVat = \DB::table('template_items')->where('data_id',$purchaseOrder->id)->sum('vat_amount');
                                @endphp
                                <td class="currency-amount">{{$currency}} {{$purchaseOrder->total-$totalVat}}</td>
                                <td>
                                    @php
                                        if($purchaseOrder->company_id){
                                            $company = \DB::table('users')->where('id',$purchaseOrder->company_id)->first();
                                        }
                                    @endphp
                                    {{$purchaseOrder->company_id && $company ? $company->name : $purchaseOrder->company_name}} 
                                    <a href="#0" data-toggle="modal" data-target="#exampleModal-comp-{{$purchaseOrder->id}}"><i class="fa fa-edit"></i></a>
                                    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal-comp-{{$purchaseOrder->id}}">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form action="{{url('updateCompany/'.$purchaseOrder->id)}}" method="post">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Supplier</h5>
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
                                                                    <option value="{{$u_c->id}}" {{$purchaseOrder->company_id==$u_c->id ? 'selected' : ''}}>{{$u_c->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" name="company_name" id="" value="{{$purchaseOrder->company_id ? $company->name : $purchaseOrder->company_name}}" class="form-control" placeholder="Company name">
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
                                <td>{{$purchaseOrder->created_at}}</td>
                                <td style="display: flex">
                                    @if($purchaseOrder->paid==0)
                                        <a href="{{url('/changePaymentStatus/'.$purchaseOrder->id.'/1')}}" class="btn btn-light btn-sm" style="font-weight: bold">{{$currency}}</a>
                                    @else
                                        <a href="{{url('/changePaymentStatus/'.$purchaseOrder->id.'/0')}}" class="btn btn-success btn-sm" style="font-weight: bold">{{$currency}}</a>
                                    @endif
                                    <style>.dropdown-menu > a{width: 100%;}.dropdown-menu > button{width: 100%;}.dropdown-menu{border: 0;box-shadow: 0px 0px 10px 0px #00000030;margin-top: 10px !important;}</style>
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Action
                                        </button>
                                        <div class="dropdown-menu" style="padding: 10px" aria-labelledby="actionDropdown">
                                            @if($purchaseOrder->is_saved==1)
                                                <a href="{{url('agency/viewPurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{url('agency/copyPurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-copy"></i>
                                                </a>
                                                @endif
                                                <a href="{{url('agency/editPurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a data-action="{{url('agency/deletePurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-danger btn-sm action-link">
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
<script>
    $(document).ready(function(){
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
        var globalTable = $('.table').DataTable({
            destroy: true,
            dom: '<"clear">lBfrtip',
            buttons: [
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            const totalColumns = $(node).parents('table').find('thead th').length;
                            if (idx === 7) {
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
                                    var assignIndex = textContent.indexOf("Supplier");
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
                }
            ]
        });
        globalTable.buttons('.buttons-pdf').nodes().css("display", "none");
        $('.btn-pdf').on('click', function() {
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
                <th>PO No</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>Supplier</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody> 
            @foreach($purchaseOrders as $purchaseOrder) 
            <tr>
                @php 
                    if($purchaseOrder->currency=='usd'){$currency = '$';}
                    if($purchaseOrder->currency=='eur'){$currency = '€';}
                    if($purchaseOrder->currency=='gbp'){$currency = '£';}
                @endphp
                <td>{{$purchaseOrder->id}}</td>
                <td>{{$purchaseOrder->invoice_no}}</td>
                <td>{{\Str::upper($purchaseOrder->currency)}}</td>
                <td>{{$currency}} {{$purchaseOrder->total}}</td>
                <td>
                    @php
                        if($purchaseOrder->company_id){
                            $company = \DB::table('users')->where('id',$purchaseOrder->company_id)->first();
                        }
                    @endphp
                    {{$purchaseOrder->company_id && $company ? $company->name : $purchaseOrder->company_name}} 
                </td>
                <td>{{$purchaseOrder->created_at}}</td>
            </tr> 
            @endforeach 
        </tbody>
    </table>
</div>
 @endsection
