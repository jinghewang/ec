<?php

namespace restful\controllers;

use Yii;
use restful\models\News;
use restful\models\NewsSearch;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends ActiveController
{
    public $modelClass = 'restful\models\News';

 

}
