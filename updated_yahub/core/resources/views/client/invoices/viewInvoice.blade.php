@extends('layouts.client') 
@section('content') 
@php 
    $logo = NULL;
    $user=\DB::table('users')->where('id',$template_data[0]->user_id)->orderBy('id','desc')->first();
    if($user){
        $theme_style = $user->theme_style;
        $theme_style = json_decode($theme_style);
        if($user->theme_setting==1){
            $logo = $user->theme_log;
        }
    }
    
@endphp
<?php 
    $lang = 'en';
    if(isset($_GET['lang'])){
        if($_GET['lang']=='fr'){
            $lang = 'fr';
        }
    }
    if($template[0]->currency=='usd'){$currency = '$';}
    if($template[0]->currency=='eur'){$currency = '€';}
    if($template[0]->currency=='gbp'){$currency = '£';}
?>
<style>
    .mt-100{
        margin-top: 100px !important;
    }
    .or{
        color: #fc5f29;
    }
    .fw6{
        font-weight: 600;
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
    tr > td{
        border-right: 0 !important;
        border-left-color: #fc5f29 !important;
        border-bottom: 0px !important;
    }
    tbody > tr > td:nth-last-child(1){
        border-right: 1px solid #fc5f29 !important;
    }
    tbody > tr{
        border-bottom: 0px !important;
    }
    tbody > .last_tr{
        border-bottom: 1px solid #fc5f29 !important;
    }
    .tbhead{
        padding: 10px 0px;
    }
    th{
        color: white;
        border-right: 0px !important;
    }
    thead > tr > th:nth-last-child(1){
        border-right: 1px solid #fc5f29 !important;
    }
    th,td{
        padding: 10px;
        text-align: center;
        border-top-color: #fc5f29 !important;
    }
    .myTable{
        width: 100%;
    }
    .or-heading{
        font-size: 16px;
        color: #fc5f29;
    }
    #pdfGenerate{
        font-size: 12px !important;
        font-family: Arial;
        padding-top: 0 !important;
    }
    strong.fw6{
        font-size: 12px !important
    }
    h5{
        font-size: 12px
    }
    .myTable{
        width: 100%;
        font-size: 12px
    }
    .datatr  td{
        border-left: 1px solid !important;
    border-right: 0 !important;
    border-left-color: #fc5f29 !important;
    border-bottom: 0px !important;
    } 
    .datatr th{
        text-align: left !important;
        border-right: 1px solid white !important;
     }

     tbody > tr{
        border-left: 1px solid #fc5f29 !important;
        border-bottom: 1px solid #fc5f29 !important;
    }
</style>
<div class="row mt-2">
    <div class="col-lg-12 text-right">
        <button class="btn btn-primary btn-sm" id="pdfButton">Generate PDF</button>
    </div>
