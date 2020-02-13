<?php

use macgyer\yii2materializecss\widgets\grid\GridView;
use macgyer\yii2materializecss\widgets\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\forms\FilesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Files';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="files-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a('Reset filter', ['index'], ['class' => 'btn btn-success']) ?></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'datum_id',
                'filter' => $searchModel->datumList(),
                'value' => 'datum.name',
            ],
            'file',

            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to([$action, 'key' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
