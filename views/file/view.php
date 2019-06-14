<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\File */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Files', 'url' => ['index']];
$this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'], $model->breadcrumbs);
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="file-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-arrow-up"></i> Вверх', $model->parentUrl, ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Создать', ['create', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Все вложенные файлы будут удалены, Вы действительно хотите удалить данную папку?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= $this->render('_table', [
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