</div>
<div class="p-sm-4 p-3 project" id="pdfGenerate">
    <div class="py-4">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-lg-6">
                        @php 
                            $usr = \App\Models\User::where('id',$template_data[0]->user_id)->first();
                            if($user) $logo = $usr->theme_log;
                        @endphp
                        @if($logo)
                            <img width="220px" src="{{$logo}}" class="img-fluid" alt="logo" />
                        @else
                            <img width="220px" src="{{asset('frontend/Pics/logo.png')}}" class="img-fluid" alt="logo" />
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <p><strong class="or-heading">{{$template_data[0]->invoice_no}}</strong></p>
                        <p><?=nl2br($template[0]->date_desc)?></p>
                    </div>
                </div>
                <div class="row mt-100">
                    <div class="col-6">
                        <p><?=nl2br($template[0]->info)?></p>
                    </div>
                    <div class="col-6">
                        <p class="or fw6"><?=nl2br($template[0]->info2)?></p>
                    </div>
                </div>
                <div class="row mt-50">
                    <div class="col-12">
                        <p class="or fw6">{{$template[0]->table_title}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="myTable" style="margin-top: 10px !important;border: 0 !important;">
                            <thead>
                                @if($lang=='en')
                                        <tr style="
                                                border-left: 1px solid #fc5f29;
                                            ">
                                            <th style="text-align: left;border-right: 1px solid white !important;">Item</th>
                                            <th>Ref.</th>
                                            <th>Qty</th>
                                            <th>Discount %</th>
                                            <th>Unit Net Price</th>
                                            <th>Total Net</th>
                                            <th>VAT %</th>
                                            <th>VAT Amount</th>
                                            <th style="border-right-color: #fc5f29 !important">Total Gross</th>
                                        </tr>
                                @else
                                    <tr class="datatr" style="
                                                border-left: 1px solid #fc5f29;
                                            ">
                                        <th style="text-align: left;border-right: 1px solid white !important;">Désignation</th>
                                        <th>Réf.</th>
                                        <th>Qté</th>
                                        <th>Réduction</th>
                                        <th>PU HT</th>
                                        <th>Total HT</th>
                                        <th>TVA</th>
                                        <th>Montant TVA</th>
                                        <th style="border-right-color: #fc5f29 !important">Total TTC</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody id="items_tbl">
                                @php
                                    $total_net = 0;
                                    $total_vat = 0;
                                    $total_gross = 0;
                                    $total_discount = 0;
                                @endphp
                                @if(count($template_items) > 0)
                                    @foreach($template_items as $key=>$items)
                                        @php
                                            $total_net = number_format((float)($total_net) + ($items->total_net),2,'.','');
                                            $total_vat = number_format((float)($total_vat) + ($items->vat_amount),2,'.','');
                                            $total_gross = number_format((float)($total_gross) + ($items->total_gross),2,'.','');
                                            $total_discount = $total_gross-$total_vat;
                                            $total_discount = $total_net-$total_discount;
                                        @endphp
                                         @if($items->item !='' && $items->ref=='' && $items->qty==0)
                                            <tr class="datatr {{count($template_items)-1==$key ? 'last_tr' : ''}}">
                                                <td style="text-align: left" colspan="9">{{$items->item}}</td>
                                            </tr>
                                        @else
                                            <tr class="datatr {{count($template_items)-1==$key ? 'last_tr' : ''}}">
                                                <td style="text-align: left;">{{$items->item}}</td>
                                                <td>{{$items->ref}}</td>
                                                <td>{{$items->qty}} {{$items->qty_unit}}</td>
                                                <td>{{$items->discount}}</td>
                                                <td>{{$items->unit_price}}</td>
                                                <td>{{$items->total_net}}</td>
                                                <td>{{$items->vat}}</td>
                                                <td>{{$items->vat_amount}}</td>
                                                <td>{{$items->total_gross}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                <tr style="border: 0 !important;" class="before">
                                    <td colspan="4" style="border: 0 !important;text-align: right"></td>
                                    <td style="border: 0 !important;text-align: right" class="bg">Total</td>
                                    <td style="border: 0 !important;text-align: center" class="bg">{{$lang=='en' ? $currency : ''}}{{$total_net}}{{$lang=='fr' ? $currency : ''}}</td>
                                    <td style="border: 0 !important" class="bg"></td>
                                    <td style="border: 0 !important;text-align: center" class="bg">{{$lang=='en' ? $currency : ''}}{{$total_vat}}{{$lang=='fr' ? $currency : ''}}</td>
                                    <td style="border: 0 !important;text-align: center" class="bg">{{$lang=='en' ? $currency : ''}}{{$total_gross}}{{$lang=='fr' ? $currency : ''}}</td>
                                </tr>
                                <tr style="border: 0 !important;">
                                    <td colspan="8" style="border: 0 !important;text-align: right;padding:5px;padding-top: 40px;"><strong>{{$lang=='fr' ? 'Total HT' : 'Total net price'}}</strong></td>
                                    <td style="border: 0 !important;text-align: center;padding:5px;padding-top: 40px;">{{$lang=='en' ? $currency : ''}} {{$total_net}}{{$lang=='fr' ? $currency : ''}}</td>
                                </tr>
                                <tr style="border: 0 !important;">
                                    <td colspan="8" style="border: 0 !important;text-align: right;padding:5px;"><strong>{{$lang=='fr' ? 'Remise' : 'Discount'}} </strong></td>
                                    <td style="border: 0 !important;text-align: center;padding:5px;">{{$lang=='en' ? $currency : ''}} <span class="total_disc_val">{{number_format($total_discount,2,'.','')}}</span>{{$lang=='fr' ? $currency : ''}}</td>
                                </tr>
                                <tr style="border: 0 !important;">
                                    <td colspan="8" style="border: 0 !important;text-align: right;padding:5px;"><strong>{{$lang=='fr' ? 'Total HT remisé' : 'Discounted net value'}}</strong></td>
                                    <td style="border: 0 !important;text-align: center;padding:5px;">{{$lang=='en' ? $currency : ''}} <span class="disc_total_vat">{{number_format(($total_gross-$total_vat),2,'.','')}}</span>{{$lang=='fr' ? $currency : ''}}</td>
                                </tr>
                                <tr style="border: 0 !important;">
                                    <td colspan="8" style="border: 0 !important;text-align: right;padding:5px"><strong>{{$lang=='fr' ? 'Montant TVA' : 'VAT Amount'}}</strong></td>
                                    <td style="border: 0 !important;text-align: center;padding:5px">{{$lang=='en' ? $currency : ''}} {{$total_vat}}{{$lang=='fr' ? $currency : ''}}</td>
                                </tr>
                                <tr style="border: 0 !important;">
                                    <td colspan="8" style="border: 0 !important;text-align: right;padding:5px"><strong>{{$lang=='fr' ? 'Total TTC' : 'Total gross price'}}</strong></td>
                                    <td style="border: 0 !important;text-align: center;padding:5px">{{$lang=='en' ? $currency : ''}} {{$total_gross}}{{$lang=='fr' ? $currency : ''}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row mt-50">
                    <div class="col-lg-12 text-left">
                        <div class="total">
                            <strong class="or">{{$lang=='fr' ? 'À payer' : 'Total Due'}}: {{$lang=='en' ? $currency : ''}} <strong class="or fw6 total_gross_c" style="font-size: 20px"> {{$total_gross}}</strong>{{$lang=='fr' ? $currency : ''}}</strong>
                        </div>
                        <div class="total" style="margin-top: 5px !important;">
                            <strong>{{$lang=='fr' ? 'Mode de règlement' : 'Payment Type'}}: </strong><span>{{$template[0]->payment_type}}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-left">
                        <div class="total" style="margin-top: 5px !important;">
                            <strong>{{$lang=='fr' ? 'Date limite de règlement' : 'Due Date'}}: </strong><span>{{$template[0]->due_date}}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-left">
                        <div class="total" style="margin-top: 5px !important;">
                            <strong>{{$lang=='fr' ? 'Informations spécifiques' : 'Notes'}}: </strong>
                            <p class="mt-3" style="font-size:12px"><?=nl2br($template[0]->notes)?></p>
                        </div>
                    </div>
                    <div class="col-lg-12 text-left">
                    <div class="total" style="margin-top: 50px !important;">
                        <h5 class="mb-2 or" style="font-weight: bold; font-size: 12px">{{$lang=='fr' ? 'Nom du vendeur' : "Seller's signature"}}</h5>
                        @if($template[0]->seller_signature)
                            <img src="{{$template[0]->seller_signature}}" alt="" srcset="" width="100px">
                        @endif
                        <?php 
                        $lines = explode("\n", $template[0]->info);
                        echo "<p>" . nl2br($lines[0]) . "</p>";
                        ?>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-lg-12 text-center">
        <p style="font-size:10px"><?=nl2br($template[0]->footer)?></p>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var opt = {
        margin: [0.6,0,0.8,0],
        filename:     '{{$template_data[0]->invoice_no}}.pdf',
        enableLinks:  true,
        image: { type: 'jpeg', quality: 0.5 },
        html2canvas: {
            dpi: 192,
            scale:4,
            letterRendering: true,
            useCORS: true
        },
        pagebreak:    { mode: 'avoid-all' },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
    }

    $('#pdfButton').click(function(){
        var element = document.getElementById('pdfGenerate')
        html2pdf().from(element).set(opt).toPdf().get('pdf').then(function (pdf) {
            var totalPages = pdf.internal.getNumberOfPages(); 
            //print current pdf width & height to console
            console.log("getHeight:" + pdf.internal.pageSize.getHeight());
            console.log("getWidth:" + pdf.internal.pageSize.getWidth());
            for (var i = 1; i <= totalPages; i++) {
                pdf.setPage(i);
                pdf.setFontSize(7)
                pdf.text(`{{$template[0]->footer}}`, pdf.internal.pageSize.getWidth()/3,pdf.internal.pageSize.getHeight()/1.05);
                pdf.text('Page ' + i + ' of ' + totalPages, pdf.internal.pageSize.getWidth()/12,pdf.internal.pageSize.getHeight()/1.05);
            } 
        }).save()
    })

    $(document).ready(function(){
        var lang = '{{$lang}}'
        if(lang=='fr'){
            $('.total_gross_c').text(parseFloat('{{$total_gross}}').toLocaleString(`fr-FR`))
        }else{
            $('.total_gross_c').text(parseFloat('{{$total_gross}}').toLocaleString())
        }
    })
</script>
 @endsection
