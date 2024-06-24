@extends('layouts.agency') 
@section('content') 


<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Update Payment</h1>
            </div>
            
        </div>
        <div class="col-md-12 task-form">
        <form action="{{url('saveupdatepayments')}}" method="post" id="form-task" data-action="">
            @csrf
           
            <input type="hidden" name="id" id="contact" class="form-control" required value="{{ isset($data) ? $data->id : '' }}" />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contact">PO Number</label>
                        <input type="text" name="ponumber" id="contact" class="form-control" required value="{{ isset($data) ? $data->ponumber : '' }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contractor">Contractor</label>
                        <input type="text" name="contractor" id="contact" class="form-control" required value="{{ isset($data) ? $data->contractor : '' }}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="contact">Month</label>
                        <input type="month" name="date" id="date" class="form-control " required value="{{ isset($data) ? date('Y-m', strtotime($data->date)) : '' }}"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control task_status">
                        <option value="OK" {{$data->status == 'OK' ? 'selected' : ''}}>OK</option>
                        <option value="Nothing" {{$data->status == 'OK' ? 'selected' : ''}} >Nothing</option>
                       
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mt-4">
                        <label for="status">Currency</label>
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
                        <input type="number" step="0.01" class="form-control task_date" name="amount" id="amount" placeholder="Amount" required value="{{ isset($data) ? $data->amount : '' }}">
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





@endsection