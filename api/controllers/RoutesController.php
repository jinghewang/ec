<?php

namespace api\controllers;

class RoutesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
