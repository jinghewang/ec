<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */

/* @var $this yii\web\View */
/* @var $model api\models\ContractSign */
/* @var $form yii\widgets\ActiveForm */

$this->title = '创建签名';
$this->params['breadcrumbs'][] = ['label' => 'Contract Signs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$wxService = new \api\services\WxService();

$img_data = "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj48c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iMjAzIiBoZWlnaHQ9IjExNSI+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIgZD0iTSAxIDEgYyAwIDAuNCAtMC41NCAxNS4zNyAwIDIzIGMgMC40NiA2LjM4IDEuODcgMTIuNDUgMyAxOSBjIDAuNiAzLjQ3IDEgNi43NiAyIDEwIGMgMS42NyA1LjQyIDMuNzkgMTAuNTUgNiAxNiBjIDIuMjYgNS41NyA0LjAyIDExLjIgNyAxNiBjIDQuNTEgNy4yNSAxMC4yMyAxNS42MSAxNiAyMSBjIDMuNDIgMy4yIDkuMzYgNi4wNyAxNCA3IGMgNy43MiAxLjU0IDE4LjEgMi4wNSAyNiAxIGMgNi4xNSAtMC44MiAxMy4xNCAtNC4xNyAxOSAtNyBjIDMuNTMgLTEuNyA2LjQyIC00Ljk4IDEwIC03IGMgMTQuMjkgLTguMDkgMzAuMDcgLTE0LjAzIDQzIC0yMyBjIDExLjQ1IC03Ljk0IDIwLjgxIC0xOS4zOCAzMiAtMjkgYyA1LjkxIC01LjA4IDEyLjM5IC05LjEyIDE4IC0xNCBsIDUgLTYiLz48L3N2Zz4=";
?>

<div class="contract-sign-create">

    <h1 class="text-center hidden"><?= Html::encode($this->title) ?></h1>

    <div id="signature" class="rb-sign-border" ></div>
    <p class="hidden">
        <button class="sign-save" type="button">保存</button>
        <button class="sign-export" type="button">查看图片</button>
        <br>
        <br>
        <button onclick="alert($('#signature').jSignature('getData', 'svgbase64'))" type="button">Export</button>
        <button onclick="importData($('#signature'))" type="button">Import Data to Canvas</button>
    </p>
    <!--<img src="<?php /*echo $img_data */?>">测试-->
    <div id="show" class="rb-sign-border hidden">
        <img src="<?= $model->sign_data?>">
    </div>

    <div class="contract-sign-form">
        <div class="form-group text-center">
            <?= Html::button('生成签名', ['class' => 'btn btn-primary hidden rb-sign-save']) ?>
            <?= Html::submitButton($model->isNewRecord ? '保存签名' : '更新签名', ['class' => $model->isNewRecord ? 'btn btn-success hidden' : 'btn btn-primary hidden']) ?>
            <!--使用中的-->
            <?= Html::button('生成签名', ['class' => 'btn btn-primary rb-btn-modal']) ?>
            <?= Html::button('清空签名', ['class' => 'btn btn-info rb-sign-clear']) ?>
        </div>
    </div>


    <div aria-labelledby="myLargeModalLabel" role="dialog" tabindex="-1" class="modal fade rb-btn-modal-show">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                    <h4 id="myLargeModalLabel" class="modal-title">手机验证</h4>
                </div>
                <div class="modal-body">

                    <div class="contract-sign-form">

                        <?php $form = ActiveForm::begin(['id'=>'ContractSign']); ?>
                        <div class="hidden">
                            <?= $form->field($model, 'sign_id')->hiddenInput() ?>

                            <?= $form->field($model, 'sign_data')->hiddenInput() ?>
                        </div>

                        <div class="input-group">
                            <span id="basic-addon1" class="input-group-addon">验证码&emsp;</span>
                            <input id="contractsign-code" name="ContractSign[code]" type="text" aria-describedby="basic-addon1" placeholder="验证码" class="form-control input-sm">
                        </div>
                        <div class="input-group" style="padding-top: 10px">
                            <span id="basic-addon1" class="input-group-addon">电子邮箱</span>
                            <input id="contractsign-email" name="ContractSign[email]" type="text" aria-describedby="basic-addon1" placeholder="电子邮箱" class="form-control input-sm">
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary rb-modal-save">确定</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>

</div>


<script>
    $(document).ready(function() {
        $('.rb-btn-modal').on('click',function(e){
            //生成签名数据
            createSign();

            //发送请求手机验证码
            var url = '/contract-sign/code?cno=<?php echo $_REQUEST['cno'] ?>';
            var data = $('#contract-sign').serialize();
            $.ajax({
                url:url,
                type:'POST',
                async: false,
                data:data,
                dataType:'json',
                success: function(data){
                    console.info(data);
                    //alert( "Data Saved: " + msg );
                    if(data && data.status=='successful'){
                        $('#contractsign-code').val(data.data.code);
                        $('#contractsign-sign_id').val(data.data.sign_id);
                    }
                }
            });

            $('.rb-btn-modal-show').modal('show');
        });

        $('.rb-modal-save').on('click',function(e){
            //数据验证

            var modal = $(this).closest('.modal');
            var form = $(modal).find('form');
            //发送数据
            $.ajax({
                'type': 'POST',
                'url': form.attr('action'),
                'cache': false,
                'async': false,
                'dataType': 'json',
                'data':form.serialize(),
                'success': function (data) {
                    console.info(data);
                    if(data && data.status=='successful'){
                        //操作成功
                        alert('签名操作成功');
                        window.location = '<?= $wxService->mainPage ?>'
                    }
                    else{
                        //操作失败
                        alert('签名操作失败，原因:'+ data.messages);
                    }
                },
                'error': function (xhr, err, obj) {
                    console.info(xhr, err, obj);
                }
            });


        });

        $("#signature").jSignature({color:"#00f",
            height:'200px',
            width:'100%',
            lineWidth:5
        });

        $('.sign-export').on('click',function(e){
            var datapair = $("#signature").jSignature("getData", "svgbase64");
            var i = new Image();
            i.src = "data:" + datapair[0] + "," + datapair[1];
            $(i).appendTo($("#show")); // append the image (SVG) to DOM.
        });

        $('.rb-sign-clear').on('click',function(e){
            $('#signature').jSignature('clear')
        });

        var createSign = function(e){
            var datapair = $("#signature").jSignature("getData", "svgbase64");
            var data = "data:" + datapair[0] + "," + datapair[1];
            $('#contractsign-sign_data').val(data);
        }

        $('.rb-sign-save').on('click',function(e){
            createSign();
        });
        //$("#signature").jSignature("setData", "<?php echo $img_data ?>");;
    })
</script>


<script src="/jsignature/src/jSignature.js"></script>
<script src="/jsignature/src/plugins/jSignature.CompressorBase30.js"></script>
<script src="/jsignature/src/plugins/jSignature.CompressorSVG.js"></script>
<script src="/jsignature/src/plugins/jSignature.UndoButton.js"></script>
<script src="/jsignature/src/plugins/signhere/jSignature.SignHere.js"></script>