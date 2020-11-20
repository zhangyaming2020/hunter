$(document).ready(function(){
	$(function(){
		var markerArr = [  
                    { title: "洪都砂场", point: "115.919149,28.723532",address: "联系人：王建波", tel: "电话：13870065192"},  
                    { title: "国集砂场", point: "115.911508,28.769995",address: "联系人：王建波", tel: "电话：13870065192" }
                ];  
  
                function map_init() {  
                    var map = new BMap.Map("map1");
                    var point = new BMap.Point(115.9433814,28.754932); 
                    map.centerAndZoom(point, 13);
                    map.enableScrollWheelZoom(true);
                    //向地图中添加缩放控件  
                    var ctrlNav = new window.BMap.NavigationControl({  
                        anchor: BMAP_ANCHOR_TOP_LEFT,  
                        type: BMAP_NAVIGATION_CONTROL_LARGE  
                    });  
                    map.addControl(ctrlNav);  
  
                    //向地图中添加缩略图控件  
                    var ctrlOve = new window.BMap.OverviewMapControl({  
                        anchor: BMAP_ANCHOR_BOTTOM_RIGHT,  
                        isOpen: 1  
                    });  
                    map.addControl(ctrlOve);  
  
                    //向地图中添加比例尺控件  
                    var ctrlSca = new window.BMap.ScaleControl({  
                        anchor: BMAP_ANCHOR_BOTTOM_LEFT  
                    });  
                    map.addControl(ctrlSca); 

                    var point = new Array(); //存放标注点经纬信息的数组  
                    var marker = new Array(); //存放标注点对象的数组  
                    var info = new Array(); //存放提示信息窗口对象的数组  
                    for (var i = 0; i < markerArr.length; i++) {  
                        var p0 = markerArr[i].point.split(",")[0]; //  
                        var p1 = markerArr[i].point.split(",")[1]; //按照原数组的point格式将地图点坐标的经纬度分别提出来  
                        point[i] = new window.BMap.Point(p0, p1); //循环生成新的地图点  
                        marker[i] = new window.BMap.Marker(point[i]); //按照地图点坐标生成标记  
                        map.addOverlay(marker[i]);  
                        marker[i].setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画  
                        var label = new window.BMap.Label(markerArr[i].title, { offset: new window.BMap.Size(20, -10) });  
                        marker[i].setLabel(label);  
                        info[i] = new window.BMap.InfoWindow("<p style=’font-size:12px;lineheight:1.8em;’>" + markerArr[i].title + "</br>地址：" + markerArr[i].address + "</br> 电话：" + markerArr[i].tel + "</br></p>"); // 创建信息窗口对象  
                    }  
                    marker[0].addEventListener("mouseover", function () {  
                        this.openInfoWindow(info[0]);  
                    });  
                    marker[1].addEventListener("mouseover", function () {  
                        this.openInfoWindow(info[1]);  
                    });  
                    marker[2].addEventListener("mouseover", function () {  
                        this.openInfoWindow(info[2]);  
                    });  
                } 
	});
});
