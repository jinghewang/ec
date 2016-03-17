<?php

namespace backend\controllers;

use common\helpers\NetHelper;
use kartik\mpdf\Pdf;
use Yii;
use backend\models\Country;
use backend\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class TestController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'update' => ['get','put','post'],
                    'delete' => ['get','post','delete'],
                ],
            ],
        ];
    }

    const NEWS_URL = 'http://r.ec.com/news';


    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionGet()
    {
        $url = self::NEWS_URL;
        $data = array('name'=>'wjh','content'=>'wjh');
        $result = NetHelper::curl_post($url,$data,'string',0,null,'GET');
        $result = json_decode($result);
        //测试提交
        echo $result;
    }


    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionPost()
    {
        $url = self::NEWS_URL;
        $data = array('name'=>'wjh','content'=>'wjh');
        $result = NetHelper::curl_post($url,$data,'string',0,null,'POST');
        $result = json_decode($result);
        echo $result;
    }


    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionPut()
    {
        $url = self::NEWS_URL;
        $data = array('id'=>'20','name'=>'wjh','content'=>'wjh');
        $result = NetHelper::curl_post($url,$data,'string',0,null,'PUT');
        $result = json_decode($result);
        echo $result;
    }




    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex()
    {
        $url = self::NEWS_URL .'/index';
        $data = [];
        $result = NetHelper::curl_post($url,$data,'string',0,null,'GET');
        echo $result;
    }

    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionIndex2()
    {
        $url = self::NEWS_URL .'/index';
        $data = [];
        $result = NetHelper::curl_post($url,$data,'string',0,null,'HEAD');
        echo $result;
    }

    /**
     * Lists all Country models.
     * @return mixed
     */
    public function actionPdf()
    {
        /**
         * @var Pdf $pdf
         */
        $htmlContent = file_get_contents("D:/template/t2.htm");
        //$htmlContent = "psddsds";

        $pdf = Yii::$app->pdf;
        $pdf->content = $htmlContent;
        return $pdf->render();
    }

    /**
     * Displays a single Country model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $url = self::NEWS_URL .'/view/' .$id;
        $data = [];
        $result = NetHelper::curl_post($url,$data,'string',0,null,'GET');
        echo $result;
    }

    /**
     * Displays a single Country model.
     * @param string $id
     * @return mixed
     */
    public function actionView2($id)
    {
        $url = self::NEWS_URL .'/view/' .$id;
        $data = [];
        $result = NetHelper::curl_post($url,$data,'string',0,null,'HEAD');
        echo $result;
    }


    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $url = self::NEWS_URL;// $url = self::NEWS_URL .'/create';
        $data = array('name'=>'wjh','content'=>'wjh');
        $result = NetHelper::curl_post($url,$data,'string',0,null,'POST');
        echo $result;
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $url = self::NEWS_URL .'/update/'.$id;
        $data = array('name'=>'wjh2','content'=>'wjh2');
        $result = NetHelper::curl_post($url,$data,'string',0,null,'PUT');
        echo $result;
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $url = self::NEWS_URL .'/delete/' .$id;
        $data = [];
        $result = NetHelper::curl_post($url,$data,'string',0,null,'DELETE');
        echo $result;
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
