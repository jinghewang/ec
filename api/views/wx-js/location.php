<style type="text/css">

</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=95Wmm1kVcVA3BI2mCLP2EVKa"></script>
<div class="container-fluid">
    <div id="fakeloader" ></div>
    <div class="uz-panel hidden">
        <div class="uz-panel-body" style="padding-bottom: 0px;">
            <input id="checkJsApi" type="button" value="checkJsApi"><br>
            <input id="onMenuShareAppMessage" type="button" value="onMenuShareAppMessage"><br>
            <input id="getNetworkType" type="button" value="getNetworkType"><br>
            <input id="scan" type="button" value="scan"><br>
            <button id="openLocation" class="btn btn_primary">openLocation</button>
            <button id="getLocation" class="btn btn_primary">getLocation</button>
        </div>
    </div>
</div>


<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!--<script type="text/javascript" src="/wx/demo.js"></script>-->
<script>
    wx.config({
        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?php echo $appid ?>', // 必填，公众号的唯一标识
        timestamp: <?php echo $timestamp ?>, // 必填，生成签名的时间戳
        nonceStr: '<?php echo $nonce ?>', // 必填，生成签名的随机串
        signature: '<?php echo $sign['sign'] ?>',// 必填，签名，见附录1
        jsApiList: [  'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });

    wx.ready(function(){
        // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
        console.info('ready');

        document.querySelector('#checkJsApi').onclick = function () {
            wx.checkJsApi({
                jsApiList: [
                    'getNetworkType',
                    'previewImage'
                ],
                success: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        };

        // 7.2 获取当前地理位置
        //方式1：
        //document.querySelector('#getLocation').onclick = getLocation;

        //方式2：
        //getLocation();

        //方式3：
        wx.getLocation({
            success: function (res) {
                //data = JSON.stringify(res);
                //alert(JSON.stringify(res));
                wx.openLocation({
                    latitude: res.latitude,
                    longitude: res.longitude,
                    name: '北京市',
                    address: '北京市',
                    scale: 14,
                    infoUrl: 'http://weixin.qq.com'
                });
            },
            cancel: function (res) {
                alert('用户拒绝授权获取地理位置');
            }
        });

    });

    wx.error(function(res){
        // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
        console.info(res);
    });

    var getLocation = function () {
        var data = null;
        wx.getLocation({
            success: function (res) {
                //data = JSON.stringify(res);
                //alert(JSON.stringify(res));

                alert('ddd--3');

                wx.openLocation({
                    latitude: res.latitude,
                    longitude: res.longitude,
                    name: '北京市',
                    address: '北京市',
                    scale: 14,
                    infoUrl: 'http://weixin.qq.com'
                });
            },
            cancel: function (res) {
                alert('用户拒绝授权获取地理位置');
            }
        });
    };

</script>