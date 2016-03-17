<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\helpers\BDefind;
use api\models\Contract;

/* @var $this yii\web\View */
/* @var $searchModel api\models\ContractSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '电子合同';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

   <!-- <p>
        <?/*= Html::a('Create Contract', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'contr_no',
            [
                'label'=>'合同版本',
                'format'=>'raw',
                'value' => function($data){
                        return $data->version->title;
                    },
            ],
            [
                'label'=>'合同类型',
                'format'=>'raw',
                'value' => function($data){
                        return BDefind::getValue(Contract::$contract_type,$data->type);
                    },
            ],
            // 'lock_time',
            [
                'label'=>'合同状态',
                'format'=>'raw',
                'value' => function($data){
                        return BDefind::getValue(Contract::$contract_status,$data->status);
                    },
            ],
            // 'audit_status',
            // 'is_submit',
            // 'sub_time',
            // 'sign_time',
            // 'price',
            // 'num',
            [
                'label'=>'经办人',
                'format'=>'raw',
                'value' => function($data){
                        return $data->transactor;
                    },
            ],
            // 'oldcontr',
            // 'orgid',
            // 'createtime',
            // 'userid',
            // 'modified',
            // 'extra_data:ntext',

            [
                'label'=>'查看',
                'format'=>'raw',
                'value' => function($data){
                        $url = "/contract-version/pdf?ecno={$data->contr_no}";
                        $result=  Html::a('PDF', $url);
                        $result .= Html::tag('br');
                        $url = "/contract-version/preview-tpl?ecno={$data->contr_no}&p=0";
                        $result .= Html::a('HTML', $url);
                        return $result;
                    }
            ],
        ],
    ]); ?>

</div>
