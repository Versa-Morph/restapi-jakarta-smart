@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<!-- Main Content Start -->
<section class="main--content">
    <div class="row gutter-20">
        <div class="col-12 col-md-4">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 p-0">Data Instances</h3>
                        <a href="{{ route('instances.index') }}" class="btn btn-danger">Back</a>
                    </div>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset($instance->icon) }}" width="150" alt="" class="d-block mx-auto" style="border-radius: 50%;">
                    <h3>{{ $instance->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 p-0">Detail Instances</h3>
                        <a href="{{ route('instance-details.create', $instance) }}" class="btn btn-primary">Create Data</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.alert')
                    <table id="instances-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="8%">Logo</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Pluscode</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instanceDetails as $instanceDetail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ asset($instanceDetail->logo) }}" width="50" alt="" class="d-block mx-auto" style="border-radius: 50%;"></td>
                                <td>{{ $instanceDetail->name }}</td>
                                <td>{{ $instanceDetail->address }}</td>
                                <td>{{ $instanceDetail->pluscode }}</td>
                                <td>{{ $instanceDetail->longitude }}</td>
                                <td>{{ $instanceDetail->latitude }}</td>
                                <td>
                                    <a href="{{ route('instance-details.edit', [$instance->id, $instanceDetail->id]) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('instance-details.destroy', [$instance->id, $instanceDetail->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
