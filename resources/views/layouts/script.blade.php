<script src="{{ asset('dist/scripts/plugins.js?v=2') }}"></script>
{{-- <script src="{{ asset('dist/scripts/summernote-lite.min.js') }}"></script> --}}
{{-- <script src="plugins/marquee/js/marquee.js"></script> --}}
{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/datatables.min.js"></script> --}}
{{-- <script src="{{ asset('dist/scripts/bootstrap.bundle.min.js') }}"></script> --}}
{{-- <script src="{{ asset('dist/scripts/bootstrap-select.min.js') }}"></script> --}}
<script src="{{ asset('dist/scripts/summernote-lite.min.js') }}"></script>
<script src="{{ asset('dist/scripts/main.js?v=2') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('focus', '.form-control', function() {
        $(this).removeClass('is-invalid');
    })

    function overlay(state = false) {
        if (state) {
            $('#loading').show();
        } else {
            $('#loading').hide();
        }
    }
</script>
