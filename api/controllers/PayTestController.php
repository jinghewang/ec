<?php

namespace api\controllers;

use Yii;
use api\models\AccessToken;
use api\models\AccessTokenSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessTokenController implements the CRUD actions for AccessToken model.
 */
class PayTestController extends PayBaseController
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
     * Lists all AccessToken models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccessTokenSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
