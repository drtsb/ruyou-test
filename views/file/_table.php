<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>

<?php Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        //'parent_id',
        [
            'attribute' => 'type',
            'format' => 'raw',
            'label' => false,
            'value' => function($model){
                return $model->isFile() ? 
                    '<i class="glyphicon glyphicon-file"></i>' :
                    '<i class="glyphicon glyphicon-folder-close"></i>';
            },
        ],
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function($model){
                return $model->isFile() ? 
                    $model->name :
                    Html::a($model->name, ['view', 'id' => $model->primaryKey], ['data-pjax'=>"0"]);
            },
        ],

        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update} {delete}',
            'visibleButtons' => [
                //'view' => function($model) { return !$model->isFile(); },
                //'update' => function($model) { return !$model->isFile(); },
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>