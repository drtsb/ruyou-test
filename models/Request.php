<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%request}}".
 *
 * @property int $id
 * @property int $created_at
 * @property int $updated_at
 * @property string $name
 * @property string $message
 * @property string $image
 * @property int $option
 */
class Request extends \yii\db\ActiveRecord
{

    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%request}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'message'], 'required'],
            [['created_at', 'updated_at', 'option'], 'integer'],
            [['message'], 'string'],
            [['name', 'image'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Время заявки',
            'updated_at' => 'Updated At',
            'name' => 'ФИО',
            'message' => 'Сообщение',
            'image' => 'Изображение',
            'option' => 'Опция',
            'optionName' => 'Опция',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * @return string option name.
     */
    public function getOptionName(){
        return ArrayHelper::getValue(self::getOptions(), $this->option);
    }

    /**
     * @return array options.
     */
    public static function getOptions(){
        return [
            1 => 'Option 1',
            2 => 'Option 2',
            3 => 'Option 3',
        ];
    }

}
