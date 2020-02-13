<?php

use app\core\models\Datums;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Datums */

$this->title = 'Create Datums';
$this->params['breadcrumbs'][] = ['label' => 'Datums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="datums-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
