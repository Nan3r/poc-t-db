<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if (!isset($_SESSION[user])) {
            $this->redirect("login");
        }
        $info = $_SERVER["SERVER_SOFTWARE"];
        $this->assign("info",$info);
        $this->display();
    }
    
    //随机显示一些数据
    public function main() {
        if (!isset($_SESSION[user])) {
            $this->redirect("login");
        }
        $info = $_SERVER["SERVER_SOFTWARE"];
        $poc = M('poc_kind');
        $sql = 'select poc_name,a.poc_id,count(*) as num from poc_result as a,poc_kind as b where a.poc_id=b.poc_id group by b.poc_id';
        $poc_name_num = $poc->query($sql);
        
        $sql_detail = 'select id,poc_name,poc_type,result from poc_result as a,poc_kind as b where a.poc_id=b.poc_id order by rand() limit 19';
        $poc_detail = $poc->query($sql_detail);
        $color = array("success","info","error","warning");
        foreach ($poc_detail as $key => $value) {
            if(preg_match('/http:\/\/(.*?)\//i',$value['result'],$result)){
                $poc_detail[$key]['result'] = $result[1];
            }
            $poc_detail[$key]['color'] = $color[array_rand($color)];
        }
        
        //显示poc名字和数量
        $this->assign("poc_num",$poc_name_num);
        //显示poc的结果
        $this->assign("poc_detail",$poc_detail);
        $this->assign("info",$info);
        $this->display();
    }
    
    //用于ajax的调用
    function json($type='',$poc_name='',$poc_name_tj=''){
        $nmb = array();
        $poc_kind = M('poc_kind');
        if ($type == 'poc') {
        //是根据poc结果所占比例分类
        //删除重复数据
        $this->delete_repeat();
        $sql = 'select poc_name,count(*) as num from poc_result as a,poc_kind as b where a.poc_id=b.poc_id group by b.poc_id';
        $poc_type_num = $poc_kind->query($sql);
        if ($poc_type_num) {
           foreach ($poc_type_num as $key => $value) {
               $poc_type_num[$key] = array('name' => $value['poc_name'] , 'value'=>$value['num']);
           }
        }
        $nmb = $poc_type_num;
    }elseif ($type == 'poc_type'){
        //是根据poc类型所占比例分类
        $sql = 'select poc_type,count(*) as num from __TABLE__ group by poc_type';
        $poc_type_num = $poc_kind->query($sql);
        if ($poc_type_num) {
           foreach ($poc_type_num as $key => $value) {
               $poc_type_num[$key] = array('name' => $value['poc_type'] , 'value'=>$value['num']);
           }
        }
        $nmb = $poc_type_num;
    }elseif (!empty($poc_name)){
            $poc_detail = $poc_kind->where("poc_name='{$poc_name}'")->find();
            $this->ajaxReturn($poc_detail);
    }elseif ($type == 'poc_tj') {
        $nmb = $this->get_ip_array($poc_name_tj);
    }
        $this->ajaxReturn($nmb);
}
//返回这个poc下所有IP
    function get_ip_array($poc_name_tj) {
        $tmp = array();
        $poc = M('poc_kind');
        $sql = "select result from poc_result as a,poc_kind as b where a.poc_id=b.poc_id and poc_name='{$poc_name_tj}'";
        $res = $poc->query($sql);
        $start_time = time();
        foreach ($res as $value) {
            if (preg_match('/\d{1,3}(\.\d{1,3}){3}/i', $value['result'], $m)) {
                $tmp[] = $this->get_ip_area($m[0]);
            }
            if(time()-$start_time > 15) break;
        }

        $tmp_res = array_count_values($tmp);
        $return_array = array(
            'name' => array_keys($tmp_res),
            'num'=>array_values($tmp_res)
        );
        return $return_array;
    }
  //返回IP所在地
  function get_ip_area($ip) {
      $url = 'http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
      #$url_content = file_get_contents($url,false,$context);
      $url_content = $this->curl_file_get_contents($url);
      $res = json_decode($url_content,true);
      return $res['data']['country'].$res['data']['city'];
  }

  function curl_file_get_contents($durl){
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $durl);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $r = curl_exec($ch);
      curl_close($ch);
       return $r;
}

    //某个poc下的漏洞情况
    function poc($name='',$delete='') {
        $poc = M("poc_result");
        $sql = 'select result from poc_result where poc_id in (select poc_id from poc_kind where poc_name=\''.$name.'\') order by id desc';
        $details = $poc->query($sql);
        $color = array("success","info","error","warning");
        foreach ($details as $key => $value) {
            $details[$key]['color'] = $color[1];
        }
        
        $poc_kind = M('poc_kind');
        $sql_ = "select count(*) as num from poc_result where poc_id in (select poc_id from poc_kind where poc_name='{$name}')";
        $poc_name_count = $poc_kind->query($sql_);
        $poc_name = $poc_kind->where("poc_name='{$name}'")->find();
        $poc_name['num'] = $poc_name_count[0]['num'];

        $this->assign("poc_introduce", $poc_name);
        $this->assign("poc",$details);
        if (!empty($delete)) {
            if($poc->where("result='{$delete}'")->delete()) $this->redirect('',array('name'=>$name));
        }
        $this->display();
    }
    
    //验证登录
    function login() {
        if ($_POST) {
            $pass = $_POST['pass'];
            if ($pass === 'nan3r') {
                $_SESSION[user] ='nan3r';
                $this->redirect("index");
            }
        }
        $this->display();
    }

    //更新poc所属分类及描述
    function update($poc_type='', $poc_introduce='',$poc_name='',$submit='') {
        $poc = M('poc_kind');
        
        if (!empty($poc_introduce) or !empty($poc_type) and !empty($poc_name)) {
            $data = $poc->create(array('poc_type'=>$poc_type,'poc_introduce'=>$poc_introduce));
            if($poc->where("poc_name='{$poc_name}'")->save($data)) $this->redirect();
        }
        $poc_all_name = $poc->select();
        $this->assign("poc_name",$poc_all_name);
        if ($submit == 'delete') {
            $poc->where("poc_name='{$poc_name}'")->delete();
            $this->redirect();
        }
        $this->display();
    }

    //因为插入的时候没有判断，所有就在index里面就进行去重复
    function delete_repeat() {
        $poc = M("poc_result");
        $poc_data = $poc->select();
        foreach ($poc_data as $key => $value) {
            $a = $poc->where("result='{$value['result']}'")->select();
            if(!empty($a)){ //如果查找到值
                $acount=count($a); //计算值的个数
                for($i=0;$i<$acount;$i++){
                    if($i != 0){
                        $poc->where(array('id'=>$a[$i]['id']))->delete();
                    }
                }
            }
        }
    }
}