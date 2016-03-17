<style type="text/css">

</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=dBxTK0dWcxbmLLiEVGlhH3E0"></script>
<div class="container-fluid">
    <div id="allmap" class="row-fluid" style="height: 300px;border: 1px solid red">
        
    </div>
    <button id="getLocation" class="btn btn_primary">getLocation</button>

</div>


<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!--<script type="text/javascript" src="/wx/demo.js"></script>-->
<script>
    $(function(){
        // 百度地图API功能
        var map = new BMap.Map("allmap");
        var point = new BMap.Point(116.331398,39.897445);
        map.centerAndZoom(point,15);

        map.addEventListener("tilesloaded",tilesloaded_func);
        map.addEventListener("load",function(){alert("地图加载完毕load");});
        map.addEventListener("click", showInfo);

        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                map.addOverlay(mk);
                map.panTo(r.point);
                //alert('您的位置：'+r.point.lng+','+r.point.lat);
            }
            else {
                alert('failed'+this.getStatus());
            }
        },{enableHighAccuracy: true})

        function tilesloaded_func(){
            alert("地图加载完毕tilesloaded");
            map.removeEventListener("tilesloaded",tilesloaded_func);
        }

        function showInfo(e){
            alert(e.point.lng + ", " + e.point.lat);
        }

    });

</script>