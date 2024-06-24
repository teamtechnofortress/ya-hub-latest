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
                <h1>Trips</h1>
            </div>
            <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                <a href="#0" class="btn btn-light btn-sm btn-filter" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a>
                <a href="#0" class="btn btn-primary btn-sm btn-pdf" onclick="$('.table').DataTable().buttons('.buttons-pdf').trigger()">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a  href="#0" data-toggle="modal" data-target="#addTaskModal" class="btn btn-primary btn-sm add">Add Trips +</a>
            </div>
        </div>
        <div class="row mt-2 advanceFilter" style="display: none">
            <div class="col-lg-6">
                <form action="{{url('filter_trip_month')}}" method="post">
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
                <form action="{{url('filter_trip_year')}}" method="post">
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
        <div class="col-md-12 task-form" style="display:none">
        <form action="{{url('add_trip')}}" method="post" id="form-task" data-action="">
            @csrf
            <input type="hidden" class="task_id" name="id">
            <div class="row">
            <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Transport</label>
                        <input type="text" class="form-control task_date" name="transport" id="end_date" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Accommodation</label>
                        <input type="text" class="form-control task_date" name="accommodation" id="end_date" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Meals</label>
                        <input type="text" class="form-control task_date" name="meals" id="end_date" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Misc</label>
                        <input type="text" class="form-control task_date" name="misc" id="end_date" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Location</label>
                        <input type="text" class="form-control task_date" name="location" id="end_date" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Description</label>
                        <input type="text" class="form-control task_date" name="description" id="end_date" placeholder="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contact">Date</label>
                        <input type="month" name="date" id="contact" class="form-control contact_task_id" required />
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="status">Currency</label>
                        <select name="currency" id="status" class="form-control task_status">
                        <option value="usd" >USD</option>
                        <option value="eur" >EUR</option>
                        <option value="gbp" >GBP</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Amount</label>
                        <input type="number" step="0.01" class="form-control task_date" name="amount" id="end_date" placeholder="Amount" required>
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
                    <table class="table table-stripped table-bordered" id="tripstable" style="position: static;">
                        <thead>
                            <tr>
                            	@php

                            	$currency = '$';
                            	@endphp
                                <th>Transport</th>
                                <th>Accommodation</th>
                                <th>Meals</th>
                                <th>Misc</th>
                                <th>Location</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Currency</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($data as $item)
                            @php 
                                    if($item->currency=='usd'){$currency = '$';}
                                    if($item->currency=='eur'){$currency = '€';}
                                    if($item->currency=='gbp'){$currency = '£';}
                            @endphp
                            <tr>
                                <td>{{$item->transport}}</td>
                                <td>{{$item->accommodation}}</td>
                                <td>{{$item->meals}}</td>
                                <td>{{$item->misc}}</td>
                                <td>{{$item->location}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</td>
                                <td>
                                <form action="{{route('changeTripCurrency',$item->id)}}" method="POST" class="currencyForm">
                                   @csrf
                                    <select name="currency" class="form-control selectC">
                                        <option value="usd" {{ $item->currency == 'usd' ? 'selected' : '' }}>USD</option>
                                        <option value="eur" {{ $item->currency == 'eur' ? 'selected' : '' }}>EUR</option>
                                        <option value="gbp" {{ $item->currency == 'gbp' ? 'selected' : '' }}>GBP</option>
                                    </select>  
                                    </form>
                                <script>$('.selectC').change(function(){$(this).parent('.currencyForm').submit()})</script>       
                                </td>
                                <td class="currency-amount">{{$currency}} {{$item->amount}}</td>
                                <td>
                                     <a href="{{ url('updatetrip/' . $item->id) }}" class="btn btn-light btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a> 
                                    <a href="{{ url('deletetrip/'. $item->id) }}" class="btn btn-danger btn-sm deletetrip">
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
      var deleteStatus = false;
    var _token = $("input[name='_token']").val()
    $(".deletetrip").on("click", function(e) {
        var $this = $(this);
        if (deleteStatus == false) {
            e.preventDefault();
            Swal.fire({
                title: "Delete Trip",
                text: "Are you sure? You won't be able to revert this!",
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
    });
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
$('.add,.cancel').click(function(){
        $('.table').toggle('slow')
        $('.task-form').toggle('slow')
        clear_form()
    })
    $(document).ready(function() {
        function initializeDataTableWithLogo(logoUrl) {
        // Destroy existing DataTable instance, if any
        if ($.fn.DataTable.isDataTable('#tripstable')) {
            $('#tripstable').DataTable().destroy();
        }   
    $('#tripstable').DataTable( {
        dom: 'lBfrtip',
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        buttons: [
            {
                    extend: 'pdf',
                    
                customize: function (doc) {
                    doc.defaultStyle.alignment = 'center';
                        // doc.content[1].table.widths = 
                        //     Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.content.splice(0, 0, {
                            image: logoUrl,
                            width: 50,
                            alignment: 'center'
                        });
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
                        },
                    text: 'Export PDF',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 8]  // Specify the columns you want to include in the PDF export (0-indexed)
                    }
                },
                {
                    extend: 'csvHtml5',
                    text: 'Export CSV',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 8]   // Specify the columns you want to include in the PDF export (0-indexed)
                    }
                },
            'copyHtml5',
            'excelHtml5',
        ],
        order: [[6, 'asc']], // Sort by the third column (index 2), which is the Date column
            columnDefs: [
                { targets: [0, 1, 2, 3, 4, 5, 7, 8], orderable: true }, // Disable sorting for all columns except the Date column
            ]


    } );
}

// Fetch logo and initialize DataTable when document is ready
toDataURL('http://127.0.0.1:8000/frontend/Pics/logo.png', function(dataUrl) {
    initializeDataTableWithLogo(dataUrl);
});

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