<?php

namespace api\controllers;

class TextController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');

    }

}
