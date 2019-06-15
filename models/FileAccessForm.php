<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\Security;

/**
 * FileAccessForm is the model behind the file access form.
 */
class FileAccessForm extends Model
{

    public $roles = [];
    public $file_id;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file_id'], 'integer'],
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
            'file_id' => 'File',
        ];
    }

    /**
     * @return bool whether the roles are saved
     */
    public function save()
    {
        if ($this->validate()) {
            FileRole::deleteAll(['file_id' => $this->file_id]);
            if (is_array($this->roles)) {
                foreach ($this->roles as $role) {
                    $fr = new FileRole(['file_id' => $this->file_id, 'role' => $role]);
                    $fr->save();
                }
            }
            return true;
        } else {
            return false;
        }
    }  

}
