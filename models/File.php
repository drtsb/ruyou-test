<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%file}}".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $type
 */
class File extends \yii\db\ActiveRecord
{

    const TYPE_FILE = 1;
    const TYPE_DIR = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%file}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'type'], 'integer'],
            [['name', 'type'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['name', 'parent_id'], 'unique', 'targetAttribute' => ['name', 'parent_id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Folder',
            'name' => 'Name',
            'type' => 'Type',
        ];
    }

    /**
    * @inheritdoc
    */
    public function beforeDelete()
    {


        return parent::beforeDelete();
    }

    /**
     * @return array types
     */ 
    public static function getDirList() : array
    {
        return ArrayHelper::map(
            self::find()->where(['type'=>self::TYPE_DIR])->all(),
            'id',
            'name'
        );
    }

    /**
     * @return string type name
     */
    public function getTypeName() : string
    {
        return ArrayHelper::getValue(self::getTypesList(), $this->type);
    }

    /**
     * @return array types
     */ 
    public static function getTypesList() : array
    {
        return [
            self::TYPE_DIR => 'Directory',
            self::TYPE_FILE => 'File',
        ];
    }

    /**
     * @return array types
     */ 
    public function isFile() : bool
    {
        return ($this->type===self::TYPE_FILE);
    }

    /**
     * @return string parent url
     */ 
    public function getParentUrl() : string
    {
        return $this->parent_id ? Url::toRoute(['view', 'id'=>$this->parent_id]) : Url::toRoute(['index']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(File::class, ['parent_id' => 'id']);
    }

    /**
     * @return string full path
     */
    public function getPath($path) : string
    {
        if (empty($this->parent_id)) { return '/'.$this->name.$path; }
        return $this->parent->getPath($path);
    }


}