@extends('layouts.agency') 
@section('content') 
    <style>
        .table{
            border: 0 !important;
        }
        .pr-10{
            padding-right: 10px;
        }
        .flex-center{
            display: flex;
            /* justify-content: center; */
            align-items: center
        }
        th{
            text-align:left !important;
        }
        td{
            text-align:left !important;
            border-right: 0px !important;
            border-left: 0px !important
        }
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
        .my-row{
            border-bottom: 1px solid #8080801a;
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
    </style>
    {{-- Add Bank Modal --}}
    <div class="modal fade" tabindex="-1" role="dialog" id="add_bank_modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('add_bank_detail')}}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Transaction - ({{$banks->name}})</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="bank_id" value="{{$banks->id}}">
                        <div class="form-group mb-2">
                            <input type="date" name="date" id="" value="" class="form-control" placeholder="Date" required>
                        </div>
                        <div class="form-group mb-2">
                            <select name="type" id="" class="form-control" required>
                                <option value="" selected>Select type</option>
                                <option value="MI | Money In">MI | Money In</option>
                                <option value="MO | Money Out">MO | Money Out</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <select name="category_id" id="" class="form-control" required>
                                <option value="" selected>Select Category</option>
                                @if($banks->categories)
                                    @foreach($banks->categories as $bankCat)
                                        <option value="{{$bankCat->id}}">{{$bankCat->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="des" id="" value="" class="form-control" placeholder="Description" required>
                        </div>
                        <div class="form-group mb-2">
                            <input type="text" name="currency" id="" value="{{$banks->currency}}" class="form-control" placeholder="" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <input type="number" name="vat" id="percentage" value="" class="form-control" placeholder="VAT %" required>
                        </div>
                        <div class="form-group mb-2">
                            <input type="number" name="vat_amount" id="percentage_amount" value="" class="form-control" placeholder="VAT Amount" readonly>
                        </div>
                        <div class="form-group mb-2">
                            <input type="number" name="total" id="total" step="0.01" value="" class="form-control" placeholder="Total" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-sm mr-2" type="submit">Save</button>
                        <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <strong style="font-size: 22px">Transactions</strong>
            <a style="float:right" data-toggle="modal" data-target="#add_bank_modal" class="btn btn-primary btn-sm">New Transaction +</a>
            <a style="float:right;margin-right: 5px !important" href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
        </div>
        <div class="col-md-12 mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Currency</th>
                        <th>Net</th>
                        <th>Tax</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($details)
                        @foreach($details as $detail)
                            <tr>
                                @php 
                                    if($detail->currency=='USD'){$currency = '$';}
                                    if($detail->currency=='EUR'){$currency = '€';}
                                    if($detail->currency=='GBP'){$currency = '£';}
                                @endphp
                                <td>{{$detail->date}}</td>
                                <td>{{$detail->type}}</td>
                                <td>{{$detail->category->name}}</td>
                                <td>{{$detail->des}}</td>
                                <td>{{$detail->currency}}</td>
                                <td>{{$currency}}{{number_format((float)($detail->total-$detail->vat_amount),2,'.','')}}</td>
                                <td>{{$detail->vat}}%</td>
                                <td>{{$currency}}{{ number_format((float)($detail->total),2,'.','')}}</td>
                                <td>
                                    <a href="#0" data-toggle="modal" data-target="#edit_bank_modal_{{$detail->id}}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a data-action="{{route('delete_bank_detail',$detail->id)}}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            {{-- Add Bank Modal --}}
                            <div class="modal fade" tabindex="-1" role="dialog" id="edit_bank_modal_{{$detail->id}}">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <form action="{{route('update_bank_detail',$detail->id)}}" method="post">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Transaction</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group mb-2">
                                                    <input type="date" name="date" id="date_edit" value="{{$detail->date}}" class="form-control" placeholder="Date" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <select name="type" id="" class="form-control" required>
                                                        <option value="MI | Money In" {{$detail->type=='MI | Money In' ? 'selected' : ''}}>MI | Money In</option>
                                                        <option value="MO | Money Out" {{$detail->type=='MO | Money Out' ? 'selected' : ''}}>MO | Money Out</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <select name="category_id" id="" class="form-control" required>
                                                        @if($banks->categories)
                                                            @foreach($banks->categories as $bankCat)
                                                                <option value="{{$bankCat->id}}" {{$detail->category_id==$bankCat->id ? 'selected' : ''}}>{{$bankCat->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" name="des" id="" value="{{$detail->des}}" class="form-control" placeholder="Description" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="text" name="currency" id="" value="{{$banks->currency}}" class="form-control" placeholder="" readonly>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="number" name="vat" id="percentage_edit" value="{{$detail->vat}}" class="form-control" placeholder="VAT %" required>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="number" name="vat_amount" id="percentage_amount_edit" value="{{$detail->vat_amount}}" class="form-control" placeholder="VAT Amount" readonly>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <input type="number" name="total" id="total_edit" step="0.01" value="{{$detail->total}}" class="form-control" placeholder="Total" >
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary btn-sm mr-2" type="submit">Save</button>
                                                <a href="#0" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- End --}}
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let actionLinks = document.querySelectorAll('.btn-danger');
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
        // Attach event listeners to "percentage" and "total" fields
        $('#percentage, #total').on('input', function() {
            var percentage = parseFloat($('#percentage').val()) || 0
            var total = parseFloat($('#total').val()) || 0
            var percentageAmount = (percentage / 100) * total
            $('#percentage_amount').val(percentageAmount.toFixed(2))
        })
        $('#percentage_edit, #total_edit').on('input', function() {
            var percentage = parseFloat($('#percentage_edit').val()) || 0
            var total = parseFloat($('#total_edit').val()) || 0
            var percentageAmount = (percentage / 100) * total
            $('#percentage_amount_edit').val(percentageAmount.toFixed(2))
        })
    </script>
    <div class="tb-hidden" style="display: none">
        <table class="table-hidden-estimate table-stripped table-bordered" style="width: 100%;">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Currency</th>
                    <th>Net</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @if($details)
                    @foreach($details as $detail)
                        <tr>
                            @php 
                                if($detail->currency=='USD'){$currency = '$';}
                                if($detail->currency=='EUR'){$currency = '€';}
                                if($detail->currency=='GBP'){$currency = '£';}
                            @endphp
                            <td>{{$detail->date}}</td>
                            <td>{{$detail->type}}</td>
                            <td>{{$detail->category->name}}</td>
                            <td>{{$detail->des}}</td>
                            <td>{{\Str::upper($detail->currency)}}</td>
                            <td>{{$currency}}{{number_format((float)($detail->total-$detail->vat_amount),2,'.','')}}</td>
                            <td>{{$detail->vat}}%</td>
                            <td>{{$currency}}{{ number_format((float)($detail->total),2,'.','')}}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection
