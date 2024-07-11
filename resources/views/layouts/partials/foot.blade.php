
<!-- Scripts -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.j') }}s"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('assets/js/raphael.min.js') }}"></script>
<script src="{{ asset('assets/js/morris.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-jvectormap-world-mill.min.js') }}"></script>
<script src="{{ asset('assets/js/horizontal-timeline.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.steps.min.js') }}"></script>
<script src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script src="{{ asset('assets/js/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Page Level Scripts -->

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        })
    }
</script>
