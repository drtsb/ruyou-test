<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RoleForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Типы доступа', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="role-create">

    <h1><?= Html::encode($this->title) ?></h1>

	<div class="role-form">

	    <?php $form = ActiveForm::begin(); ?>

    	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

	    <div class="form-group">
	        <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
