<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    Pusher.logToConsole = true;

    var pusher = new Pusher('cfa52e3e9cfd17d0f3ec', {
        cluster: 'ap1',
        authEndpoint: "{{ route('broadcastingAuth') }}",
        auth: {
            headers: {
                'X-CSRF-Token': "{{ csrf_token() }}",
            },
        },
    });

    var channel = pusher.subscribe('private-App.Models.User.{{ Auth::user()->id }}');


    channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function(data) {

        var html = '<a class="dropdown-item" href="" style="white-space: normal">' +
            '<small>' +
            '<b>' +
            data.jenis +
            data.created_at +
            '</b>' +
            '</small>' +
            '<br>' +
            data.message +
            '<p></p>' +
            '</a>';

        $('.tidak-ada-notifikasi').removeClass('show');
        $('#dropdown-menu').prepend(html);


    });
</script>
