<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'RuYou test';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="body-content">

        <?= Html::a('Создать заявку', ['request/create'], ['class'=>'btn btn-success']) ?>

    </div>
</div>
