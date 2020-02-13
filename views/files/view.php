<?php

use app\core\models\Files;
use app\core\models\FileSettings;
use yii\helpers\Html;
use macgyer\yii2materializecss\widgets\data\DetailView;

/* @var $this yii\web\View */
/* @var $model Files */

$this->title = $model->file;
$this->params['breadcrumbs'][] = ['label' =>  $model->datum->name, 'url' => ['datums/view', 'key' =>  $model->datum->code]];
$this->params['breadcrumbs'][] = ['label' => 'Files', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="files-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('View Datum', ['datums/view', 'key' => $model->datum->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Run Datum', ['datums/graph', 'key' => $model->datum->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'datum.name',
            'file',

            'fileSettings.0.firstDataIndex',
            'fileSettings.0.secondDataIndex',
            'fileSettings.0.labelRowIndex',
            'fileSettings.0.graphName',
            'fileSettings.0.balance',
            'fileSettings.0.skipTop',
            'fileSettings.0.skipDown',
            'fileSettings.0.maxElement',
        ],
    ]) ?>

</div>
