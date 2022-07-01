<script src="{{ asset('dist/scripts/plugins.js?v=2') }}"></script>
{{-- <script src="{{ asset('dist/scripts/summernote-lite.min.js') }}"></script> --}}
{{-- <script src="plugins/marquee/js/marquee.js"></script> --}}
{{-- <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.6.0/datatables.min.js"></script> --}}
{{-- <script src="{{ asset('dist/scripts/bootstrap.bundle.min.js') }}"></script> --}}
{{-- <script src="{{ asset('dist/scripts/bootstrap-select.min.js') }}"></script> --}}
<script src="{{ asset('dist/scripts/summernote-lite.min.js') }}"></script>
<script src="{{ asset('dist/scripts/main.js?v=2') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"
    integrity="sha512-Rdk63VC+1UYzGSgd3u2iadi0joUrcwX0IWp2rTh6KXFoAmgOjRS99Vynz1lJPT8dLjvo6JZOqpAHJyfCEZ5KoA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/accounting.js/0.1/accounting.min.js"
    integrity="sha512-LLsvn7RXQa0J/E40ChF/6YAf2V9PJuLGG1VeuZhMlWp+2yAKj98A1Q1lsChkM9niWqY0gCkvHvpzqQOFEfpxIw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
