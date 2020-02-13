<?php

use macgyer\yii2materializecss\widgets\form\DatePicker;
use macgyer\yii2materializecss\widgets\grid\ActionColumn;
use macgyer\yii2materializecss\widgets\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\forms\DatumsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Datums';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="datums-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Datums', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Reset filter', ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'name',
            [
                'attribute' => 'created_at',
                'filter' => DatePicker::widget(['name' => "date"]),
                'format' => 'datetime'
            ],

            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to([$action, 'key' => $model->code]);
                }
            ],
        ],
    ]); ?>


</div>
