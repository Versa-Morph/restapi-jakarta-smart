@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<!-- Main Content Start -->
<section class="main--content">
    @include('components.alert')
    <form action="{{ route('agency-details.update', [$agency, $agencyDetail]) }}" method="POST" class="row gutter-20" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex justify-content-start align-items-center">
                        <h3 class="m-0 p-0">Edit Agency Details</h3>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="agency_id" class="form-control" value="{{ $agencyDetail->agency_id }}">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $agencyDetail->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="logo">Logo</label>
                        @if($agencyDetail->logo)
                        <div class="p-2">
                            <img src="{{ asset($agencyDetail->logo) }}" alt="{{ $agencyDetail->name }}" class="img-thumbnail d-block mx-auto" style="border-radius: 50%" width="100">
                        </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ $agencyDetail->longitude }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ $agencyDetail->latitude }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ $agencyDetail->address }}" required>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('agencies.show', $agency->id) }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-success ml-2">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
