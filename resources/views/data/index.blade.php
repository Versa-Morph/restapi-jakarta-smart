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
                        <h3 class="mb-0">Data Pengguna</h3>
                    </div>
                </div>
                <div class="card-body">
                    @include('components.alert')
                    <table id="data-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="8%">Avatar</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th width="10%">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ asset($user->userBio->profile_pict_path ?? '') }}" alt="Profile Picture" class="img-fluid" width="60" height="60" style="border-radius: 50%;">
                                </td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#userDetailModal-{{ $user->id }}" data-user-id="{{ $user->id }}">Detail</button>
                                    @include('data.detail')
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

@push('scripts')
<script>
$(document).ready(function() {
    $('#userDetailModal').on('show.bs.modal', function (event) {
        console.log('masuk');
        var button = $(event.relatedTarget); // Button that triggered the modal
        var userId = button.data('user-id'); // Extract info from data-* attributes
        var modal = $(this);

        // Clear previous content
        modal.find('#userDetailContent').html('');

        // AJAX request to fetch user detail
        $.ajax({
            url: '/data/users/' + userId + '/detail',
            method: 'GET',
            success: function(data) {
                modal.find('#userDetailContent').html(data);
                console.log(data);
            },
            error: function() {
                console.log('error');
                modal.find('#userDetailContent').html('<p>An error occurred while fetching the user details.</p>');
            }
        });
    });
});
</script>
@endpush
