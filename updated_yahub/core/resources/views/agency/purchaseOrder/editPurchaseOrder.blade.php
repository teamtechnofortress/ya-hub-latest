@extends('layouts.agency') 
@section('content') 
@php 
    $user=\DB::table('users')->where('id',\Auth::user()->id)->orderBy('id','desc')->first();
    $theme_style = $user->theme_style;
    $logo = NULL;
@endphp
<?php 
    $theme_style = json_decode($theme_style);
    if($user->theme_setting==1){
        $logo = $user->theme_log;
    }
    $lang = 'en';
    if($template[0]->currency=='usd'){$currency = '$';}
    if($template[0]->currency=='eur'){$currency = '€';}
    if($template[0]->currency=='gbp'){$currency = '£';}
?>
<style>
    .mt-100{
        margin-top: 100px !important;
    }
    .mt-50{
        margin-top: 50px !important;
    }
    thead > tr{
        background: #fc5f29 !important;
    }
    .bg{
        background: #fc5f29;
        color: white;
    }
    .tbhead{
        padding: 10px 0px;
    }
    th{
        color: white
    }
    th,td{
        padding: 10px;
    }
tr > td{
        border-color: #fc5f29 !important;
    }
    .myTable{
        width: 100%;
    }
    .or{
        color: #fc5f29;
    }
