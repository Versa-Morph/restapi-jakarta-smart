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
                        <h3 class="mb-0">Queue</h3>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incidents as $incident)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $incident->incident_number }}</td>
                                <td>{{ $incident->caller->username }}</td>
                                <td>{{ $incident->responder->username }}</td>
                                <td>
                                    <span class="badge badge-warning">&nbsp;</span> {{ $incident->status }}
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
