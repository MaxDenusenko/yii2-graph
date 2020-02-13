<?php

use app\core\models\Datums;
use macgyer\yii2materializecss\widgets\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Datums */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="datums-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
