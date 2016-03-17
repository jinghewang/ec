<?php

namespace backend\controllers;

use common\helpers\NetHelper;
use Yii;
use backend\models\Country;
use backend\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class AccessTokenTestController extends Controller
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
     * Lists all Country models.
     * @return mixed
     */
    public function actionLogin()
    {
        $url = 'http://api.ec.com/ec/login?appkey=hlt2014api&clientid=YXA63VpnwIszEeSjZqcGJBng8Q&client_secret=YXA6IfChK92QC5gW_zhIy0BWNMjvTKo';
        $result = NetHelper::curl_post($url,array(),'string');
        $result = json_decode($result);

        if ($result && $result->status && $result->status == 'successful'){
            var_dump($result->data);
            Yii::$app->session->set('token',$result->data);
            //Yii::$app->session->set($result->data->tokenid,$result->data);
        }
        else{
            var_dump('操作失败，原因 ：'.$result->message);
        }
        /*echo $result;*/
    }


    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionLoginToken()
    {
       var_dump(Yii::$app->session->get('token'));
    }


    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionSave()
    {
        $url = 'http://api.ec.com/ec/save?tokenid=49whfwmzh4&appkey=hlt2014api&clientid=YXA63VpnwIszEeSjZqcGJBng8Q';
        $data = ['name'=>'wjh','age'=>19];
        $result = NetHelper::curl_post($url,$data,'string');
        $result = json_decode($result);
        if ($result && $result->status && $result->status == 'successful'){
            var_dump($result->data);
            //Yii::$app->session->set('token',$result->data);
            //Yii::$app->session->set($result->data->tokenid,$result->data);
        }
        else{
            var_dump('操作失败，原因 ：'.$result->message);
        }
        /*echo $result;*/
    }

    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        var_dump_die('index');
    }

    /**
     * Displays a single Country model.
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
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Country();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->code]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Country model.
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
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
