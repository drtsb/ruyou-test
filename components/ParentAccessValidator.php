<?php

namespace app\components;

use Yii;
use yii\validators\Validator;

use app\models\File;

class ParentAccessValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
    	// в корень доступ разрешен
        if (empty($model->$attribute) || Yii::$app->user->can(Yii::$app->params['role-name-admin'])) { return true; }

        $parent = File::findOne($model->$attribute);

        if (!$parent->checkUserAccess(Yii::$app->user->identity->id)) {
        	$this->addError($model, $attribute, 'У Вас недостаточно прав для доступа в эту папку.');
        }
    }
}