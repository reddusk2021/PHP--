<?php


include 'safwl/common.php';
if($conf['txprotect'] == 1)
    include_once(SYSTEM_ROOT."txprotect.php");
if($conf['CC_Defender'] == 1){
    define('CC_Defender', 1); //防CC攻击开关(1为session模式)
    include_once SYSTEM_ROOT.'security.php';

}
$checkcip_row = $DB->get_row("select * from safwl_blacklist where data = '".real_ip()."' and type = 2");

if($checkcip_row){
    exit("抱歉，您已列入本站黑名单，无法使用本站!");
}
if($conf['isaccvtion']){
    if( empty($_SESSION['accvtion'])){
        $accvtionc= false;
    }else{
        if(accvtion_check($_SESSION['accvtion'],0)){
             $accvtionc= true;
        }else{
             $accvtionc= false;
        }
    }
    
    if(!empty($_POST['accvtion_check'])){
        $checkpass = _safwl($_POST['pass']);
        if(accvtion_check($checkpass)){
            $_SESSION['accvtion'] = $checkpass;
             $accvtionc= true;
        }else{
            @header('Content-Type: text/html; charset=UTF-8');
            exit("<script language='javascript'>alert('验证密码错误！');history.go(-1);</script>");
            unset($_SESSION['accvtion']);
            $accvtionc= false;
        }
    }
    
    if(!$accvtionc){
        include 'other/accvtion.php';
        exit();
    }
    
}

if(!empty($conf['view']) && $conf['view'] != ""){
    $t = $conf['view'];
}else{
    $t = "default";
}

$_SESSION['createcount'] = 1;
include 'template/'.$t.'/share.php';