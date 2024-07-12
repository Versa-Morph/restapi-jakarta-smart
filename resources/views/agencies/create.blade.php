@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<!-- Main Content Start -->
<section class="main--content">
    @include('components.alert')
    <form action="{{ route('agencies.store') }}" method="POST" class="row gutter-20" enctype="multipart/form-data">
        @csrf
        <div class="col-md-6">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex justify-content-start align-items-center">
                        <h3 class="m-0 p-0">Create Agency</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="icon">Icon</label>
                        <input type="file" name="icon" class="form-control" accept="image/png, image/jpeg, image/jpg, image/webp">
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('agencies.index') }}" class="btn btn-danger">back</a>
                        <button type="submit" class="btn btn-success ml-2">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection
