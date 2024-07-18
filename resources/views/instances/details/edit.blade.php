@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<!-- Main Content Start -->
<section class="main--content">
    @include('components.alert')
    <form action="{{ route('instance-details.update', [$instance, $instanceDetail]) }}" method="POST" class="row gutter-20" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-md-6 mb-5">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex justify-content-start align-items-center">
                        <h3 class="m-0 p-0">Edit Instance Details</h3>
                    </div>
                </div>
                <div class="card-body">
                    <input type="hidden" name="instance_id" class="form-control" value="{{ $instanceDetail->instance_id }}">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $instanceDetail->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="logo">Logo</label>
                        @if($instanceDetail->logo)
                        <div class="p-2">
                            <img src="{{ asset($instanceDetail->logo) }}" alt="{{ $instanceDetail->name }}" class="img-thumbnail d-block mx-auto" style="border-radius: 50%" width="100">
                        </div>
                        @endif
                        <input type="file" name="logo" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="longitude">Longitude</label>
                                <input type="text" name="longitude" class="form-control" value="{{ $instanceDetail->longitude }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group mb-3">
                                <label for="latitude">Latitude</label>
                                <input type="text" name="latitude" class="form-control" value="{{ $instanceDetail->latitude }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pluscode">Plus Code</label>
                        <input type="text" name="pluscode" class="form-control" value="{{ $instanceDetail->pluscode }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" class="form-control" cols="30" rows="3" required>{{ $instanceDetail->address }}</textarea>
                    </div>
                </div>
                <div class="card-footer" style="border-radius: 0 0 20px 20px;">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('instances.show', $instance->id) }}" class="btn btn-danger">Back</a>
                        <button type="submit" class="btn btn-success ml-2">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
