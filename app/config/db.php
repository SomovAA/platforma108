<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host=' . getenv('APP_DB_HOST') . ';port=' . getenv('APP_DB_PORT') . ';dbname=' . getenv('APP_DB_NAME'),
    'username' => getenv('APP_DB_USER'),
    'password' => getenv('APP_DB_PASSWORD'),
];
