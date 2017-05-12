<?php

class APNSComponent extends Component {
    private $tag = 'apns';

    public function sendPushMessage($deviceToken, $config = array(), $extra = array()) {
        /**
         * APNS settings
         * @var array (
         *		'gateway' : APNS gateway (gateway.push.apple.com | gateway.sandbox.push.apple.com),
         *		'cert' : certificate file name
         *		'passphrase' : certificate passphrase
         * )
         */
        $settings = array (
            'gateway' => 'ssl://gateway.sandbox.push.apple.com:2195',
            'cert' => 'Babymap_Push.pem',
            'passphrase' => '163182776',
            'cafile' => 'entrust_2048_ca.cer'
        );
        if( !isset($config['message']) ) {
            return false;
        }

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->$settings['cert']);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->$settings['passphrase']);
        stream_context_set_option($ctx, 'ssl', 'cafile', $this->$settings['cafile']);

        // Open a connection to the APNS server
        $fp = stream_socket_client( $this->config['gateway'], $err, $errstr, 60,
            STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp) {
            $this->log( "Failed to connect: $err $errstr", $this->tag );
            throw new Exception("Failed to connect: $err $errstr" . PHP_EOL);
        }

        $this->log( 'Connected to APNS.', $this->tag );

        $this->log( 'Sending message to device: ' . $deviceToken, $this->tag );

        // Create the payload body
        $body['aps'] = array(
            'alert' => $config['message'],
            'sound' => isset($config['sound']) ? $config['sound'] : 'default',
            'badge' => isset($config['badge']) ? $config['badge'] : 0
        );

        if( is_array($extra) ) {
            $body['extra'] = $extra;
        }

        // Encode the payload as JSON
        $payload = json_encode($body);

        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        if (!$result) {
            $this->log( 'Message not delivered.', $this->tag );
        }
        else {
            $this->log( 'Message successfully delivered.', $this->tag);
        }

        // Close the connection to the server
        fclose($fp);

        return $result;

    }

}