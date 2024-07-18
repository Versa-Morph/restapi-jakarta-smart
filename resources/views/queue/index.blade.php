@extends('layouts.app')

@section('breadcumbs')
@endsection

@section('content')
<section class="main--content">
    <div class="row gutter-20">
        <div class="col-12">
            <div class="card" style="border-radius: 20px;">
                <div class="card-header p-3" style="border-radius: 20px 20px 0 0;">
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
                                <th>Datetime</th>
                                <th>Incident Number</th>
                                <th>Caller</th>
                                <th>Responder</th>
                                <th>Status</th>
                                @if(Auth::user()->role != 'admin')
                                <th width="10%">action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incidents as $incident)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $incident->created_at->format('d-m-Y H:i:s') }}</td>
                                <td>{{ $incident->incident_number }}</td>
                                <td>{{ $incident->caller->username }}</td>
                                <td>{{ $incident->responder->username ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="badge badge-warning">&nbsp;</span> {{ $incident->status }}
                                </td>
                                @if(Auth::user()->role != 'admin')
                                <td>
                                    @if ($incident->status == 'requested')
                                        <button class="btn btn-success accept-btn" data-id="{{ $incident->id }}" data-url="{{ route('queue.accept', $incident->id) }}">Accept</button>
                                    @endif
                                </td>
                                @endif
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
@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const acceptButtons = document.querySelectorAll('.accept-btn');

    acceptButtons.forEach(button => {
        button.addEventListener('click', function () {
            const incidentId = this.getAttribute('data-id');
            const url = this.getAttribute('data-url');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to accept this incident?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, accept it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${url}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'Incident accepted successfully') {
                            Swal.fire(
                                'Accepted!',
                                'The incident has been accepted.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            ).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An error occurred while accepting the incident.',
                            'error'
                        ).then(() => {
                            location.reload();
                        });
                        console.error('Error:', error);
                    });
                }
            });
        });
    });
});
</script>
@endpush
