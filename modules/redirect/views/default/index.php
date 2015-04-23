<?php

use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var kato\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var scorchsoft\scorchcore\modules\redirect\models\search\RedirectSearch $searchModel
 */

$this->title = 'Redirects';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="redirects-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'content_id',
            'content_type',
            'old_url:url',
            'new_url:url',
            // 'tags:ntext',
            // 'create_time',
            // 'update_time',
            // 'active',
            // 'deleted',

            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        ],
    ]); ?>

</div>
