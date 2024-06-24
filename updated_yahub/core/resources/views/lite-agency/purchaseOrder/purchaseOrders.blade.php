@extends('layouts.lite-agency') 
@section('content') 
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
                <a href="#0" class="btn btn-light btn-sm" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table-hidden-estimate').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a href="{{route('liteAgency_addPurchaseOrder')}}" class="btn btn-primary btn-sm">New Purchase Order</a>
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
                            @php 
                                if($purchaseOrder->currency=='usd'){$currency = '$';}
                                if($purchaseOrder->currency=='eur'){$currency = '€';}
                                if($purchaseOrder->currency=='gbp'){$currency = '£';}
                            @endphp
                            <tr>
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
                                                <a href="{{url('lite-agency/viewPurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{url('lite-agency/copyPurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-copy"></i>
                                                </a>
                                            @endif
                                            <a href="{{url('lite-agency/editPurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{url('lite-agency/deletePurchaseOrder/'.$purchaseOrder->id)}}" class="btn btn-danger btn-sm">
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
