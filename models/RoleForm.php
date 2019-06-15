<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * RoleForm is the model behind the role form.
 */
class RoleForm extends Model
{
    public $name;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
        ];
    }

    /**
     * @return bool whether the model passes validation
     */
    public function save()
    {
        if ($this->validate()) {
            $auth = Yii::$app->authManager;
            $role = $auth->createRole($this->name);
            $auth->add($role);
            $auth->addChild($role, $auth->getPermission('accessFile'));
            return true;
        } else {
            return false;
        }
    }

}
