<?php

return [
    'class' => 'yii\swiftmailer\Mailer',
    // send all mails to a file by default. You have to set
    // 'useFileTransport' to false and configure a transport
    // for the mailer to send real emails.
    'useFileTransport' => true,
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        // Mailtrap
        'host'=> 'smtp.mailtrap.io',
        'username'=> 'xxxxxx',
        'password'=> 'password',
        'port'=>'2525',
        'encryption'=>'tls',
    ],
];