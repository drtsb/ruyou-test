<?php

use yii\web\View;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

use app\widgets\Alert;

?>

<?php Pjax::begin(['id' => 'my_pjax']); ?>

<?= Alert::widget() ?>

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
            'template' => '{update} {access} {delete}',
            'visibleButtons' => [
                'access' => function($model) { return (!$model->isFile() && Yii::$app->user->can('admin')); },
            ],
            'buttons' => [
                'access' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-user"></span>', $url);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'data-pjax' => 'my_pjax',
                    ]);
                }
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>