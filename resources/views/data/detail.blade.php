
<!-- Modal -->
<div class="modal fade" id="userDetailModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailModalLabel">User Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ asset($user->userBio->profile_pict_path) }}" alt="Profile Picture" class="img-fluid">
                    </div>
                    <div class="col-md-8">
                        <h5>{{ $user->userBio->full_name }}</h5>
                        <p>Nickname: {{ $user->userBio->nickname }}</p>
                        <p>City: {{ $user->userBio->city }}</p>
                        <p>Address: {{ $user->userBio->address }}</p>
                        <p>Age: {{ $user->userBio->age }}</p>
                        <p>Blood Type: {{ $user->userBio->blood_type }}</p>
                        <p>Height: {{ $user->userBio->height }}</p>
                        <p>Weight: {{ $user->userBio->weight }}</p>
                        <p>Phone Number: {{ $user->userBio->phone_number }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
