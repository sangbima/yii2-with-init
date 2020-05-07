<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use app\widgets\SwitchButton;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password_hash')->passwordInput(['maxlength' => true]) ?>

    <?php
    // $form->field($model, 'status', [
    //     'checkTemplate' => '<div class="custom-control custom-switch">{input}{label}</div>'
    // ])->checkbox([
    //     'class' => 'custom-control-input'
    // ]) 
    ?>

    <?= $form->field($model, 'status')->widget(SwitchButton::class) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>