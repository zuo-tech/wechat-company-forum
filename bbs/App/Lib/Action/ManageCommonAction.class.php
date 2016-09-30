<?php
/**
 * ==============================================
 * CoomonAction.class.php
 * @author: Tony_wang
 * @date: 2016-7-25
 * @version: v1.0.0
 */
 
class ManageCommonAction extends Action{
    
    function _initialize(){
        
        //微信登陆
        
        if($_SESSION['username']){
            
            if( !authorize(session("username") ) ){
                
                die("you are not authorize!");
                
            }
            
        }else if($_COOKIE['username'] && $_COOKIE['check'] == md5('!@#FASDF'.$_COOKIE['username'])){
            
            $username = $_COOKIE['username'];
            session("username",$username);
            
            if( !authorize(session("username") ) ){
            
                die("you are not authorize!");
                            
            }
            
            
        }else{
            if( isset($_GET['code'])  && $_GET['code'] != ''){
                
                $code = $_GET['code'];
                $getAccessUrl = sprintf(C("tokenUrl"),C("corpid"),C("corpsecret"));
                
                $temp =  curls($getAccessUrl);
                
                $responseToken = json_decode($temp,true);
                
                $accessToken = $responseToken['access_token'];
                
                $agentid = '20';
                
                
                
                $getUserInfoUrl = sprintf(C("useridUrl"),$accessToken,$code,$agentid);
                
                $responseInfo = json_decode( curls($getUserInfoUrl) ,true);
                
                $username = $responseInfo['UserId'];
                
                //后台权限刷新:
                $appInfoUrl = "https://qyapi.weixin.qq.com/cgi-bin/agent/get?access_token=$accessToken&agentid=$agentid";
                
                $appInfo = json_decode( curls($appInfoUrl),true );
                
                $allow_user = $appInfo['allow_userinfos'];
                
                $manage = array();
                foreach ($allow_user['user'] as $v){
                    
                    $manage[] = $v['userid'];
                    
                }
                $manage_config['manage'] = $manage;
                
                F('manage',$manage_config,CONF_PATH);
                
                if($username != ''){
                    
                    $count = M("user")->where("user = '$username'")->count();
                    
                    if($count <= 0){
                    
                        $getUserInfoUrl = sprintf(c("userinfoUrl"),$accessToken,$username);
                    
                        $userInfo = json_decode( curls($getUserInfoUrl) , true);
                    
                        $sql = "INSERT INTO USER
                        (user,name,position,mobile,gender,email,avatar)
                        values (
                            '$userInfo[userid]',
                            '$userInfo[name]',
                            '$userInfo[position]',
                            '$userInfo[mobile]',
                            '$userInfo[gender]',
                            '$userInfo[email]',
                            '$userInfo[avatar]'
                        )";
                            
                        M("user")->query($sql);
                    
                    }
                    
                    
                    setcookie("username",$username,time() + 3600*30);
                    setcookie("check",md5('!@#FASDF'.$username),time() + 3600*30);
                    
                    session("username",$username);
                    if( !authorize(session("username") ) ){
                    
                        die("you are not authorize!");
                                            
                    }
                    
                    
                    $base_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER['PHP_SELF'];
                    header("Location: $base_url");
                }else{
                    die("You don't have permission to access on this service!");
                    
                }
                
                
                
            }else{
                
                $base_url = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER['PHP_SELF'];
                $getCodeUrl = sprintf(c("codeUrl"),c("corpid"),urlencode($base_url));
                header("Location: $getCodeUrl");
                exit;
                
            }
            
            
        }
        
        
    }
    
    
}















?>