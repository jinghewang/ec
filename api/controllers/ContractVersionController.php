<?php

namespace api\controllers;

use api\models\Chargeable;
use api\models\Contract;
use api\models\ContractSign;
use api\models\Group;
use api\models\Organization;
use api\models\Other;
use api\models\Routes;
use api\models\ShopAgreement;
use api\models\Traveller;
use api\services\ContractService;
use api\services\ContractVersionService;
use common\helpers\BDataHelper;
use common\helpers\ConfigHelper;
use common\helpers\DataHelper;
use common\helpers\PdfHelper;
use Faker\Provider\zh_TW\DateTime;
use kartik\mpdf\Pdf;
use Yii;
use api\models\ContractVersion;
use api\models\ContractVersionSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\helpers\Pdf_Watermark;

/**
 * ContractVersionController implements the CRUD actions for ContractVersion model.
 */
class ContractVersionController extends Controller
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
     * Lists all ContractVersion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContractVersionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Lists all AccessApp models.
     * @return mixed
     */
    public function actionPreview($contr_id='1')
    {
        /**
         * @var Pdf $pdf
         * @var Contract $ec
         */

        $this->layout = 'main_outer';

        $ec = Contract::findOne($contr_id);
        if (empty($ec))
            throw new Exception('合同不存在');

        //$pdf->output($htmlContent);
        return $this->render('inside');
    }

    /**
     * Lists all AccessApp models.
     * @return mixed
     */
    public function actionPreviewPdf($contr_id='1')
    {
        /**
         * @var Pdf $pdf
         * @var Contract $ec
         */

        $ec = Contract::findOne($contr_id);
        if (empty($ec))
            throw new Exception('合同不存在');

        //pdf
        $css = Yii::$app->basePath . "/web/css/ec.css";
        $css = file_get_contents($css);
        $htmlContent = $this->renderPartial('inside');//file_get_contents("D:/template/t2.htm");
        $pdf = Yii::$app->pdf;
        $pdf->cssInline .= $css;
        $pdf->content = $htmlContent;
        $pdf->filename = "电子合同-{$ec->contr_no}.pdf";
        //methods
        $pdf->methods['SetHeader'] = "合同编号<span class=\"color-tno\">{$ec->contr_no}</span>";
        $pdf->methods['SetTitle'] = ConfigHelper::getAppConfig('down');
        $pdf->methods['SetAuthor'] = ConfigHelper::getAppConfig('author');
        $pdf->methods['SetCreator'] = ConfigHelper::getAppConfig('creator');
        $pdf->methods['SetSubject'] = ConfigHelper::getAppConfig('subject');

        //$pdf->output($htmlContent);
        return $pdf->render();
    }


    /**
     * Lists all AccessApp models.
     * @return mixed
     */
    public function actionPreviewTpl($ecno,$p=1)
    {
        /**
         * @var Pdf $pdf
         * @var Contract $ec
         * @var Group $group
         * @var Organization $provider
         * @var Traveller $assigned
         * @var Other $other
         */

        $this->layout = 'main_outer.php';

        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();

        if (empty($ec))
            throw new Exception('合同不存在');

        //ecid
        $ecid=$ec->contr_id;

        $version = ContractVersion::findOne($ec->vercode);
        if (empty($version))
            throw new Exception('合同版本不存在');

        $travellers = Traveller::find()->andWhere('contr_id=:contr_id', [':contr_id' => $ec->contr_id])->all();
        $provider = $ec->org;
        $assigned = Traveller::find()->andWhere('contr_id=:contr_id and is_leader=:is_leader', [':contr_id' => $ec->contr_id, ':is_leader' => '1'])->one();;
        $group = Group::find()->andWhere('contr_id=:contr_id', [':contr_id' => $ec->contr_id])->one();

        $other = Other::findOne($ecid);
        $pay = json_decode($other->pay,true);
        $insurance = json_decode($other->insurance,true);
        $groupcorp = json_decode($provider->extra_data,true);
        $otherGroup = json_decode($other->group,true);
        $effect = json_decode($other->effect,true);

        $routes = Routes::find()->andWhere('contr_id=:contr_id and parentid=:parentid',[':contr_id'=>$ec->contr_id,':parentid'=>0])->all();
        //自愿购物活动补充协议
        $shops = ShopAgreement::find()->where("contr_id='{$ec->contr_id}'")->orderBy('index asc')->all();
        //自愿参加另行付费旅游项目补充协议
        $chargeables = Chargeable::find()->where("contr_id='{$ec->contr_id}'")->orderBy('index asc')->all();

        $contractSign = new ContractSign($ec->contr_id);

        $data = [
            'contract' => $ec,//合同
            'version' => $version,//合同版本
            'provider' => $provider,//旅行社
            'group' => $group,//团信息
            'assigned' => $assigned,//签字代表
            'travellers' => $travellers,//人员名单
            'routes' => $routes,//行程
            'shops' => $shops,//自愿购物活动补充协议
            'chargeables' => $chargeables,//自愿参加另行付费旅游项目补充协议
            'other'=>$other,
            'pay' => $pay,
            'insurance' => $insurance,
            'groupcorp' => $groupcorp,
            'otherGroup' => $otherGroup,
            'effect' => $effect,
            'contractSign' =>$contractSign,//签字
            //----------------------------------
            'app' => ConfigHelper::getParamsConfigArray('app'),//app 信息
            'company' => ConfigHelper::getParamsConfigArray('company'),//公司信息
            'year' => date('Y'),//年份
            'PAGE_BREAK' => PdfHelper::PAGE_BREAK_MK,//分页符
        ];

        //获取合同模板路径
        $template=ContractVersionService::getContractTemplateByModel($version);

        //pdf
        if($p){
            $css = Yii::$app->basePath . "/web/css/ec.css";
            $css = file_get_contents($css);
            $htmlContent = $this->render($template,$data);//file_get_contents("D:/template/t2.htm");
            $pdf = Yii::$app->pdf;
            $pdf->cssInline .= $css;
            $pdf->content = $htmlContent;
            $pdf->filename = "电子合同-{$ec->contr_no}.pdf";
            //methods
            $pdf->methods['SetHeader'] = "合同编号<span class=\"color-tno\">{$ec->contr_no}</span>";
            $pdf->methods['SetTitle'] = ConfigHelper::getAppConfig('down');
            $pdf->methods['SetAuthor'] = ConfigHelper::getAppConfig('author');
            $pdf->methods['SetCreator'] = ConfigHelper::getAppConfig('creator');
            $pdf->methods['SetSubject'] = ConfigHelper::getAppConfig('subject');

            return $pdf->render();
        }else{
            return $this->render($template,$data);
        }

        //$pdf->output($htmlContent);
        //echo $htmlContent;
    }

    /**
     * 处理preview-tpl action参数P引起的生成pdf错误
     * @author lvkui
     * @param $ecno
     */
    public function actionHtml($ecno){
        $this->redirect(array('contract-version/preview-tpl','ecno'=>$ecno,'p'=>'0'));
    }

    /**
     * 下载pdf
     * @author lvkui
     * @param $ecno
     */
    public function  actionPdf($ecno){

        $fileName =ContractService::getPdfPath($ecno);

        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();
        if($ec->status==Contract::CONTRACT_STATUS_SIGNED){

            if(!file_exists($fileName)){
                ContractService::generate_pdf($ecno,false);
            }else{
                BDataHelper::downfile($fileName);
            }
        }else{
            ContractService::generate_pdf($ecno,false);
        }
    }


    /**
     * Lists all AccessApp models.
     * @return mixed
     */
    public function actionTest()
    {
        $this->layout = 'main_outer.php';
        return $this->render('@app/../common/template/test.tpl',[
            'name'=>'wjh',
            'age'=>28,
            'data'=>[13,45]
        ]);
    }

    /**
     * Lists all AccessApp models.
     * @return mixed
     */
    public function actionTestPdf()
    {
        /**
         * @var Pdf $pdf
         */
        $css = Yii::$app->basePath . "/web/css/ec.css";
        $css = file_get_contents($css);
        $htmlContent = $this->renderPartial('test');//file_get_contents("D:/template/t2.htm");
        $pdf = Yii::$app->pdf;
        $pdf->content = $htmlContent;
        $pdf->cssInline .= $css;

        return $pdf->render();
    }

    /**
     * Displays a single ContractVersion model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ContractVersion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContractVersion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vercode]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ContractVersion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->vercode]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionMark($ecno){
        $filename=ContractService::getPdfPath($ecno);
        var_dump($filename);die;
        ContractService::setWater($filename);
    }

    /**
     * Deletes an existing ContractVersion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ContractVersion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ContractVersion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContractVersion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
