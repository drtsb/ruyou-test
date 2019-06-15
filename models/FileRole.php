<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%file_role}}".
 *
 * @property int $file_id
 * @property string $role
 */
class FileRole extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file_role}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file_id'], 'integer'],
            [['role'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file_id' => 'File ID',
            'role' => 'Role',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getRolesByFileId($file_id)
    {
        return self::find()
            ->select(['role'])
            ->where(['file_id' => $file_id])
            ->asArray()
            ->column();
    }

}
