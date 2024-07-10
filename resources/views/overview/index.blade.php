@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
    <!-- Main Content Start -->
    <section class="main--content">
        <div class="row gutter-20">
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #EFEAFA;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Total Incident Today</div>
                    <div class="card-number mb-2">145</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #CBF2FF;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Emergency Queue</div>
                    <div class="card-number mb-2">6</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #E5EDE6;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Emergency Resolved</div>
                    <div class="card-number mb-2">117</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="icon-circle" style="background-color: #FFEDE2;">
                        <img src="{{ asset('assets/img/icon/users.png') }}"  alt="">
                    </div>
                    <div class="card-title mb-3">Currently Being Handled</div>
                    <div class="card-number mb-2">22</div>
                </div>
            </div>
        </div>
    </section>
@endsection
