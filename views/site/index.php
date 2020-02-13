<?php

/* @var $this yii\web\View */

$this->title = Yii::$app->name;

use yii\helpers\Url;
?>

<div class="container">
    <br><br>
    <h1 class="header center blue-text"><a href="https://www.chartjs.org"><?=Yii::$app->name;?></a></h1>
    <div class="row center">
        <h5 class="header col s12 light"><?=Yii::$app->params['appDescription'];?></h5>
    </div>
    <div class="row center">
        <a href="<?= Url::to(['/datums'])?>" id="download-button" class="btn-large waves-effect waves-light blue darken-4">Get Started</a>
    </div>
    <br><br>

</div>
