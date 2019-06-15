<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\Security;

/**
 * AssignmentForm is the model behind the user assignments form.
 */
class AssignmentForm extends Model
{

    public $roles = [];
    public $user_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['roles'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'roles' => 'Роли',
            'user_id' => 'Пользователь',
        ];
    }

    /**
     * @return bool whether the roles are saved
     */
    public function save()
    {
        if ($this->validate()) {
            Yii::$app->authManager->revokeAll($this->user_id);
            if (is_array($this->roles)) {
                foreach ($this->roles as $roleName) {
                    $role = Yii::$app->authManager->getRole($roleName);
                    Yii::$app->authManager->assign($role, $this->user_id);
                }
            }
            return true;
        } else {
            return false;
        }
    }  

}
