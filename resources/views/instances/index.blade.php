@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<section class="main--content">
    <div class="row gutter-20">
        <div class="col-12">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Instances</h3>
                        <a href="{{ route('instances.create') }}" class="btn btn-primary">Create Data</a>
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
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($instances as $instance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ asset($instance->icon) }}" width="50" alt="" class="d-block mx-auto" style="border-radius: 50%;"></td>
                                <td>{{ $instance->name }}</td>
                                <td>
                                    <a href="{{ route('instances.show', $instance->id) }}" class="btn btn-info">View</a>
                                    <a href="{{ route('instances.edit', $instance->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('instances.destroy', $instance->id) }}" method="POST" style="display:inline;">
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
