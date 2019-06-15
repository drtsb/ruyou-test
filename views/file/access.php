<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $file app\models\File */
/* @var $model app\models\FileAccessForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Доступ к папке: ' . $file->name;
$this->params['breadcrumbs'][] = ['label' => 'Files', 'url' => ['index']];
$this->params['breadcrumbs'] = array_merge($this->params['breadcrumbs'], $file->breadcrumbs);
$this->params['breadcrumbs'][] = ['label' => $file->name, 'url' => ['view', 'id' => $file->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="file-access">

    <h1><?= Html::encode($this->title) ?></h1>

	<div class="file-access-form">

	    <?php $form = ActiveForm::begin(); ?>

		<div class="row">
			<div class="col-sm-4 col-xs-12">
				<?= $form->field($model, 'roles')->listBox($roles, ['multiple' => true, 'size' => 10]) ?>	
			</div>
		</div>

	    <div class="form-group">
	        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
	    </div>

	    <?php ActiveForm::end(); ?>

	</div>

</div>
