<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\Security;

/**
 * RequestForm is the model behind the request form.
 */
class RequestForm extends Model
{
    public $name;
    public $message;
    public $file;
    public $option;

    public $request;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'message'], 'required'],
            [['option'], 'integer'],
            [['message'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg', 'skipOnEmpty' => true,],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'ФИО',
            'message' => 'Сообщение',
            'file' => 'Изображение',
            'option' => 'Опция',
        ];
    }

    /**
     * @return bool whether the model passes validation
     */
    public function create()
    {
        if ($this->validate()) {
            $path = null;
            if (!empty($this->file)){
                $security = new Security();
                $path = 'uploads/' . $security->generateRandomString() . '.' . $this->file->extension;
                if (!$this->file->saveAs($path)){
                    return false;
                }
            }
            $request = new Request([
                'name' => $this->name,
                'message' => $this->message,
                'option' => $this->option,
                'image' => $path,
            ]);
            return $request->save();
        } else {
            return false;
        }
    }

    /**
     * @return bool whether the model passes validation
     */
    public function update()
    {
        if ($this->validate()) {
            // Если загружен новый файл
            if (!empty($this->file)){
                // удаляем старый
                if (!empty($this->request->image)) {
                    unlink($this->request->image);
                }
                $security = new Security();
                $path = 'uploads/' . $security->generateRandomString() . '.' . $this->file->extension;
                if (!$this->file->saveAs($path)){
                    return false;
                }
                $this->request->image = $path;
            }

            $this->request->attributes = $this->attributes;

            return $this->request->save();
        } else {
            return false;
        }
    }    

}
