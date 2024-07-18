@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<!-- Main Content Start -->
<section class="main--content">
    @include('components.alert')
    <form action="{{ route('instances.update', $instance->id) }}" method="POST" class="row gutter-20" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="col-md-6">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex justify-content-start align-items-center">
                        <h3 class="m-0 p-0">Create Instance</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $instance->name }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="icon">Icon</label>
                        @if($instance->icon)
                        <div class="p-2">
                            <img src="{{ asset($instance->icon) }}" alt="{{ $instance->name }}" class="img-thumbnail d-block mx-auto" style="border-radius: 50%" width="100">
                        </div>
                        @endif
                        <input type="file" name="icon" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                    </div>
                </div>
                <div class="card-footer" style="border-radius: 0 0 20px 20px;">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('instances.show', $instance) }}" class="btn btn-danger">back</a>
                        <button type="submit" class="btn btn-success ml-2">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
