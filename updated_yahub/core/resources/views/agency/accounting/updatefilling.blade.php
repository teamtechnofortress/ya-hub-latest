@extends('layouts.agency') 
@section('content') 



<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Update Filling</h1>
            </div>
            {{-- <div class="col-lg-6 text-right">
                @if(request('project'))
                    <a href="javascript:history.back()" class="btn btn-light btn-sm ml-2"><i class="fa fa-angle-left"></i> Back</a>
                @endif
                <a href="#0" class="btn btn-light btn-sm btn-filter" onclick="$('.advanceFilter').toggle('slow')">Advanced Filters</a>
                <a href="#0" class="btn btn-primary btn-sm btn-pdf" onclick="$('.table').DataTable().buttons('.buttons-pdf').trigger()">Export PDF</a>
                <a href="#0" class="btn btn-primary btn-sm" onclick="$('.table').DataTable().buttons('.buttons-csv').trigger()">Export CSV</a>
                <a  href="#0" data-toggle="modal" data-target="#addTaskModal" class="btn btn-primary btn-sm add">Add Balance +</a>
            </div> --}}
        </div>
        <div class="row mt-2 advanceFilter" style="display: none">
            <div class="col-lg-6">
                <form action="#" method="">
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
                <form action="#" method="">
                    @csrf
                    <div class="form-group">
                        <label for="sale_year">Amount per year</label>
                        <input type="number"  name="year" class="form-control" id="sale_year" min="1900" max="2099" placeholder="Type year min 1900 and max current year" required value="{{request('year')}}" >
                    </div>
                    <div class="form-group mt-2">
                        <input type="submit" class="btn btn-light" value="Apply">
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-12 task-form" style="">
        <form action="{{ url('saveupdatefilling') }}" method="post">
            @csrf
            <input type="hidden" class="task_id" name="id">
            <div class="row">
                <input type="hidden" name="id" id="contact" class="form-control contact_task_id" required value="{{ isset($data) ? $data->id : '' }}" />
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="note" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control task_note" placeholder="Description">{{ isset($data) ? $data->description : '' }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contact">Due Date</label>
                        <input type="month" name="month" id="contact" class="form-control contact_task_id" required value="{{ isset($data) ? date('Y-m', strtotime($data->duedate)) : '' }}" />
                        {{-- <input type="month"  name="month" id="contact" class="form-control contact_task_id" required value="{{ isset($data) ? $data->date : '' }}" /> --}}
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="currency">Currency</label>
                        <select name="currency" id="status" class="form-control task_status">
                            <option value="usd" {{ $data->currency == 'usd' ? 'selected' : '' }}>USD</option>
                            <option value="eur" {{ $data->currency == 'eur' ? 'selected' : '' }}>EUR</option>
                            <option value="gbp" {{ $data->currency == 'gbp' ? 'selected' : '' }}>GBP</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="end_date">Amount</label>
                        <input type="number" step="0.01" class="form-control task_date" name="amount" id="end_date" placeholder="Amount" required value="{{ isset($data) ? $data->amount : '' }}" >
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mt-4 text-right">
                        {{-- <a href="#0" class="btn btn-light cancel">Cancel</a> --}}
                        <input type="submit" class="btn btn-secondary" value="Submit">
                    </div>
                </div>
            </div>
        </form>
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
            let totalsString = 'Total Credit&nbsp;&nbsp;'
            for (let currency in totals) {
                totalsString += currency + '' + totals[currency].toLocaleString() + `${currency!='£' ? '&nbsp;&nbsp;' : ''}`
            }
            // totalsString = totalsString.substring(0, totalsString.length - 2) // remove trailing comma and space
            $('.total_info_credit').append('' + totalsString)
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

        // build the totals string and append to the table
        function updateTotals() {
            let totalsString = 'Total Debit&nbsp;&nbsp;'
            for (let currency in totals) {
                totalsString += currency + '' + totals[currency].toLocaleString() + `${currency!='£' ? '&nbsp;&nbsp;' : ''}`
            }
            // totalsString = totalsString.substring(0, totalsString.length - 2) // remove trailing comma and space
            $('.total_info_debit').append('' + totalsString)
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
    $('#balance_table').DataTable( {
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
</div>
 @endsection
