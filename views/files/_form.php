<?php

use app\core\models\Files;
use app\core\forms\EditFileForm;
use macgyer\yii2materializecss\widgets\form\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model Files */
/* @var $activeForm yii\widgets\ActiveForm */
/* @var $form EditFileForm */
?>

<div class="files-form">

    <?php $activeForm = ActiveForm::begin(); ?>

    <?= $activeForm->field($form, 'file')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'File name']) ?>

    <?= $activeForm->field($form->settings, 'firstDataIndex')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter column index for date and time data. Count start from 1.
         Sample data: '.date('Y.m.d h:i:s').'. Date format - datetime']) ?>
    <?= $activeForm->field($form->settings, 'secondDataIndex')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter column index for balance data. Count start from 1.
         Sample data: 8.16 or -5.78']) ?>
    <?= $activeForm->field($form->settings, 'labelRowIndex')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter row index for label data. Count start from 1.
         Sample data: Item or Commission']) ?>
    <?= $activeForm->field($form->settings, 'graphName')->textInput(['maxlength' => true]) ?>
    <?= $activeForm->field($form->settings, 'balance')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter your starting balance.
         Sample data: 120']) ?>
    <?= $activeForm->field($form->settings, 'skipTop')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter the number of lines to skip at the beginning.']) ?>
    <?= $activeForm->field($form->settings, 'skipDown')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter the number of lines to skip at the end.']) ?>
    <?= $activeForm->field($form->settings, 'maxElement')->textInput(['maxlength' => true, 'class' => 'tooltipped',
        'data-tooltip' => 'Enter the maximum number of items to display.']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
