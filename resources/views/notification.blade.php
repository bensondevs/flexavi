<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('120e24243003ba638319', {
            cluster: 'ap1',
            forceTLS: true,
            encrypted: true,
            authorizer: (channel, options) => {
                return {
                    authorize: (socketId, callback) => {
                        axios.post('https://api-canary.daksoftware.nl/broadcasting/auth', {
                            socket_id: socketId,
                            channel_name: channel.name,
                        }, {
                            headers: {
                                Authorization: 'Bearer 10|Rd6Ch4UQvXJ9JN1xUYPpDvgGkCa7gvNDYKYBcH4Q'
                            }
                        })
                            .then(response => {
                                callback(null, response.data);
                            })
                            .catch(error => {
                                callback(error);
                            });
                    }
                };
            },
        });

        var channel = pusher.subscribe('private-notification.6a905620-6030-11ed-b716-4743d43dad79');
        channel.bind('notification.created', function (data) {
            alert(JSON.stringify(data));
        });
    </script>
</head>
<body>
<h1>Pusher Test</h1>
<p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
</p>
<script>

</script>
</body>
