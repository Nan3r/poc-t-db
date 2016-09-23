<?php if (!defined('THINK_PATH')) exit();?><head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta http-equiv="refresh" content="5"> 
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap-combined.min.css" type="text/css" />
<style>
li a{display:block;color:#fff;}
li a:hover{background:#85144b;color:000;}
</style>
</head>

<div class="container-fluid">
	<div class="row1-fluid">
		<div class="span12">
			<ol class="inline">
			<?php if(is_array($poc_num)): $i = 0; $__LIST__ = $poc_num;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
					<a href="/display1/index.php/Home/Index/poc/name/<?php echo ($v["poc_name"]); ?>" target="_blank"><strong><?php echo ($v["poc_name"]); ?>(<?php echo ($v["num"]); ?>)</strong></a>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ol><hr width=1500px/>
			
		</div>
	</div>
	<div class="row-fluid">
		<div class="span6">
			<table class="table table-bordered table-hover">
				<thead>
					<tr class="success">
						<th>
							Num
						</th>
						<th>
							POC
						</th>
						<th>
							Result
						</th>
						<th>
							TYPE
						</th> 
					</tr>
				</thead>
				<tbody>
				<?php if(is_array($poc_detail)): $i = 0; $__LIST__ = $poc_detail;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="<?php echo ($v["color"]); ?>">
						<td>
							<?php echo ($v["id"]); ?>
						</td>
						<td>
							<?php echo ($v["poc_name"]); ?>
						</td>
						<td>
							<?php echo ($v["result"]); ?>
						</td>
						<td>
							<?php echo ($v["poc_type"]); ?>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>


				</tbody>
			</table>
		</div>
		<div class="span6">
			<h1>
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
			</dl><img height="642" width="642" src="/display1/Public/image/main1.png" />
		</div>
	</div>
</div>