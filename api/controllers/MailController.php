<?php

namespace api\controllers;

use api\models\Contract;
use api\models\Group;
use api\models\Traveller;
use api\services\ContractService;
use Yii;
use yii\base\Exception;
use yii\helpers\BaseJson;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessAppController implements the CRUD actions for AccessApp model.
 */
class MailController extends Controller
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
     * 发送合同下载邮件
     * @param $email 邮箱
     * @param $ecno 合同号
     */
    public function actionSendMail($email,$ecno)
    {
        $ec = Contract::find()->where(['contr_no'=>$ecno])->one();
        $assigned = Traveller::find()->andWhere('contr_id=:contr_id and is_leader=:is_leader', [':contr_id' => $ec->contr_id, ':is_leader' => '1'])->one();
        $group = Group::find()->andWhere('contr_id=:contr_id', [':contr_id' => $ec->contr_id])->one();
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($email);
        $mail->setSubject("旅游电子合同签名成功");
        $mail->setHtmlBody("<div>
        <p>尊敬的 {$assigned->name} ：</p>
        <p>您出团日期为 {$group->bgndate}的合同【{$ec->contr_no}】 已经上传至旅游局备案并完成签字，请确认附件中PDF合同内容。</p>
        <p>您可以下载合同附件查阅合同备案情况 。祝您旅途愉快！[北京汇来科技有限公司]</p>
        </div>");

        $file=ContractService::getPdfPath($ecno);
        if(file_exists($file)){
            $mail->attach($file,array('fileName'=>"{$ecno}.pdf",));
        }
        $mail->send();
    }
}
