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
                        <h3>Incidents</h3>
                        <a href="{{ route('incidents.create') }}" class="btn btn-primary">Create Data</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.alert')
                    <table id="incidents-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Incident Number</th>
                                <th>Caller</th>
                                <th>Responder</th>
                                <th>Status</th>
                                {{-- <th width="20%">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incidents as $incident)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $incident->incident_number }}</td>
                                <td>{{ $incident->caller }}</td>
                                <td>{{ $incident->responder }}</td>
                                <td>{{ $incident->status }}</td>
                                {{-- <td>
                                    <a href="{{ route('incidents.show', $incident->id) }}" class="btn btn-info">View</a>
                                    <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('incidents.destroy', $incident->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td> --}}
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
