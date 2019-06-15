<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use app\rbac\FileRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

		$auth->removeAll();

		// add the rule
		$rule = new FileRule;
		$auth->add($rule);

		$accessFile = $auth->createPermission('accessFile');
		$accessFile->description = 'Can access file';
		$accessFile->ruleName = $rule->name;
		$auth->add($accessFile);

        $any = $auth->createRole(Yii::$app->params['role-name-any']);
        $auth->add($any);

        $admin = $auth->createRole(Yii::$app->params['role-name-admin']);
        $auth->add($admin);

        $auth->addChild($admin, $any);

		$auth->addChild($any, $accessFile);

        $auth->assign($admin, 1);
    }
}