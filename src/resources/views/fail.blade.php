@extends('site.layouts.app')

@section('title', trans('app.cart.checkout.fail_page'))

@section('content')
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">FAIL</h4>
        <p>Payment failed; Reason Provided by bank: %message</p>
        <hr>
        <p class="mb-0">We were unable to process your credit card payment; reason provided by bank: %message. If the problem persists, contact us to complete your order.</p>
    </div>
@endsection
