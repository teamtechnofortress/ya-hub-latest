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
<div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{url('client/payEstimate')}}" method="post" id="payment-form">
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
                    <input type="hidden" id="res_data" name="res_stripe">
                    <input type="hidden" name="amount" id="amount">
                    <input type="hidden" name="currency" id="currency">
                    <input type="hidden" name="estimate_id" id="invoice_id">
                    <label for="card-element" style="margin-bottom:10px !important"> Enter Your card</label>
                    <div id="card-element"></div>
                    <div id="card-errors" style="color: rgb(151, 0, 0)" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm mr-2" type="submit" id="customsubmit">Pay Now</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="p-sm-4 p-3 project">
    <div class="py-4">
        <div class="row">
            <div class="col-lg-6">
                <h1>Estimates</h1>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-stripped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estimate No.</th>
                                <th>Total Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($estimates))
                                @foreach($estimates as $estimate) 
                                    <tr>
                                        <td>{{$estimate->id}}</td>
                                        <td>{{$estimate->invoice_no}}</td>
                                        <td>@if($estimate->currency=='gbp') {{'£'}} @elseif($estimate->currency=='eur') {{'€'}} @else {{'$'}} @endif {{$estimate->total}}</td>
                                        <td>{{$estimate->created_at}}</td>
                                        <td>
                                            @if($estimate->is_saved==1)
                                                <a href="{{$estimate->template_id==2 ? url('client/viewEstimate/'.$estimate->id) : url('client/viewEstimate/'.$estimate->id.'?lang=fr')}}" class="btn btn-light btn-sm">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                {{-- @if($estimate->status==1) --}}
                                                    @php 
                                                        $check = \DB::table('accepted_invoices')->where(['user_id'=>\Auth::user()->id,'data_id' => $estimate->id])->first();
                                                    @endphp
                                                    @if($check)
                                                        @if($check->paid)
                                                            <a href="#0" class="btn btn-sm btn-dark">
                                                                Already Paid
                                                            </a>
                                                        @else
                                                            <button class="btn btn-sm btn-warning" disabled>Approved</button>
                                                            {{-- <a href="#0" class="btn btn-sm btn-dark pay-btn" data-id="{{$estimate->id}}" data-currency="{{$estimate->currency}}" data-ch="{{$estimate->total}}" data-toggle="modal" data-target="#exampleModal">Pay Now</a> --}}
                                                        @endif
                                                    @else
                                                        <a href="{{url('client/acceptEstimate/'.$estimate->id)}}" class="btn btn-sm btn-primary">Accept Estimate</a>
                                                    @endif
                                                {{-- @else
                                                    <a href="{{url('client/acceptEstimate/'.$estimate->id)}}" class="btn btn-sm btn-primary">Accept Estimate</a>
                                                @endif --}}
                                            @endif
                                        </td>
                                    </tr> 
                                @endforeach 
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://js.stripe.com/v3/"></script>
<!-- Techno Fortress -->
<script type="text/javascript">
    $('.pay-btn').click(function(){
        $('#amount').val($(this).data('ch'))
        $('#invoice_id').val($(this).data('id'))
        $('#currency').val($(this).data('currency'))
    })
    var stripe = Stripe("pk_test_51Kt8LdGkjDb1KSjqlA1Im7R4ixPQvQvV8d9n7UTUrnFhJtoFjnhDGAZSypW3QXO8WCX2QPXFjPg46UM4KprjXvSO000pk9h1my")
    // Create an instance of Elements.
    var elements = stripe.elements()
    var style = {
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
    var card = elements.create('card', {
        hidePostalCode: true,
        style: style
    })

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element')

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors')
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = ''
        }
    })

    // Handle form submission.
    var form = document.getElementById('payment-form')
    form.addEventListener('submit', function(event) {
        event.preventDefault()
        $("#customsubmit").attr("disabled", true);
        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors')
                errorElement.textContent = result.error.message
                $("#customsubmit").removeAttr('disabled')
            } else {
                // Send the token to your server.
                stripeTokenHandler(result)
            }
        })
    })

    // Submit the form with the token ID.
    function stripeTokenHandler(result) {
        // Insert the token ID into the form so it gets submitted to the server
        var form = document.getElementById('payment-form')
        var hiddenInput = document.createElement('input')
        hiddenInput.setAttribute('type', 'hidden')
        hiddenInput.setAttribute('name', 'stripeToken')
        hiddenInput.setAttribute('value', result.token.id)
        form.appendChild(hiddenInput)
        $('#res_data').val(JSON.stringify(result))
        // Submit the form
        form.submit()
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
 @endsection
