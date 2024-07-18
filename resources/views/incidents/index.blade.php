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
                        <h3 class="mb-0">Incidents</h3>
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
                                <th width="12%">Actions</th>
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
                                    @if($incident->status == 'processed')
                                        <span class="badge badge-warning">&nbsp;</span> processed
                                    @elseif($incident->status == 'completed')
                                        <span class="badge badge-success">&nbsp;</span> completed
                                    @endif
                                </td>
                                @if(Auth::user()->role != 'admin')
                                <td>
                                    @if($incident->status != 'completed' && $incident->status == 'processed')
                                        <button class="btn btn-success complete-btn" data-id="{{ $incident->id }}" data-url="{{ route('incidents.complete', $incident->id) }}">Complete</button>
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
    const completeButtons = document.querySelectorAll('.complete-btn');

    completeButtons.forEach(button => {
        button.addEventListener('click', function () {
            const incidentId = this.getAttribute('data-id');
            const url = this.getAttribute('data-url');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to complete this incident?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, complete it!'
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
                        if (data.message === 'Incident completed successfully') {
                            Swal.fire(
                                'Completed!',
                                'The incident has been completed.',
                                'success'
                            ).then(() => {
                                location.reload(); // Refresh the page
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message,
                                'error'
                            )
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An error occurred while completing the incident.',
                            'error'
                        )
                        console.error('Error:', error);
                    });
                }
            });
        });
    });
});
</script>
@endpush
