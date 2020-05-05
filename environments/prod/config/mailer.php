<?php

return [
    'class' => 'yii\swiftmailer\Mailer',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => false,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        // Mail Server Info
        'host'=> 'your.smtp-server.com',
        'username'=> 'noreply@smtp-server.com',
        'password'=> 'password',
        'port'=>'465',
        'encryption'=>'ssl',
        'streamOptions' => [
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false
            ]
        ],
    ],
];