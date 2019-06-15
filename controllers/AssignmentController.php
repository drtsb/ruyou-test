<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use app\models\AssignmentForm;
use app\models\User;

/**
 * AssignmentController implements actions for roles assignment.
 */
class AssignmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Request models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => new ActiveDataProvider([
                'query' => User::find(),
                'pagination' => false,
            ]),
        ]);
    }

    /**
     * Updates an existing User assignments.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $model = new AssignmentForm([
            'user_id' => $id,
            'roles' => ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($id), 'name', false),
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $roles = ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');

        ArrayHelper::remove($roles, Yii::$app->params['role-name-any']);

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Finds the User model based on its id value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
