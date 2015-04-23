<?php

use kato\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var scorchsoft\scorchcore\modules\redirect\models\Redirect $model
 */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Redirects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redirects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'content_id',
            'content_type',
            'old_url:url',
            'new_url:url',
            'tags:ntext',
            'create_time',
            'update_time',
            'active',
            'deleted',
        ],
    ]) ?>

</div>
