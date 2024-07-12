@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<!-- Main Content Start -->
<section class="main--content">
    <div class="row gutter-20">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 p-0">Data Agencies</h3>
                        <a href="{{ route('agencies.index') }}" class="btn btn-danger">Back</a>
                    </div>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset($agency->icon) }}" width="150" alt="" class="d-block mx-auto" style="border-radius: 50%;">
                    <h3>{{ $agency->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="m-0 p-0">Detail Agencies</h3>
                        <a href="{{ route('agency-details.create', $agency) }}" class="btn btn-primary">Create Data</a>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.alert')
                    <table id="agencies-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="8%">Logo</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Longitude</th>
                                <th>Latitude</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agencyDetails as $agencyDetail)
                            {{-- {{ dd($agencyDetail) }} --}}
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img src="{{ asset($agencyDetail->logo) }}" width="50" alt="" class="d-block mx-auto" style="border-radius: 50%;"></td>
                                <td>{{ $agencyDetail->name }}</td>
                                <td>{{ $agencyDetail->address }}</td>
                                <td>{{ $agencyDetail->longitude }}</td>
                                <td>{{ $agencyDetail->latitude }}</td>
                                <td>
                                    <a href="{{ route('agency-details.edit', [$agency, $agencyDetail->id]) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('agency-details.destroy', [$agency, $agencyDetail->id]) }}" method="POST" style="display:inline;">
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
