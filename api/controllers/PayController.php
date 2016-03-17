<?php

namespace api\controllers;

use api\models\AccessApp;
use api\models\Order;
use api\models\Organization;
use api\services\AjaxStatus;
use api\services\DebugService;
use common\helpers\BDataHelper;
use Yii;
use api\models\AccessToken;
use api\models\AccessTokenSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessTokenController implements the CRUD actions for AccessToken model.
 */
class PayController extends PayBaseController
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
    public function actionIndex($appkey='',$payout=0.01)
    {
        error_reporting(E_ALL);

        //var_dump_die("0.01"/0.01);


        if (empty($appkey))
            var_dump_die('请输入orgid参数');

        /**
         * http报文参数
         */
        $http_arg = array(
            "key"           => $this->KEY,				//密钥
            "front_url"     => $this->FRONT_URL,			//请求地址
            "encoding"      => $this->INPUT_CHARSET,		//编码方式
            "http_time_out" => $this->HTTP_TIME_OUT		//请求超时时间（单位:s）
        );

        /**
         * 公用请求参数
         */
        $paras = array( );
        $paras["payout"] = $payout;

        $type = 'pay';
        $randCode = BDataHelper::randomNum(6);
        $out_trade_no = "{$appkey}-{$type}-{$randCode}";
        $subject_detail = '';
        $result = $this->pay_pwd($paras,$http_arg,$out_trade_no,$subject_detail);					//调用有密支付
        // royalty($paras,$http_arg);					//调用分账
        // refund($paras,$http_arg);					//调用退款

        return $this->render('index',[
            'result'=>$result
        ]);
    }


    /**
     * Lists all AccessToken models.
     * @return mixed
     */
    public function actionPayUrl($appkey,$out_trade_no,$payout=0)
    {
        $map = self::getRestMap();
        try {
            if (empty($appkey))
                throw new Exception('请输入orgid参数');

            /**
             * http报文参数
             */
            $http_arg = array(
                "key"           => $this->KEY,				//密钥
                "front_url"     => $this->FRONT_URL,			//请求地址
                "encoding"      => $this->INPUT_CHARSET,		//编码方式
                "http_time_out" => $this->HTTP_TIME_OUT		//请求超时时间（单位:s）
            );

            /**
             * 公用请求参数
             */
            $paras = array( );
            $paras["payout"] = $payout;

            //$out_trade_no = "{$appkey}-{$type}-{$randCode}";
            $subject_detail = '';
            $result = $this->pay_pwd($paras,$http_arg,$out_trade_no,$subject_detail);					//调用有密支付
            // royalty($paras,$http_arg);					//调用分账
            // refund($paras,$http_arg);					//调用退款

            //return $result['cashier_url'];

            $map[AjaxStatus::PROPERTY_MESSAGES] = "";
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_DATA] = $result;
        } catch (Exception $e) {
            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_FAILED;
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
        }
        echo json_encode($map);

    }


    /**
     * Lists all AccessToken models.
     * @return mixed
     */
    public function actionPayUrl2($orgid='',$payout=0)
    {
        if (empty($orgid))
            var_dump_die('请输入orgid参数');

        /**
         * http报文参数
         */
        $http_arg = array(
            "key"           => $this->KEY,				//密钥
            "front_url"     => $this->FRONT_URL,			//请求地址
            "encoding"      => $this->INPUT_CHARSET,		//编码方式
            "http_time_out" => $this->HTTP_TIME_OUT		//请求超时时间（单位:s）
        );

        /**
         * 公用请求参数
         */
        $paras = array( );
        $paras["payout"] = $payout;

        $type = 'pay';
        $randCode = BDataHelper::randomNum(6);
        $out_trade_no = "{$orgid}-{$type}-{$randCode}";
        $subject_detail = '';
        $result = $this->pay_pwd($paras,$http_arg,$out_trade_no,$subject_detail);					//调用有密支付
        // royalty($paras,$http_arg);					//调用分账
        // refund($paras,$http_arg);					//调用退款

        return $result['cashier_url'];
    }


    public function actionGetEccount($appkey)
    {
        /**
         * @var AccessApp $app
         */
        $map = $this->getRestMap();
        try{
            $app = AccessApp::findOne($appkey);
            if (empty($app))
                throw new Exception('app不存在');

            $map[AjaxStatus::PROPERTY_STATUS] = AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_DATA] = $app->attributes;
            echo json_encode($map);
        }
        catch(Exception $e){
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
            echo json_encode($map);
        }
    }


    public function actionResult()
    {
        DebugService::Log('pay-result', $_REQUEST, DebugService::TYPE_PAY);
        //BDataHelper::print_r($_REQUEST);
        return $this->render('result', [
            'data' => $_REQUEST,
        ]);
    }


    public function actionCallback(){
        try{
            DebugService::Log('pay-callback-1', $_REQUEST,DebugService::TYPE_PAY);

            $data = $this->getCallbackXml2Array($_REQUEST['para']);
            DebugService::Log('pay-callback-2', $data,DebugService::TYPE_PAY);
            if (!empty($data['is_success']) && $data['is_success'] == 'S'){//操作成功
                $out_trade_no = $data['out_trade_no'];
                $nos = preg_split('/-/',$out_trade_no);
                $appkey = $nos[0];

                //保存订单信息
                $order = Order::findOne(['out_trade_no' => $out_trade_no]);
                //$order->orgid = $orgid;
                $order->out_trade_no = $out_trade_no;
                $order->paysum = $data['payout'];
                $order->paynum = $this->getPayNum($order->paysum);
                $order->status = Order::STATUS_SUCCESS;
                $order->callback = json_encode($data);
                $order->callbacktime = BDataHelper::getCurrentTime();
                $order->createtime=BDataHelper::getCurrentTime();
                if (!$order->save())
                    DebugService::Log('callback-order-save',$order->errors);

                /**
                 * @var Organization $app
                 */

                //更新充值数量
                $app = AccessApp::findOne($appkey);
                if (empty($app))
                    throw new Exception('app不存在');

                $app->eccount = empty($app->eccount) ? 0 : $app->eccount;
                $app->eccount += $order->paynum;
                if (!$app->save())
                    DebugService::Log('callback-org-save',$app->errors);


                echo 'success';
            }
            else{//操作失败，不处理
                DebugService::Log('callback-order-save操作失败');
                echo 'error';
            }
        }
        catch(Exception $ex){
            DebugService::Log('callback-order-save操作失败',$ex->getMessage());
            echo 'error';
        }
    }
}
