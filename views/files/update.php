<?php

use app\core\models\Files;
use app\core\forms\EditFileForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Files */
/* @var $form \app\core\forms\EditFileForm */

$this->title = 'Update Files: ' . $model->file;
$this->params['breadcrumbs'][] = ['label' =>  $model->datum->name, 'url' => ['datums/view', 'key' =>  $model->datum->code]];
$this->params['breadcrumbs'][] = ['label' => 'Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->file, 'url' => ['view', 'key' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<p><?= Html::a('View Datum', ['datums/view', 'key' => $model->datum->code], ['class' => 'btn btn-primary']) ?></p>

<div class="files-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'form' => $form,
    ]) ?>

</div>
