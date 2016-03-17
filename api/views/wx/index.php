<style type="text/css">

</style>
<div class="container-fluid">
    <div id="fakeloader" ></div>
    <div class="uz-panel hidden">
        <div class="uz-panel-body" style="padding-bottom: 0px;">
            <?php echo $ticket ?><br>
            <?php echo $token ?>

            <input id="checkJsApi" type="button" value="checkJsApi"><br>

            <input id="onMenuShareAppMessage" type="button" value="onMenuShareAppMessage"><br>
            <input id="getNetworkType" type="button" value="getNetworkType"><br>
            <input id="scan" type="button" value="scan"><br>

            <button id="openLocation" class="btn btn_primary">openLocation</button>

            <button id="getLocation" class="btn btn_primary">getLocation</button>



        </div>
    </div>
</div>


<script type="text/javascript">
    $(function(){
       $("#fakeloader").fakeLoader({
            timeToHide:3000, //Time in milliseconds for fakeLoader disappear
            zIndex:999, // Default zIndex
            //spinner:"spinner1",//Options: 'spinner1', 'spinner2', 'spinner3', 'spinner4', 'spinner5', 'spinner6', 'spinner7'
            //bgColor:"#2ecc71", //Hex, RGB or RGBA colors
            //imagePath:"yourPath/customizedImage.gif" //If you want can you insert your custom image
        });
    });
</script>


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

        // 7.1 查看地理位置
        document.querySelector('#openLocation').onclick = function () {
            wx.openLocation({
                latitude: 23.099994,
                longitude: 113.324520,
                name: 'TIT 创意园',
                address: '广州市海珠区新港中路 397 号',
                scale: 14,
                infoUrl: 'http://weixin.qq.com'
            });
        };

        // 7.2 获取当前地理位置
        document.querySelector('#getLocation').onclick = getLocation();

        // 2. 分享接口
        // 2.1 监听“分享给朋友”，按钮点击、自定义分享内容及分享结果接口
        document.querySelector('#onMenuShareAppMessage').onclick = function () {
            wx.onMenuShareAppMessage({
                title: '互联网之子',
                desc: '在长大的过程中，我才慢慢发现，我身边的所有事，别人跟我说的所有事，那些所谓本来如此，注定如此的事，它们其实没有非得如此，事情是可以改变的。更重要的是，有些事既然错了，那就该做出改变。',
                link: 'http://movie.douban.com/subject/25785114/',
                imgUrl: 'http://demo.open.weixin.qq.com/jssdk/images/p2166127561.jpg',
                trigger: function (res) {
                    // 不要尝试在trigger中使用ajax异步请求修改本次分享的内容，因为客户端分享操作是一个同步操作，这时候使用ajax的回包会还没有返回
                    alert('用户点击发送给朋友');
                },
                success: function (res) {
                    alert('已分享');
                },
                cancel: function (res) {
                    alert('已取消');
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
            alert('已注册获取“发送给朋友”状态事件');
        };

        // 6 设备信息接口
        // 6.1 获取当前网络状态
        document.querySelector('#getNetworkType').onclick = function () {
            wx.getNetworkType({
                success: function (res) {
                    alert(res.networkType);
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        };

        // 6 设备信息接口
        // 6.1 获取当前网络状态
        document.querySelector('#scan').onclick = function () {
            wx.scanQRCode({
                needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
                scanType: ["qrCode","barCode"], // 可以指定扫二维码还是一维码，默认二者都有
                success: function (res) {
                    var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
                    alert(result);
                }
            });
        };

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