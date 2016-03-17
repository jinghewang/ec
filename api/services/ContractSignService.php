<?php

namespace api\services;

use api\models\ContractSign;
use common\services\Sms;
use Yii;
use yii\base\Exception;
use yii\base\ExitException;
use yii\data\Pagination;
use yii\data\Sort;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\services;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class ContractSignService
{

    public static function getSignImage($cno)
    {

        /**
         * @var ContractSign $cs
         */
        $cs = new ContractSign($cno);
        if ($cs)
            return $cs->sign_data;
        else
            return '';
    }

}
