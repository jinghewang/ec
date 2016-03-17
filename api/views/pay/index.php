<?php
/* @var $this yii\web\View */
use common\helpers\BDataHelper;

?>
<h1>支付</h1>

<p>
    <?php
    BDataHelper::print_r($result);
    ?>
    <a class="hidden" target="_blank" href="<?= $result['cashier_url'] ?>">支付宝支付</a>

    <!-- Small modal -->
    <a class="btn btn-primary rb-btn-pay" target="_blank" href="<?= $result['cashier_url'] ?>">支付宝支付</a>

    <div class="modal fade rb-btn-model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="hidden" aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">支付情况</h4>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 text-center">
                                <button type="button" class="btn btn-primary btn-lg rb-pay-success">支付成功</button>
                            </div>
                            <div class="col-lg-6 col-sm-6 text-center">
                                <button type="button" class="btn btn-warning btn-lg rb-pay-fail">支付失败</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer hidden">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Send message</button>
                </div>
            </div>
        </div>
    </div>

</p>

<script>
    $(function(){
        $('.rb-btn-pay').on('click',function(e){
            var modal = $('.rb-btn-model');
            $(modal).modal('toggle');
        });

        $('.rb-pay-success').on('click',function(e){
            alert('支付成功');
            var modal = $('.rb-btn-model');
            $(modal).modal('toggle');
        });

        $('.rb-pay-fail').on('click',function(e){
            alert('支付失败');
            var modal = $('.rb-btn-model');
            $(modal).modal('toggle');
        });
    })
</script>