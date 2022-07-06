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

        console.log(data);
        if (data.url.includes("?")) {
            var url = data.url + '&notification_id=' + data.id;
        } else {
            var url = data.url + '?notification_id=' + data.id;
        }

        var html =
            '<a class="dropdown-item" href="' + url + '"' +
            'style="white-space: normal">' +
            '<span>' +
            '<b> ' + data.jenis + '</b>' +
            '<small>' +
            data.created_at +
            '</small>' +
            '</span>' +
            '<br>' +
            '<p>' + data.message + '</p>' +
            '</a>';

        $('.tidak-ada-notifikasi').removeClass('show');
        $('#dropdown-menu').prepend(html);
    });
</script>
