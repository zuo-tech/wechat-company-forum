<?php
/**
 * ==============================================
 * function.php
 * @author: Tony_wang
 * @date: 2016-7-26
 * @version: v1.0.0
 */
 


function curls($url, $timeout = '10')
{
    // 1. 初始化
    $ch = curl_init();
    // 2. 设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // 3. 执行并获取HTML文档内容
    $info = curl_exec($ch);
    // 4. 释放curl句柄
    curl_close($ch);

    return $info;
}




function rand_zifu($number,$what = 3){
    $string='';
    for($i = 1; $i <= $number; $i++){
        //混合
        $panduan=1;
        if($what == 3){
            if(rand(1,2)==1){
                $what=1;
            }else{
                $what=2;
            }
            $panduan=2;
        }

        //数字
        if($what==1){
            $string.=rand(0,9);
        }elseif($what==2){
            //字母
            $rand=rand(0,24);
            $b='a';
            for($a =0;$a <=$rand;$a++){
                $b++;
            }
            $string.=$b;
        }
        if($panduan==2)$what=3;
    }
    return $string;
}


function genRandomString($len)
{
    $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    );
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱

    $output = "";
    for ($i=0; $i<$len; $i++)
    {
    $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

function createpath($path){
    
    
    if(!is_dir( $path ) ){
        if(!mkdir($path) ){
            return false;
        }else{
            return true;
        }
    }else{
        return true;
        
    }
    
}

function authorize($user){
    
    $manage = c("manage");
    if (in_array($user, $manage)){
        
        return true;
    }else{
        return false;
    }
    
}
function isimage($type){
    
    $allow = array('image/jpeg','image/png','image/gif');
    if(in_array($type, $allow)){
        
        return true;
    }else{
        return false;
    }
    
}
function showimage($url){
    $arr = explode(',', $url);
    $str = '';
    foreach ($arr as $v){
        $str .= '<img class="img-responsive" src="__PUBLIC__/Uploads/'.$v.'" />';
    }
    
    return $str;
    
}


