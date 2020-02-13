<?php
require __DIR__ . '/../vendor/autoload.php';

$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__. '/..');
$dotEnv->load();

define('YII_DEBUG', getenv('YII_DEBUG'));
define('YII_ENV', getenv('YII_ENV'));

require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
