<?php

namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

use app\models\File;

class FileRule extends Rule
{
    public $name = 'fileRule';

    /**
     * @param string|int $user the user ID.
     * @param Item $item the role or permission that this rule is associated width.
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     * @throws InvalidParamException
     */
    public function execute($user, $item, $params) : bool
    {
        if (!isset($params['file']) || !($params['file'] instanceof File)) {
            throw new InvalidParamException("Param 'file' should be instance of File class.", 1);
        }
       
        return $params['file']->checkUserAccess($user);
    }
}