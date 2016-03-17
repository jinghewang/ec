<?php

namespace api\services;

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
use common\helpers\DataHelper;
use api\models\Contract;
use api\models\ContractSign;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class PayService
{
    private $url = 'http://testfront.51ebill.com:65527/front/base/gateway.in';
    private $username = 'gonggongceshi';
    private $userpwd = '88@88.com';
    private $paypwd = '88@88.com';
    private $orgcode = 'CC_C4762811203';
    private $ca = 'CA21000000006002';
    private $pid = '10012387293150713';
    private $key = '574C955AFF58125A86E256D332CA275F';


}