</style>
<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <form action="{{route('savePurchaseOrder')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="purchaseOrder_id" value="{{$template_data[0]->id}}">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="row">
                    <div class="col-lg-6">
                        @php
                            $dept_logo = DB::table('departments')
                                        ->where('created_by', Auth::user()->id)
                                        ->where('active', 1)
                                        ->first();
                        @endphp
                         @if($dept_logo && isset($dept_logo->department_logo)) {{-- Assuming `logo` is the field containing the logo URL --}}
                             <img width="220px" src="{{ asset($dept_logo->department_logo) }}" class="img-fluid" alt="logo" />
                         @elseif($logo)
                             <img width="220px" src="{{$logo}}" class="img-fluid" alt="logo" />
                         @else
                             <img width="220px" src="{{asset('frontend/Pics/logo.png')}}" class="img-fluid" alt="logo" />
                         @endif
                        {{-- @if($logo)
                            <img width="220px" src="{{$logo}}" class="img-fluid" alt="logo" />
                        @else
                            <img width="220px" src="{{asset('frontend/Pics/logo.png')}}" class="img-fluid" alt="logo" />
                        @endif --}}
                    </div>
                    <div class="col-lg-6">
                            <input name="invoice_no" type="text" class="form-control" style="margin-bottom: 5px !important" value="{{$template_data[0]->invoice_no}}" {{$template[0]->maintempid != NULL ? 'readonly' : '' }}>
                            <textarea name="date_desc" class="form-control">{{$template[0]->date_desc}}</textarea>
                    </div>
                    </div>
                    <div class="row mt-100">
                        <div class="col-6">
                            <textarea name="info" class="form-control" rows="6">{{$template[0]->info}}</textarea>
                        </div>
                        <div class="col-6">
                            <textarea name="info2" class="form-control" rows="6">{{$template[0]->info2}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-50">
                        <div class="col-12">
                            <input type="text" class="form-control" name="table_title" value="{{$template[0]->table_title}}">
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px !important">
                        <div class="col-lg-12 text-right">
                            <a href="#0" class="btn btn-primary" id="add_text_btn">Add Text</a>
                            <a href="#0" class="btn btn-primary" id="add_items_invoice">Add Item</a>
                        </div>
                        <div class="col-lg-12">
                            <table class="myTable" style="margin-top: 20px !important;border: 0 !important;">
                                <thead>
                                    <tr>
                                        <th style="border-left-color: #fc5f29 !important">Item</th>
                                        <th>Ref.</th>
                                        <th>Qty</th>
                                        <th>Unit Net Price</th>
                                        <th>Total Net</th>
                                        <th>VAT %</th>
                                        <th>VAT Amount</th>
                                        <th style="border-right-color: #fc5f29 !important">Total Gross</th>
                                    </tr>
                                </thead>
                                <tbody id="items_tbl">
                                    @php
                                        $total_net = 0;
                                        $total_vat = 0;
                                        $total_gross = 0;
                                    @endphp
                                    @if(count($template_items) > 0)
                                        @foreach($template_items as $key=>$items)
                                            @php
                                                $total_net = number_format((float)($total_net) + ($items->total_net),2,'.','');
                                                $total_vat = number_format((float)($total_vat) + ($items->vat_amount),2,'.','');
                                                $total_gross = number_format((float)($total_gross) + ($items->total_gross),2,'.','');
                                            @endphp
                                            @if($items->item !='' && $items->ref=='' && $items->qty==0)
                                                <tr>
                                                    <td colspan="7"><input type="text" name="item[]" style="width:100%" value="{{$items->item}}"></td>
                                                    <td style="display:none"><input type="text" name="ref[]" value=""></td>
                                                    <td style="display:none"><input type="number" name="qty[]" value="0">
                                                        <input style="max-width:50px" type="text" name="qty_unit[]">
                                                    </td>
                                                    <td style="display:none"><input type="number" name="unit_price[]" value="0"></td>
                                                    <td style="display:none"><input type="number" name="total_net[]" value="0" readonly></td>
                                                    <td style="display:none"><input type="number" name="vat[]" value="0"></td>
                                                    <td style="display:none"><input type="number" name="vat_amount[]" value="0" readonly></td>
                                                    <td>
                                                        <input style="display:none" name="total_gross[]">
                                                        <a style="display: block;" href="#0" class="btn btn-danger del_item btn-sm"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td><input type="text" style="width:100%" name="item[]" value="{{$items->item}}"></td>
                                                    <td><input style="max-width:90px" type="text" name="ref[]" value="{{$items->ref}}"></td>
                                                    <td><input style="max-width:50px" class="qty_input" type="number" name="qty[]" value="{{$items->qty}}"><input style="max-width:50px" class="qty_unit" type="text" name="qty_unit[]" value="{{$items->qty_unit}}"></td>
                                                    <td><input style="max-width:100px" class="unit_price_input" type="text" step="any" name="unit_price[]" value="{{$items->unit_price}}"></td>
                                                    <td><input style="max-width:100px" class="total_net_input" type="number" name="total_net[]" value="{{$items->total_net}}" readonly></td>
                                                    <td><input style="max-width:50px" class="vat_input" type="text" step="any" name="vat[]" value="{{$items->vat}}"></td>
                                                    <td><input style="max-width:100px" class="vat_amount_input" type="number" name="vat_amount[]" value="{{$items->vat_amount}}" readonly></td>
                                                    <td style="display:flex;"><input style="max-width:100px" class="total_gross_input" type="number" name="total_gross[]" value="{{$items->total_gross}}" readonly>
                                                        <a href="#0" class="btn btn-danger del_item btn-sm"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                    <tr style="border: 0 !important;" class="before">
                                        <td colspan="3" style="border: 0 !important;text-align: right"></td>
                                        <td style="border: 0 !important;text-align: right" class="bg">Total</td>
                                        <td style="border: 0 !important;text-align: center" class="bg">{{$currency}} <span class="total_net">{{$total_net}}</span></td>
                                        <td style="border: 0 !important" class="bg"></td>
                                        <td style="border: 0 !important;text-align: center" class="bg">{{$currency}} <span class="total_vat">{{$total_vat}}</span></td>
                                        <td style="border: 0 !important;text-align: center" class="bg">{{$currency}} <span class="total_gross">{{$total_gross}}</span></td>
                                    </tr>
                                    <tr style="border: 0 !important;">
                                        <td colspan="7" style="border: 0 !important;text-align: right;padding:5px;padding-top: 40px;"><strong>{{$lang=='fr' ? 'Total HT' : 'Total net price'}}</strong></td>
                                        <td style="border: 0 !important;text-align: center;padding:5px;padding-top: 40px;">{{$currency}} <span class="total_net">{{$total_net}}</span></td>
                                    </tr>
                                    <tr style="border: 0 !important;">
                                        <td colspan="7" style="border: 0 !important;text-align: right;padding:5px"><strong>{{$lang=='fr' ? 'Montant TVA' : 'VAT Amount'}}</strong></td>
                                        <td style="border: 0 !important;text-align: center;padding:5px">{{$currency}} <span class="total_vat">{{$total_vat}}</span></td>
                                    </tr>
                                    <tr style="border: 0 !important;">
                                        <td colspan="7" style="border: 0 !important;text-align: right;padding:5px"><strong>{{$lang=='fr' ? 'Total TTC' : 'Total gross price'}}</strong></td>
                                        <td style="border: 0 !important;text-align: center;padding:5px">{{$currency}} <span class="total_gross">{{number_format($total_gross,2,'.','')}}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-50">
                        @if($template[0]->maintempid == NULL)
                            <div class="col-lg-12 text-left">
                                <div class="total">
                                    <strong class="or">{{$lang=='fr' ? 'À payer' : 'Total Due'}}: {{$lang=='en' ? $currency : ''}} <span class="total_gross or">{{$total_gross}}</span>{{$lang=='fr' ? $currency : ''}}</strong>
                                </div>
                                <div class="total" style="margin-top: 5px !important;">
                                    <strong>{{$lang=='fr' ? 'Mode de règlement' : 'Payment Type'}}: <input type="text" class="form-control" value="{{$template[0]->payment_type}}" name="payment_type"></strong>
                                </div>
                            </div>
                            <div class="col-lg-12 text-left">
                                <div class="total" style="margin-top: 5px !important;">
                                    <strong>{{$lang=='fr' ? 'Date limite de règlement' : 'Due Date'}}: <input type="text" class="form-control" value="{{$template[0]->due_date}}" name="due_date"></strong>
                                </div>
                            </div>
                            <div class="col-lg-12 text-left">
                                <div class="total" style="margin-top: 5px !important;">
                                    <strong>{{$lang=='fr' ? 'Informations spécifiques' : 'Notes'}}: 
                                    <textarea class="form-control"name="notes" rows="10">{{$template[0]->notes}}</textarea>
                                </strong>
                                </div>
                            </div>
                        @else
                                @php
                                    $dept = DB::table('departments')
                                                ->where('created_by', Auth::user()->id)
                                                ->where('active', 1)
                                                ->first();

                                    $maintmps = DB::table('main_templatefor_deps')
                                                ->where('user_id', Auth::user()->id)
                                                ->where('depid',$dept->id)
                                                ->get();

                                    $maintmp = DB::table('main_templatefor_deps')
                                                ->where('id', $template[0]->maintempid)
                                                ->where('user_id', Auth::user()->id)
                                                ->get();
                                    // $mainTmp = $maintmps->merge($maintmp);
                                    $notetempsArray = $maintmps->toArray();
                                    $notetempArray = $maintmp->toArray();

                                    // Combine arrays, but filter out duplicates based on 'id'
                                    $combinedArray = $notetempsArray;

                                    foreach ($notetempArray as $note) {
                                        // Check if the note is already in the combined array
                                        $exists = false;
                                        foreach ($combinedArray as $existingNote) {
                                            if ($existingNote->id == $note->id) {
                                                $exists = true;
                                                break;
                                            }
                                        }
                                        if (!$exists) {
                                            $combinedArray[] = $note;
                                        }
                                    }
                                    // Convert the array back to a collection
                                    $mainTmp = collect($combinedArray);
                    
                                    $notetemps = DB::table('note_templatefor_deps')
                                                ->where('user_id', Auth::user()->id)
                                                ->where('depid',$dept->id)
                                                ->where('notefor','purchaseOrder')
                                                ->get();

                                    $notetemp = DB::table('note_templatefor_deps')
                                                ->where('id', $template[0]->notespoid)
                                                ->where('user_id', Auth::user()->id)
                                                ->where('notefor','purchaseOrder')
                                                ->get();

                                                $notetempsArray = $notetemps->toArray();
                                                $notetempArray = $notetemp->toArray();

                                                // Combine arrays, but filter out duplicates based on 'id'
                                                $combinedArray = $notetempsArray;

                                                foreach ($notetempArray as $note) {
                                                    // Check if the note is already in the combined array
                                                    $exists = false;
                                                    foreach ($combinedArray as $existingNote) {
                                                        if ($existingNote->id == $note->id) {
                                                            $exists = true;
                                                            break;
                                                        }
                                                    }
                                                    if (!$exists) {
                                                        $combinedArray[] = $note;
                                                    }
                                                }
                                                // Convert the array back to a collection
                                                $noteTemp = collect($combinedArray);
                    
                                @endphp
                                {{-- {{$notetemps}} --}}
                                <div class="col-lg-12 text-left">
                                    <div class="form-group mt-4">
                                        <label for="contact">Main Template</label>
                                        <select class="custom-select" name="maintempid" required>
                                            <option value="">Open this select Main Temp</option>
                                            @foreach($mainTmp as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $template[0]->maintempid ? 'selected' : '' }}>{{ $item->tempName}}</option>
                                            @endforeach
                                        </select>
                                        {{-- {{$template[0]->maintempid}}
                                        {{$template[0]->noteid}} --}}

                                    </div>
                                </div>   
                                {{-- <div class="col-lg-12 text-left">
                                    <div class="form-group mt-4">
                                        <label for="contact">Notes Template</label>
                                        <select class="custom-select" name="notestempid" required>
                                            <option value="">Open this select Note</option>
                                            @foreach($noteTemp as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $template[0]->notespoid ? 'selected' : '' }}>{{ $item->notename }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                        @endif
                        {{-- <div class="col-lg-12 text-left">
                            <div class="total">
                                <strong class="or">Total due: {{$currency}} <span class="total_gross or">{{number_format($total_gross,2,'.','')}}</span></strong>
                            </div>
                            <div class="total" style="margin-top: 5px !important;">
                                <strong>Payment type: <input type="text" class="form-control" value="{{$template[0]->payment_type}}" name="payment_type"></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 text-left">
                            <div class="total" style="margin-top: 5px !important;">
                                <strong>Due date: <input type="text" class="form-control" value="{{$template[0]->due_date}}" name="due_date"></strong>
                            </div>
                        </div>
                        <div class="col-lg-12 text-left">
                            <div class="total" style="margin-top: 5px !important;">
                                <strong>Notes: 
                                <textarea class="form-control"name="notes" rows="10">{{$template[0]->notes}}</textarea>
                            </strong>
                            </div>
                        </div> --}}
                        <div class="col-lg-12 text-left">
                            <div class="total" style="margin-top: 50px !important;">
                                <h5>Buyer's signature</h5>
                            @if($template[0]->seller_signature=='https://ya-hub.com/app/frontend/Pics/logo.png' || $template[0]->seller_signature=='')
                                <img src="{{asset('uploads/signature.jpeg')}}" alt="" id="signature" srcset="" width="200px">
                            @else
                                <img src="{{$template[0]->seller_signature}}" alt="" id="signature" srcset="" width="200px">
                            @endif
                                <input type="file" class="form-control mt-3" id="seller_signature" name="seller_signature">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-12 text-center">
                            <textarea name="footer" rows="3" cols="60" style="font-size:10px">{{$template[0]->footer}}</textarea>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-12 text-right">
                            <a href="javascript:history.back()" class="btn btn-light">Cancel</a>
                            <input type="submit" value="Save" class="btn btn-primary">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        function getSum(element){
            var val = 0
            $(element).each(function(){
                if($(this).val()!='') val = val + parseFloat($(this).val())
            })
            return val
        }
        function getSumVerticle(element){
            var qty = parseInt($(element).parent().parent('tr').find('.qty_input').val())
            var unit_price = parseFloat($(element).parent().parent('tr').find('.unit_price_input').val())
            var total_net = parseFloat($(element).parent().parent('tr').find('.total_net_input').val())
            var vat_disc = parseFloat($(element).parent().parent('tr').find('.vat_input').val())
            var vat_amount = parseFloat($(element).parent().parent('tr').find('.vat_amount_input').val())
            var total_gross = parseFloat($(element).parent().parent('tr').find('.total_gross_input').val())
            var newTotalNet = qty*unit_price
            $(element).parent().parent('tr').find('.total_net_input').val((newTotalNet).toFixed(2))
            $(element).parent().parent('tr').find('.vat_amount_input').val((vat_disc/100*newTotalNet).toFixed(2))
            $(element).parent().parent('tr').find('.total_gross_input').val((newTotalNet+(vat_disc/100*newTotalNet)).toFixed(2))

            
        }
        $('body').on('keyup','.vat_amount_input,.vat_input,.qty_input,.total_net_input,.unit_price_input,.vat_amount_input,.total_gross_input',function (e) {
            getSumVerticle($(this))
            var totalNet = getSum($('.total_net_input'))
            var totalVatAmount = getSum($('.vat_amount_input'))
            var totalGross = getSum($('.total_gross_input'))
            $('.total_net').text(totalNet.toFixed(2))
            $('.total_vat').text(totalVatAmount.toFixed(2))
            $('.total_gross').text(totalGross.toFixed(2))
        })
        $('#add_items_invoice').click(function(){
            $(`<tr>
                    <td><input type="text" name="item[]" style="width:100%"  value=""></td>
                    <td><input style="max-width:90px" type="text" name="ref[]" value=""></td>
                    <td><input style="max-width:50px" class="qty_input" type="number" name="qty[]" value="0"><input style="max-width:50px" class="qty_unit" type="text" name="qty_unit[]"></td>
                    <td><input style="max-width:100px" class="unit_price_input" type="text" step="any" name="unit_price[]" value="0"></td>
                    <td><input style="max-width:100px" class="total_net_input" type="number" name="total_net[]" value="0" readonly></td>
                    <td><input style="max-width:50px" class="vat_input" type="text" step="any" name="vat[]" value="0"></td>
                    <td><input style="max-width:100px" class="vat_amount_input" type="number" name="vat_amount[]" value="0" readonly></td>
                    <td style="display:flex;"><input style="max-width:100px" type="number" class="total_gross_input" name="total_gross[]" value="0" readonly>
                        <a href="#0" class="btn btn-danger del_item btn-sm"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>`).insertBefore('.before')
        })
        $('#add_text_btn').click(function(){
            $(`<tr>
                    <td colspan="7"><input type="text" name="item[]" style="width:100%"></td>
                    <td style="display:none"><input type="text" name="ref[]" value=""></td>
                    <td style="display:none"><input type="number" name="qty[]" value="0">
                        <input style="max-width:50px" type="text" name="qty_unit[]">
                    </td>
                    <td style="display:none"><input type="number" name="unit_price[]" value="0"></td>
                    <td style="display:none"><input type="number" name="total_net[]" value="0" readonly></td>
                    <td style="display:none"><input type="number" name="vat[]" value="0"></td>
                    <td style="display:none"><input type="number" name="vat_amount[]" value="0" readonly></td>
                    <td>
                        <input style="display:none" name="total_gross[]">
                        <a style="display: block;" href="#0" class="btn btn-danger del_item btn-sm"><i class="fa fa-trash"></i></a></td>
                </tr>`).insertBefore('.before')
        })
    })
    $('body').delegate('.del_item','click',function(){
        $(this).parent().parent().remove()
    })
    $("#seller_signature").change(function(){
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#signature').attr("src",e.target.result)
        }
        reader.readAsDataURL($('#seller_signature')[0].files[0])
    })
 </script>
 @endsection
