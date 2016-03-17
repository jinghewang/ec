<?php

namespace api\controllers;

use api\services\AjaxStatus;
use common\helpers\BDataHelper;
use Yii;
use api\models\Order;
use api\models\OrderSearch;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends BaseController
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination = new Pagination(['pageSize'=>5]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->oid]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Pay a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionPay()
    {
        $model = new Order();
        if (!empty($_REQUEST['appkey']))
            $model->appkey = $_REQUEST['appkey'];

        if (Yii::$app->request->isPost && isset(Yii::$app->request->bodyParams['Order'])) {

            $map = self::getRestMap();
            try {
                $model->load(Yii::$app->request->post());
                $model->status = Order::STATUS_DEFAULT;
                if (!$model->save()){
                    throw new Exception($model->errors);
                }

                $map[AjaxStatus::PROPERTY_MESSAGES] = "业务处理成功";
                $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
                $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_OK;
                $map[AjaxStatus::PROPERTY_DATA] = $model->attributes;
            } catch (Exception $e) {
                $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
                $map[AjaxStatus::PROPERTY_CODE] = AjaxStatus::CODE_503;
                $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
            }
            echo json_encode($map);

            //return $this->redirect(['view', 'id' => $model->oid]);
        } else {
            $model->out_trade_no = "{$model->appkey}-" . BDataHelper::getCurrentTime('Ymd') . '-' . BDataHelper::randomNum(6);
            return $this->renderPartial('pay', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->oid]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
