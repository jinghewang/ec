<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\ContractVersionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Contract Versions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-version-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Contract Version', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'vercode',
            'pcode',
            'title',
            'fileid',
            'orgid',
            // 'extra_data:ntext',
            // 'createuser',
            // 'createtime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
