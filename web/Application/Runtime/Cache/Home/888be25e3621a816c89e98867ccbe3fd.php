<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap.css" type="text/css" />
<link rel="stylesheet" media="screen"  href="/display1/Public/dist/css/bootstrap-combined.min.css" type="text/css" />
<script src="/display1/Public/dist/js/jquery-3.1.0.min.js"></script>
<script src="/display1/Public/dist/js/bootstrap.min.js"></script>
<style> 
        body{
			background:url(/display1/Public/image/login.jpg) top center no-repeat; background-size:cover;
		}
form{
			width:360px;
			margin: 0 auto;
			position:relative;
			top:43vh;
		}
</style> 
</head>
<body>

<form action="/display1/index.php/Home/Index/update" method="post">
<div class="dropdown">
<select id="sele" name="poc_name" onchange="poc_json();">
	<?php if(is_array($poc_name)): $i = 0; $__LIST__ = $poc_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value ="<?php echo ($v["poc_name"]); ?>"><?php echo ($v["poc_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
</select>
</div>
POC_TYPE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="text" name="poc_type" id="poc_type">
POC_INTRODUCE:
<textarea name="poc_introduce" id="poc_introduce"></textarea>
<input type="submit" name="submit" value="update"><input type="submit"  name="submit" value="delete">
</form> 
 <script type="text/javascript">
 function poc_json(){
 var sele = $('#sele option:selected').text();
 $.ajax({  
	type:'post',      
	url:'/display1/index.php/Home/Index/json',  
	data:{'poc_name':sele},  
	dataType:'json',  
	success:function(data){
		$("#poc_type").val(data.poc_type);
		$("#poc_introduce").val(data.poc_introduce);
	}  
});  
 }
 </script>

</body>
</html>