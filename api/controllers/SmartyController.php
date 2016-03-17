<?php

namespace api\controllers;

use api\models\Demo;
use Smarty;
use XS;
use XSDocument;
use Yii;
use api\models\AccessToken;
use api\models\AccessTokenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessTokenController implements the CRUD actions for AccessToken model.
 */
class SmartyController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','get'],
                ],
            ],
        ];
    }

    /**
     * Lists all AccessToken models.
     * @return mixed
     */
    public function actionIndex()
    {

        $smarty = new Smarty();

        $name = Yii::$app->viewPath."/smarty/1.tpl";
        $smarty->assign('username','wjh');
        $content = $smarty->fetch($name);

        $fp = fopen($name . '.html','w');
        fwrite($fp,$content);
        fclose($fp);

        echo $content;
    }

    /**
     * Displays a single AccessToken model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {

    }

    /**
     * Creates a new AccessToken model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

    }

    /**
     * Updates an existing AccessToken model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionCreate2($id=null)
    {


    }

    /**
     * Deletes an existing AccessToken model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id=null)
    {


    }

    /**
     * Finds the AccessToken model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AccessToken the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {


    }
}
