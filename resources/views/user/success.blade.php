@extends('user.layout.index')

@section('title') Payment success @endsection

@section('css')

@endsection

@section('content')
    <section class="text-center download-section">
        <div class="image-box">
            <img src="{{ url('front/images/successfful-image.png') }}">
        </div>
        <h3>Payment Successful!</h3>
        <h4>Download Our App</h4>
        <div class="download-buttons">
            <div class="download-item">
                <a href="#">
                    <img src="{{ url('front/images/google-play-badge.svg') }}">
                </a>
            </div>
            <div class="download-item">
                <a href="#">
                    <img src="{{ url('front/images/app-store-apple.svg') }}">
                </a>
            </div>
        </div>
    </section>

@endsection

@section('js')

@endsection