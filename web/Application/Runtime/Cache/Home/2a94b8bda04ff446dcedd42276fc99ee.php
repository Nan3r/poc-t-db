<?php if (!defined('THINK_PATH')) exit();?><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap-combined.min.css" type="text/css" />
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/js/echarts.simple.min.js" type="text/js" />
<script src="/display1/Public/dist/js/echarts.simple.min.js"></script>
<script src="/display1/Public/dist/js/jquery-3.1.0.min.js"></script>
</head>

<div class="container-fluid">
	<div class="row-fluid">
		<div style="width:40%;float:left;">
			<img height="42" width="642" src="/display1/Public/image/main.jpg" />
		</div>
		<div id="main" style="width: 960px;height:400px;float:right;"></div>
		<div style='width:642px;height:90px;line-height:100px;overflow:hidden;text-align:center;background-color:#111111;font-size:80px;float:left;'><a href="/display1/index.php/Home/Index/main">SYSTEM</a></div>
		<div id="main1" style="width: 700px;height:400px;float:right;"></div>
		<div  class="span6">
			<h1 align="left">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The Chickens System
			</h1>
			<dl class="dl-horizontal">
				<dt>
					Author
				</dt>
				<dd>
					Nan3r
				</dd>
				<dt>
					Environment
				</dt>
				<dd>
					<?php echo ($info); ?>
				</dd>
				<dt>
					<span class="short_text" id="result_box"><span>Development</span></span>
				</dt>
				<dd>
					ThinkPHP+Mysql+Bootstrap+ECharts
				</dd>
				<dt>
					<span class="short_text" id="result_box"><span>Introduction</span></span>
				</dt>
				<dd>
					整个系统通过饼状图展示漏洞情况，实时更新抓鸡情况
				</dd>
			</dl>	
		</div>
		
	</div>
	
</div>
    <script type="text/javascript">
        var myChart1 = echarts.init(document.getElementById('main1'));
        myChart1.setOption({
            title: {
                text: '南丁格尔玫瑰图',
                x: 'center'
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: []
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {
                        show: true
                    },
                    dataView: {
                        show: true,
                        readOnly: false
                    },
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true
                    },
                    saveAsImage: {
                        show: true
                    }
                }
            },
            calculable: true,
            series: [{
                name: '模式',
                type: 'pie',
                radius: [30, 180],
                center: ['60%', '50%'],
                roseType: 'area',
                itemStyle: {
                    normal: {
                       label: {
                          textStyle: {
                             // 用 itemStyle.normal.label.textStyle.fontSize 來更改餅圖上項目字體大小
                             fontSize: 20
                          }
                       }
                    }},
                data: [{
                    value: "",
                    name: ""
                }, {
                    value: "",
                    name: ""
                }, {
                    value: "",
                    name: ""
                }]
            }]

        });
        myChart1.showLoading();
        $.get("/display1/index.php/Home/Index/json/type/poc_type", function(msg) {
        	myChart1.hideLoading();
            for (var i in msg) {
                myChart1.setOption({series:[{
                  data:msg
                }]});
            }
        },"json");
    </script>
    <script type="text/javascript">
        var myChart = echarts.init(document.getElementById('main'));
        myChart.on('click', function (params) {
            window.open('/display1/index.php/Home/Index/poc/name/<?php echo ($v["poc_name"]); ?>/' + encodeURIComponent(params.name));
        });
        myChart.setOption({
            title: {
                text: '南丁格尔玫瑰图',
                x: 'center'
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: []
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {
                        show: true
                    },
                    dataView: {
                        show: true,
                        readOnly: false
                    },
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {
                        show: true
                    },
                    saveAsImage: {
                        show: true
                    }
                }
            },
            calculable: true,
            series: [{
                name: '模式',
                type: 'pie',
                radius: [30, 180],
                center: ['50%', '50%'],
                roseType: 'area',
                itemStyle: {
                    normal: {
                       label: {
                          textStyle: {
                             // 用 itemStyle.normal.label.textStyle.fontSize 來更改餅圖上項目字體大小
                             fontSize: 20
                          }
                       }
                    }},
                data: [{
                    value: "",
                    name: ""
                }, {
                    value: "",
                    name: ""
                }, {
                    value: "",
                    name: ""
                }]
            }]

        });
        myChart.showLoading();
        $.get("/display1/index.php/Home/Index/json/type/poc", function(msg) {
        	myChart.hideLoading();
            for (var i in msg) {
                myChart.setOption({series:[{
                  data:msg
                }]});
            }
        },"json");
    </script>