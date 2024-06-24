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
        right: 5px !important;
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
                <h1>VAT</h1>
            </div>
            <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                
                <a href="#0" class="btn btn-light btn-sm btn-filter" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a>
                <a href="#0" class="btn btn-primary btn-sm btn-pdf" onclick="$('.table').DataTable().buttons('.buttons-pdf').trigger()">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
               
            </div>
        </div>
        <div class="row mt-2 advanceFilter" style="display: none">
            <div class="col-lg-6">
                <form action="{{url('filter_vat_month')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="sale_month">Amount per month</label>
                        <input type="month" name="month" class="form-control" id="sale_month" required value="{{ request('month') }}">
                    </div>
                    <div class="form-group mt-2">
                        <input type="submit" class="btn btn-light" value="Apply">
                    </div>
                </form>
            </div>
            <div class="col-lg-6">
                <form action="{{url('filter_vat_year')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="sale_year">Amount per year</label>
                        <input type="number" name="year" class="form-control" id="sale_year" min="1900" max="2099" placeholder="Type year min 1900 and max current year" required value="{{request('year')}}" >
                    </div>
                    <div class="form-group mt-2">
                        <input type="submit" class="btn btn-light" value="Apply">
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            
            <div class="col-lg-12 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered" id="vat_table" style="position: static;">
                        <thead>
                            <tr>
                            	
                                <th>Date</th>
                                <th>Vat In</th>
                                <th>Vat Out</th>
                                {{-- <th>Taxes</th>
                                <th>Total Hours</th>
                                <th>Cost Associated</th> --}}
                            </tr>
                             
                        </thead>
                        <tbody>
                            @isset($vat_year_filter)
                            @foreach($vat_year_filter as $records)
                                @foreach($records as $record)

                                @php
                                if($record->currency=='USD'){$currency = '$';}
                                if($record->currency=='EUR'){$currency = '€';}
                                if($record->currency=='GBP'){$currency = '£';}
                                @endphp
                            <tr>
                                {{-- <td>
                                    <select name="currency" class="form-control selectC">
                                    <option value="usd" >USD</option>
                                    <option value="eur" >EUR</option>
                                    <option value="gbp" >GBP</option>
                                    </select>       
                                </td> --}}
                                @if($record->type === 'MI | Money In')
                                <td class="">{{ date('F j, Y', strtotime($record->date))}}</td>
                                <td class="currency-amount">{{$currency}} {{$record->vat_amount}}</td>
                                <td class="currency-amount1">{{$currency}} 0</td>
                                @endif
                                @if($record->type === 'MO | Money Out')
                                <td class="">{{date('F j, Y', strtotime($record->date))}}</td>
                                <td class="currency-amount">{{$currency}} 0</td>
                                <td class="currency-amount1">{{$currency}} {{$record->vat_amount}}</td>
                                @endif
                                {{-- <td>
                                    <select name="taxis" class="form-control selectT">
                                    <option value="ok" >10</option>
                                    <option value="ok" >20</option>
                                    <option value="ok" >30</option>
                                    </select>       
                                </td>
                                <td>10</td>
                                <td>{{$currency}} 50</td> --}}
                            </tr> 
                            @endforeach
                            @endforeach
                            
                            
                        @endisset


                            @isset($vat_month_filter)
                            @foreach($vat_month_filter as $records)
                                @foreach($records as $record)

                                @php
                                if($record->currency=='USD'){$currency = '$';}
                                if($record->currency=='EUR'){$currency = '€';}
                                if($record->currency=='GBP'){$currency = '£';}
                                @endphp
                            <tr>
                                {{-- <td>
                                    <select name="currency" class="form-control selectC">
                                    <option value="usd" >USD</option>
                                    <option value="eur" >EUR</option>
                                    <option value="gbp" >GBP</option>
                                    </select>       
                                </td> --}}
                                @if($record->type === 'MI | Money In')
                                <td class="">{{date('F j, Y', strtotime($record->date))}}</td>
                                <td class="currency-amount">{{$currency}} {{$record->vat_amount}}</td>
                                <td class="currency-amount1">{{$currency}} 0</td>
                                @endif
                                @if($record->type === 'MO | Money Out')
                                <td class="">{{date('F j, Y', strtotime($record->date))}}</td>
                                <td class="currency-amount">{{$currency}} 0</td>
                                <td class="currency-amount1">{{$currency}} {{$record->vat_amount}}</td>
                                @endif
                                {{-- <td>
                                    <select name="taxis" class="form-control selectT">
                                    <option value="ok" >10</option>
                                    <option value="ok" >20</option>
                                    <option value="ok" >30</option>
                                    </select>       
                                </td>
                                <td>10</td>
                                <td>{{$currency}} 50</td> --}}
                            </tr> 
                            @endforeach
                            @endforeach
                            
                            
                        @endisset
                           
                            @isset($bank)
                            @foreach($bank as $bank_id) 
                                @php
                                $details = DB::table('BankDetails')->where('bank_id',$bank_id->id)->get();
                                @endphp
                                    @foreach($details as $detail)
                                        @php
                                        if($detail->currency=='USD'){$currency = '$';}
                                        if($detail->currency=='EUR'){$currency = '€';}
                                        if($detail->currency=='GBP'){$currency = '£';}
                                        @endphp
                            <tr>
                                {{-- <td>
                                    <select name="currency" class="form-control selectC">
                                       <option value="usd" >USD</option>
                                       <option value="eur" >EUR</option>
                                       <option value="gbp" >GBP</option>
                                     </select>       
                                </td> --}}
                                @if($detail->type === 'MI | Money In')
                                <td class="">{{date('F j, Y', strtotime($detail->date))}}</td>
                                <td class="currency-amount">{{$currency}} {{$detail->vat_amount}}</td>
                                <td class="currency-amount1">{{$currency}} 0</td>
                                @endif
                                @if($detail->type === 'MO | Money Out')
                                <td class="">{{date('F j, Y', strtotime($detail->date))}}</td>
                                <td class="currency-amount">{{$currency}} 0</td>
                                <td class="currency-amount1">{{$currency}} {{$detail->vat_amount}}</td>
                                @endif
                                {{-- <td>
                                    <select name="taxis" class="form-control selectT">
                                       <option value="ok" >10</option>
                                       <option value="ok" >20</option>
                                       <option value="ok" >30</option>
                                     </select>       
                                </td>
                                <td>10</td>
                                <td>{{$currency}} 50</td> --}}
                            </tr> 
                            @endforeach
                            @endforeach
                            @endisset
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12 col-sm-12" style="position: relative;">
                <div class="total_info_in"></div>
            </div>
            <div class="col-md-12 col-sm-12" style="position: relative;">
                <div class="total_info_out"></div>
            </div>
            <br><br>
            <div class="col-md-12 col-sm-12" style="position: relative;">
                <div class="total_net_balance" id="tot"></div>
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
            // console.log(amount);
        })

        // build the totals string and append to the table
        function updateTotals() {
            let totalsString = 'Total VAT In&nbsp;&nbsp;'
            for (let currency in totals) {
                totalsString += currency + '' + totals[currency].toLocaleString() + `${currency!='£' ? '&nbsp;&nbsp;' : ''}`
            }
            // totalsString = totalsString.substring(0, totalsString.length - 2) // remove trailing comma and space
            $('.total_info_in').append('' + totalsString)
            console.log(totals['£'].toLocaleString())
        }

        setTimeout(function() {
            updateTotals()
        }, 500)
    })
    $(document).ready(function(){
        let totals = {'$': 0, '€': 0, '£': 0} // initialize totals object

        // loop through each table cell with a currency class
        $('.table .currency-amount1').each(function() {
            let text = $(this).text(); // get the text content of the cell
            let currency = text.substring(0, 1); // get the currency symbol from the text
            let amount = parseFloat(text.substring(2)); // get the numerical amount from the text
            totals[currency] += amount; // add the amount to the correct currency's total
       
        })
        console.log(totals);


        // build the totals string and append to the table
        function updateTotals() {
            let totalsString = 'Total VAT Out&nbsp;&nbsp;'
            for (let currency in totals) {
                totalsString += currency + '' + totals[currency].toLocaleString() + `${currency!='£' ? '&nbsp;&nbsp;' : ''}`
            }
            // totalsString = totalsString.substring(0, totalsString.length - 2) // remove trailing comma and space
            $('.total_info_out').append('' + totalsString)
            console.log(totals['£'].toLocaleString())
        }

        setTimeout(function() {
            updateTotals()
        }, 500)
    })
    $(document).ready(function(){
        let totals = {'$': 0, '€': 0, '£': 0} // initialize totals object

        // loop through each table cell with a currency class
        $('.table .currency-amount').each(function() {
            let text = $(this).text(); // get the text content of the cell
            let currency = text.substring(0, 1); // get the currency symbol from the text
            let amount = parseFloat(text.substring(2)); // get the numerical amount from the text
            totals[currency] += amount; // add the amount to the correct currency's total
       
        })
        let totals1 = {'$': 0, '€': 0, '£': 0} // initialize totals object

        // loop through each table cell with a currency class
        $('.table .currency-amount1').each(function() {
            let text = $(this).text(); // get the text content of the cell
            let currency = text.substring(0, 1); // get the currency symbol from the text
            let amount = parseFloat(text.substring(2)); // get the numerical amount from the text
            totals1[currency] += amount; // add the amount to the correct currency's total
       
        })


        let totalsString = 'Net VAT&nbsp;&nbsp;';
        let currencySymbol = '$'; // Set the currency symbol

        let t = totals['$'] - totals1['$'];
        totalsString += currencySymbol + '' + t.toLocaleString() + `${currencySymbol !== '£' ? '&nbsp;&nbsp;' : ''}`;

        $('#tot').html(totalsString);
        // console.log(totals['$']);
        // console.log(totals1['$']);
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
    $('#vat_table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                    extend: 'pdf',
                    text: 'Export PDF',
                    // exportOptions: {
                    //     columns: [0, 1, 2, 4]  // Specify the columns you want to include in the PDF export (0-indexed)
                    // }
                },
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
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