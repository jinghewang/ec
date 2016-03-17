<?php

namespace api\controllers;

use api\models\Contract;
use api\services\AjaxStatus;
use api\services\SmsService;
use api\services\WxService;
use api\services\ContractService;
use common\helpers\BaseDataHelper;
use common\helpers\BDataHelper;
use common\helpers\DataHelper;
use Yii;
use api\models\ContractSign;
use api\models\ContractSignSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\validators;

/**
 * ContractSignController implements the CRUD actions for ContractSign model.
 */
class ContractSignController extends BaseController
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
     * Lists all ContractSign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContractSignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContractSign model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContractSign model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContractSign();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sign_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ContractSign model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sign_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ContractSign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionSignLogin(){
        var_dump_die(222);
    }

    /**
     * 获取签名信息
     */
    public function actionSign($cno='1'){
        /**
         * @var Contract $contract
         * @var ContractSign $model
         */
        $wxService = WxService::getInstance();
        if (!$wxService->checkLogin()){
            Yii::$app->response->redirect($wxService->loginPage);
            Yii::$app->end();
        }

        $this->layout = "main_sign";
        $model = new ContractSign($cno);
        $contract = $model->contract;
        if (isset($_POST['ContractSign'])) {
            $map = $this->getRestMap();
            try{
                //contract sign
                $data = $_POST['ContractSign'];
                $email=$data['email'];

                $model = ContractSign::findOne($data['sign_id']);
                if ($data['code'] != $model->sign_code)
                    throw new Exception('手机验证码输入不正确');

                $model->load(Yii::$app->request->post());
                //$model->contr_id = $_REQUEST['cno'];
                if(!$model->save())
                    throw new Exception(BDataHelper::getErrorMsg('操作失败，原因：',$model));

                //contract
                $contract = Contract::findOne($model->contr_id);
                $contract->status = Contract::CONTRACT_STATUS_SIGNED;
                $contract->sign_time = BaseDataHelper::getCurrentTime();
                if (!$contract->save())
                    throw new Exception(BDataHelper::getErrorMsg('操作失败，原因：',$contract));

                //生成pdf
                ContractService::generate_pdf($contract->contr_no);

                //已签字保存合同文件及文件签名
                ContractService::save_file_info($contract->contr_no);

                //发送邮件
                if(!empty($email)){
                    $validator = new validators\EmailValidator();
                    if($validator->validate($email, $error)){
                        ContractService::send_email($email,$contract->contr_no);
                    }else{
                        throw new Exception($error);
                    }
                }

                $map[AjaxStatus::PROPERTY_STATUS]=AjaxStatus::STATUS_SUCCESSFUL;
                //$map[AjaxStatus::PROPERTY_DATA] = ['code'=>$code,'mobile'=>$mobile];
                echo json_encode($map);
            }
            catch(\Exception $e){
                $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
                echo json_encode($map);
            }
        } else {
            return $this->render('sign', [
                'model' => $model,
                'contract' => $contract
            ]);
        }
    }

    /**
     * 签名查看
     */
    public function actionSignView($cno='1'){
        $this->layout = "main_sign";
        $model = new ContractSign($cno);
        $contract = $model->contract;
        return $this->render('sign-view', [
            'model' => $model,
            'contract' => $contract
        ]);
    }

    /**
     * 手机验证码
     */
    public function actionCode($cno='1'){
        $map = $this->getRestMap();
        try{
            /**
             * @var Contract $contract
             * @var ContractSign $cs
             */
            $contract = Contract::findOne($cno);
            $cs = ContractSign::findOne(['contr_id'=>$cno]);

            //发送短信验证码
            $code = SmsService::getCode();
            $cs->sign_code = $code;
            if (!$cs->save())
                throw new Exception(BDataHelper::getErrorMsg('操作失败，原因：' . $cs));

            $mobile = $cs->mobile;
            $company = '神舟旅游';
            $msg = SmsService::getMessgeCodeTemplate($code,$contract->contr_no,$company);
            if (!SmsService::batchSend($mobile, $msg))
                throw new Exception('短信发送失败');

            $map[AjaxStatus::PROPERTY_STATUS]=AjaxStatus::STATUS_SUCCESSFUL;
            $map[AjaxStatus::PROPERTY_DATA] = ['code' => $code, 'mobile' => $mobile, 'sign_id' => $cs->sign_id];
            echo json_encode($map);
        }
        catch(\Exception $e){
            $map[AjaxStatus::PROPERTY_MESSAGES] = $e->getMessage();
            echo json_encode($map);
        }
    }
    /**
     * Finds the ContractSign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContractSign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContractSign::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
