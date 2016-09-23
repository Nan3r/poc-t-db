<?php if (!defined('THINK_PATH')) exit();?><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap-combined.min.css" type="text/css" />
<script src="/display1/Public/dist/js/jquery-3.1.0.min.js"></script>
<script src="/display1/Public/dist/js/echarts-all.js"></script>
<script type="text/javascript">  
function goTopEx() {  
    var obj = document.getElementById("goTopBtn");  
    function getScrollTop() {  
        return document.documentElement.scrollTop + document.body.scrollTop;  
    }  
    function setScrollTop(value) {  
        if (document.documentElement.scrollTop) {  
            document.documentElement.scrollTop = value;  
        } else {  
            document.body.scrollTop = value;  
        }  
    }  
    window.onscroll = function() {  
        getScrollTop() > 0 ? obj.style.display = "": obj.style.display = "none";  
    }  
    obj.onclick = function() {  
        var goTop = setInterval(scrollMove, 10);  
        function scrollMove() {  
            setScrollTop(getScrollTop() / 1.1);  
            if (getScrollTop() < 1) clearInterval(goTop);  
        }  
    }  
}  

function getUrl(num){
	var item = document.getElementById("tb");
	var tbody = item.getElementsByTagName("tbody")[0];
	var trs = tbody.getElementsByTagName("tr");
	var url = 'http://ip.taobao.com/service/getIpInfo.php?ip='+trs[num].innerText;
	
}
$.ajax({
    url : url,
    type : "GET",
    dataType : 'json',
    success : function (msg){
        do something....
    }
});
</script>  
<style>  
BODY {  
    HEIGHT: 3600px;  
}  
#goTopBtn {  
    POSITION: fixed; TEXT-ALIGN: center; LINE-HEIGHT: 30px; WIDTH: 55px; BOTTOM: 35px; HEIGHT: 33px; FONT-SIZE: 12px; CURSOR: pointer; RIGHT: 0px; _position: absolute; _right: auto  
}  
</style> 
<style>
li a{display:block;color:#fff;}
li a:hover{background:#85144b;color:000;}
</style>
</head>

<div class="container-fluid">
	<div class="row-fluid">
	
		<div class="span6">
		
			<table class="table table-bordered table-hover" id="tb">
				<thead>
					<tr class="success">
						<th>
							Result
						</th>
					</tr>
				</thead>
				<tbody id="tbody">
				<?php if(is_array($poc)): $i = 0; $__LIST__ = $poc;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="<?php echo ($v["color"]); ?>" id="tr">
						<td><?php echo ($v["result"]); ?></td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>

				</tbody>
			</table>
			
		</div>
		
		<div class="span6">
		
			<h1 class="text-center">
				<?php echo ($poc_introduce['poc_name']); ?>
			</h1>
			<dl class="dl-horizontal">
				<dt >
					<p style="font-size:20px; font-weight:bold;">Num</p>
				</dt>
				<dd>
					<p style="font-size:20px; font-weight:bold;"><?php echo ($poc_introduce['num']); ?></p>
				</dd>
							<dt>
					<p style="font-size:20px; font-weight:bold;">Type</p>
				</dt>
				<dd>
					<p style="font-size:20px; font-weight:bold;"><?php echo ($poc_introduce['poc_type']); ?></p>
				</dd>
				<dt>
					<p style="font-size:20px; font-weight:bold;">Introduction</p>
				</dt>
				<dd>
					<p style="font-size:20px; font-weight:bold;"><?php echo ($poc_introduce['poc_introduce']); ?></p>
				</dd>
			</dl>
			<div id="main" style="width: 800px;height:400px;float:right;"></div>
		</div>
		
	</div>
	
</div>
<script type=text/javascript>goTopEx();</script> 
<script type="text/javascript">
var myChart = echarts.init(document.getElementById('main'));
myChart.setOption({
    title : {
        text: 'IP地区分布'
    },
    tooltip : {
        trigger: 'axis'
    },
    calculable : true,
    xAxis :[{
            type : 'value',
            boundaryGap : [0, 0.01]
        }],
    yAxis :[{
            type : 'category',
            data : [""]
        }],
    series : [
        {
            name:'数量',
            type:'bar',
            data:[""]
        }
    ]
});

$.get("/display1/index.php/Home/Index/json/type/poc_tj/poc_name_tj/<?php echo ($poc_introduce['poc_name']); ?>", function(msg) {
	 for (var i in msg.name) {
        myChart.setOption({
        	yAxis:[{
        		data:msg.name
        	}],
        	series:[{
          		data:msg.num
        	}]
        });
	 }
},"json");
</script>