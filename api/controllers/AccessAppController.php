<?php

namespace api\controllers;

use api\services\AjaxStatus;
use common\helpers\BDataHelper;
use Yii;
use api\models\AccessApp;
use api\models\AccessAppSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessAppController implements the CRUD actions for AccessApp model.
 */
class AccessAppController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AccessApp models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccessAppSearch();
        $dataProvider = $searchModel->searchKeyword(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccessApp model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AccessApp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AccessApp();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->appkey]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new AccessApp model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateAjax()
    {
        $model = new AccessApp();

        if (Yii::$app->request->isPost && isset(Yii::$app->request->bodyParams['AccessApp'])) {
            $model->load(Yii::$app->request->post());
            $map = $this->getRestMap();
            try{
                if (!$model->save())
                    throw new Exception(json_encode($model->errors));

                $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
                $map[AjaxStatus::PROPERTY_DATA] = $model->attributes;
            }
            catch(Exception $e){
                $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
                $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
            }
            echo json_encode($map);
            Yii::$app->end();

            //return $this->redirect(['view', 'id' => $model->appkey]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccessApp model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->appkey]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AccessApp model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AccessApp model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AccessApp the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccessApp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
