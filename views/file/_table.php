<?php

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
        'name',

        [
            'class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => function($model) { return !$model->isFile(); },
                //'update' => function($model) { return !$model->isFile(); },
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>