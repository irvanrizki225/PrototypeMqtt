@php
    use PhpMqtt\Client\Facades\MQTT;
    use PhpMqtt\Client\Exceptions\MqttClientException;
    use PhpMqtt\Client\ConnectionSettings;
    use PhpMqtt\Client\MqttClient;
    // import laravel log
    use Illuminate\Support\Facades\Log;
    use App\Models\Device;
    use App\Models\Setting;

    $setting = Setting::first();


@endphp
<!DOCTYPE html>
<html>
<head>
    {{-- <title>Bootstrap 5 Message Template</title> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    @php

        ini_set('max_execution_time', 7);
        $topics = [];
        $msg = [];
        $time = [];
        // $mqttdata = Device::all();

        $data = [
            'topic' => 'testtopic/testOne',
            'message' => 'Hello world! TEST',
            'time' => date('Y-m-d H:i:s')
        ];

        // Array to JSON
        $message = json_encode($data,JSON_UNESCAPED_SLASHES);
        // $message = implode('', $json);
        // dd($message);

        try {
            $mqtt = new MqttClient(env('MQTT_HOST'), env('MQTT_PORT'), env('MQTT_CLIENT_ID'));
            $ConnectionSettings = new ConnectionSettings();
            $ConnectionSettings->setUsername($setting->username);
            $ConnectionSettings->setPassword($setting->password);

            $mqtt->connect($ConnectionSettings);

            // Convert JSON back to string


            // Publish with the string message
            // $mqtt->publish('testtopic/testOne', $message, 1);
            // $mqtt->subscribe('testtopic/testOne', function ($topic, $message) {
            //     $data = [
            //         'name' => 'Device 1',
            //         'topic' => $topic,
            //         'message' => $message,
            //         'time' => date('Y-m-d H:i:s')
            //     ];
            //     Device::create($data);
            // },1);
            // $mqtt->loop(true);

            $mqtt->disconnect();
        } catch (MqttClientException $e) {
            echo 'MQTT Error: ' . $e->getMessage();
        }

        $datas = Device::all();


    @endphp



    <div class="container">
        <h1>MQTT Message</h1>
        <form action="" method="POST">
            @csrf
            <div class="mb-3">
                <label for="topic" class="form-label">Topic</label>
                <input type="text" class="form-control" id="topic" name="topic">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <input type="text" class="form-control" id="message" name="message">
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>

        <h1>MQTT List Message</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Topic</th>
                    <th>Message</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $key => $data)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $data['topic'] }}</td>
                        <td>{{ $data['message'] }}</td>
                        <td>{{ $data['created_at'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- received message from mqtt broker message --}}
        <h1>Received Message</h1>
        <div class="row"></div>
            <div class="col-md-12">
                {{-- list message --}}
                <ul>
                    <li id="received-message"></li>
                </ul>
            </div>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mqtt/5.5.4/mqtt.min.js" integrity="sha512-YHJvScsodz3xyg1YSiH4gm8+S9KecWaIz9zg7894+izZ32iQUaVKxLIiKOndSvoDKbJxDdhhl2VRLWAkOwT5/Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Create a client instance
        const url = 'ws://broker.emqx.io:8083/mqtt'; // Change port if necessary
        const options = {
            clientId: 'proto-type-mqtt' + Math.random().toString(16).substr(2, 8),
            username: 'test',
            password: 'test',
            clean: true,
        };


        const client = mqtt.connect(url, options);

        client.on('connect', () => {
            console.log('Connected to MQTT broker');
            // Subscribe to a topic
            client.subscribe('testtopic/testOne', { qos: 1 }, (err) => {
                if (!err) {
                    console.log('Subscribed to testtopic/testOne');
                }
            });

            // Publish a message after successful connection
            client.publish('testtopic/testOne', 'Hello MQTT DARI BROSER', { qos: 1 });
        });

        client.on('message', (topic, message) => {
            // Received message from subscribed topic
            console.log('Received message:', message.toString());
            document.getElementById('received-message').innerHTML = message.toString();
        });

        client.on('error', (err) => {
            // Handle MQTT client errors
            console.log('MQTT client error:', err);
        });

        client.on('close', () => {
            console.log('Disconnected from MQTT broker');
        });
    </script>

</body>
</html>
