<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.getenv('DBMYSQLHOST').';dbname='.getenv('DBNAME'),
    'username' => getenv('DBUSERNAME'),
    'password' => getenv('DBPASSWORD'),
    'charset' => getenv('DBCHARSET'),

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
