<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AssignmentForm */
/* @var $user app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Пользователь \'' . $user->username . '\': назначить типы доступа';
$this->params['breadcrumbs'][] = ['label' => 'Назначения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assign-update">

    <h1><?= Html::encode($this->title) ?></h1>

	<div class="role-form">

	    <?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-sm-4 col-xs-12">
				<?= $form->field($model, 'roles')->listBox($roles, ['multiple' => true, 'size' => 10]) ?>	
			</div>
		</div>

	    <div class="form-group">
	        <?= Html::submitButton('Назначить', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
