<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

use app\models\File;
use app\models\FileRole;
use app\models\FileAccessForm;

/**
 * FileController implements the CRUD actions for File model.
 */
class FileController extends Controller
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
                        'actions' => ['access'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'create', 'view', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all File models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => File::find()->where(['parent_id'=>null]),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'type' => SORT_DESC,
                    'name' => SORT_ASC,
                ],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single File model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if ($model->isFile()) {
            return $this->redirect($model->parentUrl);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getChilds(),
            'pagination' => false,
            'sort' => [
                'defaultOrder' => [
                    'type' => SORT_DESC,
                    'name' => SORT_ASC,
                ],
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new File model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $model = new File(['parent_id'=>$id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing File model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing access rules for file.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAccess($id)
    {
        $model = new FileAccessForm(['file_id'=>$id, 'roles' => FileRole::getRolesByFileId($id)]);

        $file = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $file->id]);
        }

        return $this->render('access', [
            'model' => $model,
            'file' => $file,
            'roles' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name'),
        ]);
    }

    /**
     * Deletes an existing File model.
     * If deletion is successful, the browser will be redirected to the parent page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->delete();

        if (Yii::$app->request->isAjax) {
            if ($model->parent_id) {
                $query = $model->parent->getChilds();
            } else {
                $query = File::find()->where(['parent_id'=>null]);
            }
            return $this->renderPartial('_table', [
                'dataProvider' =>  new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => false,
                    'sort' => [
                        'defaultOrder' => [
                            'type' => SORT_DESC,
                            'name' => SORT_ASC,
                        ],
                    ],
                ]),
            ]);
        }

        return $this->redirect($model->parentUrl);
    }

    /**
     * Finds the File model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return File the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = File::findOne($id)) !== null) {
            if (!(Yii::$app->user->can('accessFile', ['file'=>$model]) || Yii::$app->user->can(Yii::$app->params['role-name-admin']))) {
                throw new ForbiddenHttpException('У вас недостаточно прав для просмотра этой страницы.');
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
