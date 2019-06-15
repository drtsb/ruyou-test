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
                //'view' => function($model) { return !$model->isFile(); },
                //'update' => function($model) { return !$model->isFile(); },
                'access' => function($model) { return (!$model->isFile() && Yii::$app->user->can('admin')); },
            ],
            'buttons' => [
                'access' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-user"></span>', $url);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        //'class' => 'pjax-delete-link',
                        //'delete-url' => $url,
                        //'pjax-container' => 'my_pjax',
                        //'title' => Yii::t('yii', 'Delete')
                        'data-pjax' => 'my_pjax',
                    ]);
                }
            ],
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

<?php

$script = <<< JS
$(document).on('ready pjax:success', function() {
    $('.pjax-delete-link').on('click', function(e) {
        console.log('click');
        e.preventDefault();
        var deleteUrl = $(this).attr('delete-url');
        var pjaxContainer = $(this).attr('pjax-container');
        var result = confirm('Delete this item, are you sure?');                                
        if(result) {
            $.ajax({
                url: deleteUrl,
                type: 'post',
                error: function(xhr, status, error) {
                    alert('There was an error with your request.' + xhr.responseText);
                }
            }).done(function(data) {
                $.pjax.reload('#' + $.trim(pjaxContainer), {timeout: 3000});
            });
        }
    });
});
JS;

/*$this->registerJs(
    $script
);*/

?>