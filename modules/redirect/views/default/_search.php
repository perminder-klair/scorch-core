<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var kato\web\View $this
 * @var scorchsoft\scorchcore\modules\redirect\models\search\RedirectSearch $model
 * @var yii\bootstrap\ActiveForm $form
 */
?>

<div class="redirects-search">

    <?php $form = ActiveForm::begin([
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'old_url') ?>

    <?= $form->field($model, 'new_url') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
