<?php

namespace api\controllers;

use Yii;
use yii\helpers\BaseJson;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessAppController implements the CRUD actions for AccessApp model.
 */
class HtmlController extends Controller
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
        return $this->renderPartial('inside');
    }

    public function actionDebug(){
        $jsonTravelerArray=array(
            'addr'=>'河南省三门峡市湖滨区会兴镇王官村',
            'fax'=>'86 519-85125379',
            'zip'=>'472000',
            'mail'=>'touzilk521@163.com',
        );
        echo BaseJson::encode($jsonTravelerArray).'<br>';

        $jsonGroupArray=array(
            'corp'=>'北京神舟国际旅行社集团有限公司',
            'corpCode'=>'L-BJ-CJ00080',
            'scope'=>'旅行社、旅游酒店、商业、餐饮等',
            'addr'=>'朝阳区建外大街28号北京旅游大厦',
            'tel'=>'',
            'fax'=>'',
            'zip'=>'',
            'mail'=>''
        );

        echo BaseJson::encode($jsonGroupArray).'<br>';

        $jsonPayArray=array(
            'payEachAdult'=>'1500',
            'payEachChild'=>'0',
            'payTravel'=>'3100',
            'payDeadline'=>'2016-01-11',
            'payType'=>'1',
        );

        echo BaseJson::encode($jsonPayArray).'<br>';

        $jsonPayArray=array(
            'purchases'=>'1',
            'insurance'=>'中国人寿',
        );

        echo BaseJson::encode($jsonPayArray).'<br>';

        $jsonPayArray=array(
            'transAgree'=>'0',
            'transAgency'=>'',
            'delayAgree'=>'0',
            'changeLineAgree'=>'0',
            'terminateAgree'=>'1',
            'mergeAgree'=>'1',
            'mergeAgency'=>'北京中旅',
            'teminateDealType'=>'2',
            'committee'=>'',
        );

        echo BaseJson::encode($jsonPayArray).'<br>';

        $jsonPayArray=array(
            'copys1'=>'2',
            'copys2'=>'1',
            'agencyComplaintsMobile'=>'4000100808',
            'lawCity'=>'北京',
            'lawComplaintsMobile'=>'1',
            'lawState'=>'1',
            'lawEmail'=>'北京中旅',
            'awAddress'=>'2',
            'lawZip'=>'',
            'addr'=>'',
        );

        echo BaseJson::encode($jsonPayArray).'<br>';

    }

}
