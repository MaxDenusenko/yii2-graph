<?php

use app\core\forms\DatumFileForm;
use macgyer\yii2materializecss\widgets\data\DetailView;
use macgyer\yii2materializecss\widgets\form\ActiveForm;
use macgyer\yii2materializecss\widgets\grid\ActionColumn;
use macgyer\yii2materializecss\widgets\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model \app\core\models\Datums */
/* @var $filesForm DatumFileForm */
/* @var $filesDataProvider ActiveDataProvider */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Datums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="datums-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if (count($model->files)) : ?>
        <?= Html::a('Run', ['graph', 'key' => $model->code], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?= Html::a('Update', ['update', 'key' => $model->code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'key' => $model->code], [
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

            'name',
            'created_at:date',
            'code',
        ],
    ]) ?>

    <br>
    <br>
    <br>
    <?= GridView::widget([
        'dataProvider' => $filesDataProvider,
        'columns' => [
            'file',
            [
                'class' => ActionColumn::class,
                'controller' => 'files',
                'template' => '{update}{delete}',
            ],
        ],
    ]); ?>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'options' => ['enctype'=>'multipart/form-data'],
    ]); ?>


    <?= $form->field($filesForm, 'files[]')->label(false)->fileInput(['multiple' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton('Upload', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
