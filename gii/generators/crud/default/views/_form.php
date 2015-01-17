<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * @var yii\web\View $this
 * @var yii\gii\generators\crud\Generator $generator
 */

/** @var \yii\db\ActiveRecord $model */
$model = new $generator->modelClass;
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kato\modules\tag\models\Tag;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use yii\imperavi\Widget as ImperaviWidget;
use kartik\widgets\SwitchInput;
use kato\modules\media\widgets\Media;

$tag = new Tag;

/**
* @var kato\web\View $this
* @var <?= ltrim($generator->modelClass, '\\') ?> $model
* @var yii\bootstrap\ActiveForm $form
*/
?>

<div class="row">
    <div class="col-lg-12">
        <?= "<?php " ?>$form = ActiveForm::begin([
            'layout' => 'horizontal',
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-4',
                    'offset' => 'col-sm-offset-4',
                    'wrapper' => 'col-sm-8',
                    'error' => '',
                    'hint' => '',
                ],
            ],
        ]); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#info" data-toggle="tab">Basic Info</a></li>
                        <li><a href="#media" data-toggle="tab">Media</a></li>
                    </ul>
                </div>
                <div class="panel-body">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active <?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form" id="info">

    <?php foreach ($safeAttributes as $attribute) {
    echo "              <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
    } ?>

                        </div>
                        <div class="tab-pane fade in" id="media">

                            <?= "<?= " ?>Media::widget([
                            'model' => $model,
                            ]); ?>

                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <?= "<?= " ?>Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success col-sm-offset-2' : 'btn btn-primary col-sm-offset-2']) ?>
                </div>
            </div>
        <?= "<?php " ?>ActiveForm::end(); ?>
    </div>
</div>
