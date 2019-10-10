<?php

use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

Modal::begin([
    'header' => '<h4>съесть процент</h4>',
    'id' => 'eat-apple-modal',
    'size' => 'modal-sm'
]);
?>
    <div id='eat-apple-modal-content'>
        <?php $form = ActiveForm::begin(['action' => 'apple/eat-apple']); ?>
        <div class="form-group">
            <select name="size" id="eat-percentage" class="form-control"></select>
        </div>
        <input name="id" type="hidden" value="" id="apple-id">
        <div class="form-group">
            <?= Html::submitButton('съесть', ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php Modal::end();