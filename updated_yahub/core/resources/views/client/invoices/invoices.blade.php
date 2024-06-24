@extends('layouts.client') 
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
</style>
<script src="https://js.stripe.com/v3/"></script>
<div class="p-sm-4 p-3 project">
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Invoices</h1>
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
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody> 
                            @foreach($invoices as $invoice) 
                                @php 
                                    $user = \DB::table('users')->where('id',$invoice->user_id)->first();
                                @endphp
                                <tr> 
                                    <td>{{$invoice->id}}</td>
                                    <td>{{$invoice->invoice_no}}</td>
                                    <td>@if($invoice->currency=='gbp') {{'£'}} @elseif($invoice->currency=='eur') {{'€'}} @else {{'$'}} @endif {{$invoice->total}}</td>
                                    <td>{{$invoice->created_at}}</td>
                                    <td>
                                        @if($invoice->is_saved==1)
                                            <a href="{{$invoice->template_id==1 ? url('client/viewInvoice/'.$invoice->id) : url('client/viewInvoice/'.$invoice->id.'?lang=fr')}}" class="btn btn-light btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            {{-- @if($invoice->status==1) --}}
                                                @php 
                                                    $check = \DB::table('accepted_invoices')->where(['user_id'=>\Auth::user()->id,'data_id' => $invoice->id])->first();
                                                @endphp
                                                @if($check)
                                                    @if($check->paid )
                                                        <a href="#0" class="btn btn-sm btn-success">
                                                            Already Paid
                                                        </a>
                                                        <a href="{{url('reciept/'.$check->id)}}" target="_blank" class="btn btn-sm btn-light">
                                                            Receipt <i class="fa fa-download"></i>
                                                        </a>
                                                    @endif
                                                @elseif($invoice->paid)
                                                    <a href="#0" class="btn btn-sm btn-success">
                                                        Already Paid
                                                    </a>
                                                @else
                                                    <a href="#0" class="btn btn-sm btn-dark" id="pay-btn_{{$invoice->id}}" data-currency="{{$invoice->currency}}" data-id="{{$invoice->id}}" data-ch="{{$invoice->total}}" data-toggle="modal" data-target="#exampleModal_{{$invoice->id}}">Pay Now</a>
                                                @endif
                                            {{-- @else
                                                <a href="#0" class="btn btn-sm btn-dark pay-btn" data-currency="{{$invoice->currency}}" data-id="{{$invoice->id}}" data-ch="{{$invoice->total}}" data-toggle="modal" data-target="#exampleModal">Pay Now</a>
                                            @endif --}}
                                        @endif
                                        <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal_{{$invoice->id}}">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <form action="{{url('client/payInvoice')}}" method="post" id="payment-form_{{$invoice->id}}">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Payment via Debit/Credit Card</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="text-center mb-2">
                                                                <a href="https://www.stripe.com" target="blank">
                                                                    <img width="100px" src="{{asset('uploads/stripeLogo.png')}}" alt="" srcset="">
                                                                </a>
                                                            </div>
                                                            <input type="hidden" id="res_data_{{$invoice->id}}" name="res_stripe">
                                                            <input type="hidden" name="amount" id="amount_{{$invoice->id}}">
                                                            <input type="hidden" name="currency" id="currency_{{$invoice->id}}">
                                                            <input type="hidden" name="invoice_id" id="invoice_id_{{$invoice->id}}">
                                                            <label for="card-element" style="margin-bottom:10px !important"> Enter Your card</label>
                                                            <div id="card-element_{{$invoice->id}}"></div>
                                                            <div id="card-errors_{{$invoice->id}}" style="color: rgb(151, 0, 0)" role="alert"></div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary btn-sm mr-2" type="submit" id="customsubmit_{{$invoice->id}}">Pay Now</button>
                                                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr> 
                                <!-- Techno Fortress -->
                                <script type="text/javascript">
                                    $('#pay-btn_{{$invoice->id}}').click(function(){
                                        $('#amount_{{$invoice->id}}').val($(this).data('ch'))
                                        $('#invoice_id_{{$invoice->id}}').val($(this).data('id'))
                                        $('#currency_{{$invoice->id}}').val($(this).data('currency'))
                                        console.log( $('#currency_{{$invoice->id}}').val())
                                    })
                                    var stripe_{{$invoice->id}} = Stripe("{{$user ? $user->pk : ''}}")
                                    // Create an instance of Elements.
                                    var elements_{{$invoice->id}} = stripe_{{$invoice->id}}.elements()
                                    var style_{{$invoice->id}} = {
                                        base: {
                                            color: '#32325d',
                                            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                                            fontSmoothing: 'antialiased',
                                            fontSize: '16px',
                                            '::placeholder': {
                                                color: '#aab7c4'
                                            }
                                        },
                                        invalid: {
                                            color: '#fa755a',
                                            iconColor: '#fa755a'
                                        }
                                    }
                                    var card_{{$invoice->id}} = elements_{{$invoice->id}}.create('card', {
                                        hidePostalCode: true,
                                        style: style_{{$invoice->id}}
                                    })

                                    // Add an instance of the card Element into the `card-element` <div>.
                                    card_{{$invoice->id}}.mount('#card-element_{{$invoice->id}}')

                                    // Handle real-time validation errors from the card Element.
                                    card_{{$invoice->id}}.addEventListener('change', function(event) {
                                        var displayError_{{$invoice->id}} = document.getElementById('card-errors_{{$invoice->id}}')
                                        if (event.error) {
                                            displayError_{{$invoice->id}}.textContent = event.error.message;
                                        } 
                                        else {
                                            displayError_{{$invoice->id}}.textContent = ''
                                        }
                                    })

                                    // Handle form submission.
                                    var form_{{$invoice->id}} = document.getElementById('payment-form_{{$invoice->id}}')
                                    form_{{$invoice->id}}.addEventListener('submit', function(event) {
                                        event.preventDefault()
                                        $("#customsubmit_{{$invoice->id}}").attr("disabled", true);
                                        stripe_{{$invoice->id}}.createToken(card_{{$invoice->id}}).then(function(result) {
                                            if (result.error) {
                                                // Inform the user if there was an error.
                                                var errorElement_{{$invoice->id}} = document.getElementById('card-errors_{{$invoice->id}}')
                                                errorElement_{{$invoice->id}}.textContent = result.error.message
                                                $("#customsubmit_{{$invoice->id}}").removeAttr('disabled')
                                            } 
                                            else {
                                                // Send the token to your server.
                                                stripeTokenHandler_{{$invoice->id}}(result)
                                            }
                                        })
                                    })

                                    // Submit the form with the token ID.
                                    function stripeTokenHandler_{{$invoice->id}}(result) {
                                        // Insert the token ID into the form so it gets submitted to the server
                                        var form_{{$invoice->id}} = document.getElementById('payment-form_{{$invoice->id}}')
                                        var hiddenInput_{{$invoice->id}} = document.createElement('input')
                                        hiddenInput_{{$invoice->id}}.setAttribute('type', 'hidden')
                                        hiddenInput_{{$invoice->id}}.setAttribute('name', 'stripeToken')
                                        hiddenInput_{{$invoice->id}}.setAttribute('value', result.token.id)
                                        form_{{$invoice->id}}.appendChild(hiddenInput_{{$invoice->id}})
                                        $('#res_data_{{$invoice->id}}').val(JSON.stringify(result))
                                        // Submit the form
                                        form_{{$invoice->id}}.submit()
                                    }
                                    var modal = document.querySelector(".modal");
                                    var trigger = document.querySelector(".trigger");
                                    var closeButton = document.querySelector(".close-button");

                                    function toggleModal() {
                                        modal.classList.toggle("show-modal");
                                    }

                                    function windowOnClick(event) {
                                        if (event.target === modal) {
                                            toggleModal();
                                        }
                                    }

                                    trigger.addEventListener("click", toggleModal);
                                    closeButton.addEventListener("click", toggleModal);
                                    window.addEventListener("click", windowOnClick);
                                </script>
                            @endforeach 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
 @endsection
