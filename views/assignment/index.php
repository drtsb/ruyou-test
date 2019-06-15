<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = 'Назначения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'username',
                    //'email',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update}',
                    ],
                ],
            ]); ?>  
        </div>
    </div>


</div>
