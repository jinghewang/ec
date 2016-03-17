<?php
/* @var $this yii\web\View */
use common\helpers\BDataHelper;

?>
<h1>充值结果</h1>

<p>


    <?php if (!empty($data['is_success'])  && $data['is_success'] == 'T') { ?>
    <div class="alert alert-success" style="padding: 30px" role="alert">
        <strong>充值成功</strong> ,感谢你的使用.
    </div>
    <?php
    } else { ?>
        <div class="alert alert-danger" style="padding: 30px" role="alert">
            <strong>充值失败</strong> ，请联系管理员.
        </div>

    <?php } ?>

    <!--a class="btn btn-primary" target="_blank" href="#">返回</a-->

    <?php
    //BDataHelper::print_r($data);
    ?>
</p>
