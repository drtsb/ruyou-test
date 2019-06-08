<?php

use yii\helpers\Html;
use yii\grid\GridView;

use kartik\date\DatePicker;

use app\models\Request;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'updated_at',
            'name',
            'message:ntext',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'pickerButton' => false,
                    'options' => [ 'autocomplete' => 'nope', ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
            ],
            [
                'attribute' => 'option',
                'value' => 'optionName',
                'filter' => Request::getOptions(),
            ],
            [
                'attribute' => 'image',
                'format' => 'image',
                'filter' => false, 
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'visibleButtons' => [
                    'update' => Yii::$app->user->can('admin'),
                    'delete' => Yii::$app->user->can('admin'),
                ],
            ],
        ],
    ]); ?>


</div>
