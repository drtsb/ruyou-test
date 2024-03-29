<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\components\ParentAccessValidator;

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
            [['name', 'parent_id'], 'unique', 'targetAttribute' => ['name', 'parent_id']],
            [['parent_id'], ParentAccessValidator::class],
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
    public function afterDelete()
    {
        $files = self::find()
            ->select(['name'])
            ->where(['type' => self::TYPE_FILE, 'parent_id' => $this->primaryKey])
            ->asArray()
            ->column();

        $fileMessage = $files ? "<br />Удалены вложенные файлы: " . implode(', ', $files) : '';

        $folders = self::find()
            ->select(['name'])
            ->where(['type' => self::TYPE_DIR, 'parent_id' => $this->primaryKey])
            ->asArray()
            ->column();

        $folderMessage = $folders ? "<br />Папки " . implode(', ', $folders) . " перемещены в родительский каталог." : '';

        self::updateAll(
            ['parent_id' => $this->parent_id],
            ['type' => self::TYPE_DIR, 'parent_id' => $this->primaryKey]
        );

        self::deleteAll(['type' => self::TYPE_FILE, 'parent_id' => $this->primaryKey]);

        Yii::$app->session->setFlash('error', $this->name." удален.".$fileMessage.$folderMessage);

        parent::afterDelete();
    }

    /**
     * @return array directories list
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
        return ($this->type === self::TYPE_FILE);
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
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(File::class, ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFileRoles()
    {
        return $this->hasMany(FileRole::class, ['file_id' => 'id']);
    }

    /**
     * @return array roles
     */
    public function getRoles($roles = [])
    {
        $roles = ArrayHelper::merge(
            ArrayHelper::getColumn($this->fileRoles, 'role', false),
            $roles
        );
        if (empty($this->parent_id)) { return $roles; }
        return $this->parent->getRoles($roles);
    }

    /**
     * @return string full path
     */
    public function getPath($path = '') : string
    {
        if (empty($this->parent_id)) { return '/'.$this->name.'/'.$path; }
        return $this->parent->getPath($this->name.'/'.$path);
    }

    /**
     * @return array breadcrumbs
     */ 
    public function getBreadcrumbs($breadcrumbs = [], File $file = null) : array
    {
        if (is_null($file)){
            if (empty($this->parent_id)) {
                return array_reverse($breadcrumbs);
            }
            return $this->getBreadcrumbs($breadcrumbs, $this->parent);
        }

        $breadcrumbs[] = ['label' => $file->name, 'url' => ['view', 'id' => $file->id]];

        if (empty($file->parent_id)) {
            return array_reverse($breadcrumbs);
        }

        return $file->getBreadcrumbs($breadcrumbs, $file->parent);
    }

    /**
     * @param int $user_id the user ID.
     * @return boolean whether user can access this file
     */
    public function checkUserAccess($user_id)
    {
        $userRoles = ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($user_id), 'name', false);

        // Если есть пересечения в ролях пользователя и файла
        return !empty(array_intersect($this->roles, $userRoles));
    }
}
