<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends CommonAction {
    public function index(){
	
        $this->forum();
    
    }
    public function forum(){
        
        
        $id = $this->_request("id","intval",0);
        
        $where = "";
        
        if($id == 0){
            
            $where = " 1 and `status` = 1 ";
        }else{
            
            $where = "f.category_id = '$id' and `status` = 1";
        }
        
        $sql = "SELECT
                	f.id,
                	f.title,
                	left(f.content,60) text,
                	f.image,
                	f.isshow,
                	f.istop,
                	f.create_date,
                	f.view_count,
                	u.name,
                	u.avatar,
                	count(fc.id) cc
                FROM
                	forum AS f
                LEFT JOIN forum_comment fc ON f.id = fc.forum_id
                LEFT JOIN USER u ON f.username = u. USER
                WHERE
                	".$where."
                GROUP BY
                	f.id
                ORDER BY
                	istop DESC,
                	id DESC
                LIMIT 20";
        $data = M()->query($sql);
        
        $this->assign("title","发现");
        $this->assign("default_avatar",c("default_avatar"));
        $this->assign("id",$id);
        $this->assign("data",$data);
        $this->display("forum");
        
    }
    public function listajax(){
        
        $id = $this->_post("id","intval",0);
        $page = $this->_post("page","intval",1);
        
        $where = "";
        $limit = "";
        
        $limit = $page*20 .',10 ' ;
        
        if($id == 0){
            
            $where = " 1 and `status` = 1 ";
        }else{
            
            $where = "f.category_id = '$id' and `status` = 1 ";
        }
        
        
        
        $sql = "SELECT
                	f.id,
                	f.title,
                	left(f.content,30) text,
                	f.image,
                	f.isshow,
                	f.istop,
                	f.create_date,
                	f.view_count,
                	u.name,
                	u.avatar,
                	count(fc.id) cc
                FROM
                	forum AS f
                LEFT JOIN forum_comment fc ON f.id = fc.forum_id
                LEFT JOIN USER u ON f.username = u. USER
                WHERE
                	".$where."
                GROUP BY
                	f.id
                ORDER BY
                	istop DESC,
                	id DESC
                LIMIT ".$limit;
        $data = M()->query($sql);
        
        $this->assign("title","发现");
        $this->assign("default_avatar",c("default_avatar"));
        $this->assign("data",$data);
        
        $html = $this->fetch("ajax-forum");
        die($html);
    }
    
    public function ajax_comment(){
        
        $id = $this->_post("id","intval",0);
        $page = $this->_post("page","intval",1);
        
        $limit = "";
        
        $limit = $page*20 .',20 ' ;
        
        $comment_sql = "SELECT
                        	f.*, count(f2.id) ct,u.`name`,u.avatar
                        FROM
                        	forum_comment f
                        LEFT JOIN `forum_comment` f2 ON f.id = f2.p_id
                        LEFT JOIN `user` u ON f.username = u.`user`
                        
                        WHERE
                        	f.forum_id = '$id'
                        	and f.p_id = 0
                        GROUP BY
                        	f.id
                        ORDER BY
                        	id ASC
                        LIMIT 
                        ". $limit;
        
        $comment_sql= "SELECT
                        f.*, u2.`name` cm_name,
                        u.`name`,
                        u.avatar
                        FROM
                        forum_comment f
                        LEFT JOIN `forum_comment` f2 ON f.p_id = f2.id
                        LEFT JOIN `user` u ON f.username = u.`user`
                        LEFT JOIN `user` u2 ON f2.username = u2.`user`
                        WHERE
                        f.forum_id = '$id'
                        GROUP BY
                        f.id
                        ORDER BY
                        id ASC
                        LIMIT ". $limit;
        
                
        $comment = M()->query($comment_sql);
        
        $this->assign("comment",$comment);
        $this->assign("default_avatar",c("default_avatar"));
        $html = $this->fetch("ajax-comment");
        die($html);
        
        
    }
    public function category(){
        
        $sql = "select c.*, count(f.id) as ct from category as c left join forum as f on c.id = f.category_id where 1 GROUP BY c.id order by c.sort asc limit 10 " ;       
        
        $data = M()->query($sql);
        
        $this->assign("data",$data);
        $this->assign("title","话题分类");
        $this->display("forum-category");
        
    }
    public function detail(){
        
        
        $id = intval($_REQUEST['id']);
        if($id == 0){
            
            $this->faild();
            die;
        }
        
        $username = session("username");
        
        $sql = "SELECT f.*,u.`name`, u.avatar FROM forum f LEFT JOIN USER u ON f.username = u. USER WHERE (`status` = 1 or f.username = '$username')  and  f.id = '$id' LIMIT 1";
        
        $data = M()->query($sql);
        
        if(!$data){
            
            $this->faild();
            exit;
        }
        
        $comment_sql = "SELECT
                        	f.*, count(f2.id) ct,u.`name`,u.avatar
                        FROM
                        	forum_comment f
                        LEFT JOIN `forum_comment` f2 ON f.id = f2.p_id
                        LEFT JOIN `user` u ON f.username = u.`user`
                        
                        WHERE
                        	f.forum_id = '$id'
                        	and f.p_id = 0
                        GROUP BY
                        	f.id
                        ORDER BY
                        	id ASC
                        LIMIT 0,20
                        ";
        $comment_sql = "SELECT
                        f.*, u2.`name` cm_name,
                        u.`name`,
                        u.avatar
                        FROM
                        forum_comment f
                        LEFT JOIN `forum_comment` f2 ON f.p_id = f2.id
                        LEFT JOIN `user` u ON f.username = u.`user`
                        LEFT JOIN `user` u2 ON f2.username = u2.`user`
                        WHERE
                        f.forum_id = '$id'
                        ORDER BY
                        id ASC
                        LIMIT 0,
                        20
                        ";
        
            
        $comment = M()->query($comment_sql);
        
        
        $count = M("forum_comment")->where("forum_id = '$id' " )->count();
        
        $bool = M("forum")->where("id = $id")->setInc('view_count');
        
//         echo M("forum")->getLastSql();die;
//          print_r($comment);die;
        
        $this->assign("count",$count);
        $this->assign("data",$data[0]);
        $this->assign("comment",$comment);
        $this->assign("default_avatar",c("default_avatar"));
        $this->display("forum-detail");
        
    }
    
    public function comment(){
        
        $content = $this->_post("content","trim","");
        $category_id = $this->_post("category_id","intval",1);
        $forum_id = $this->_post("forum_id","intval",1);
        $pid = $this->_post("pid","intval",0);
//         echo $pid;die;
        
        
        $create_date = time();
        $username = session("username");
        
        $data = array();
        
        $data['content'] = $content;
        $data['category_id'] = $category_id;
        $data['forum_id'] = $forum_id;
        $data['p_id'] = $pid;
        $data['create_date'] = $create_date;
        $data['username'] = $username;
        
        $id = M("forum_comment")->add($data);

        $comment_sql= "SELECT
                        f.*, u2.`name` cm_name,
                        u.`name`,
                        u.avatar
                        FROM
                        forum_comment f
                        LEFT JOIN `forum_comment` f2 ON f.p_id = f2.id
                        LEFT JOIN `user` u ON f.username = u.`user`
                        LEFT JOIN `user` u2 ON f2.username = u2.`user`
                        WHERE
                        f.id = '$id'
                        LIMIT 1";
        
        
        $comment = M()->query($comment_sql);
        
        $this->assign("comment",$comment);
        $this->assign("default_avatar",c("default_avatar"));
        $html = $this->fetch("ajax-comment");
        die($html);
        
        
    }
    public function comment_detail(){
        
        $id = $this->_request("id","intval",0);
        
        $sql = "select fc.*,u.`name`,u.avatar from forum_comment fc left JOIN `user` u on fc.username = u.`user` where fc.id = '$id' ";
        
        $data = M()->query($sql);
        
        $sql = "select fc.*,count(f2.id) ct ,u.`name`,u.avatar from forum_comment fc LEFT JOIN `forum_comment` f2 ON fc.id = f2.p_id LEFT JOIN `user` u ON fc.username = u.`user` where fc.p_id = '$id' GROUP BY fc.id ORDER BY fc.id LIMIT 0, 20";
        $comment = M()->query($sql);
        
        $count = M("forum_comment")->where("p_id = $id")->count();
        
        $this->assign("count",$count);
        $this->assign("data",$data[0]);
        $this->assign("comment",$comment);
        $this->assign("default_avatar",c("default_avatar"));
        $this->display("comment-detail");
        
    }
    
    public function release(){
        
        
        $category = M("category")->where("status = 1")->order("sort asc")->select();
        
        $this->assign("title","发表话题");
        $this->assign("category",$category);
        $this->display("forum-release");
        
    }
    public function release_process(){
        
        
        
        $category_id = $this->_post("category_id","intval",1);
        
//         echo $category_id;die;
        $title = $this->_post("title","strip_tags","");
        $content = $this->_post("content","strip_tags","");
        $isshow = $this->_post("isshow","intval","");
        
        if($title == "" || $content == ""){
            
            die("不能为空!");
            
        }
        
        $data = array();
        $data['title'] = $title;
        $data['content'] = $content;
        $data['category_id'] = $category_id;
        $data['isshow'] = $isshow;
        $data['create_date'] = time();
        $data['username'] = session("username");
        
        $id = M("forum")->add($data);
        
        M("forum")->where("id = $id")->setField("sort",$id);
        
        //print_r($_FILES);
        genRandomString(13);
        
        $path = "./Public/Uploads/".date(Ym,time()).'/';
        
        if(count($_FILES) > 0){
            
            
//             print_r($_FILES);die;
            if( createpath($path) ){
            
                $img_arr = array();
                foreach ($_FILES['files']['error'] as $k=>$v){
                    
                    if(!isimage($_FILES['files']['type'][$k])){
                        
                        continue;
                    }
                    $name = $id.'_'.genRandomString(10).$k.'.jpg';
                    $file = $path.$name;
                    $url = date(Ym,time()).'/'.$name;
                    
                    move_uploaded_file($_FILES['files']['tmp_name'][$k], $file);
                    $img_arr[] = $url;
                    
                }
                $url = implode(',', $img_arr);
                M("forum")->where("id = $id")->setField("image",$url);
                die("上传完成!");
                
            
            }else{
                
                die("目录不存在!");
            }
            
            
        }else{
            
            die("上传完成!");
        }
        
        
        
    }
    
    public function search(){
        
        
        $this->display("search");
        
    }
    public function search_ajax(){
        
        $key = $this->_post("key","trim",'');
        $page = $this->_post("page","intval",0);
        
        
        $where = "";
        $limit = "";
        
        $limit = $page*10 .',10 ' ;
        
        if($key == ""){
            
            return false;
        }
        
        $sql = "select f.*,u.name,u.avatar from forum f left join `user` u on f.username = u.`user`  where f.title like '%{$key}%' limit  ".$limit;
        
        $r= M()->query($sql);
        
        $this->assign("data",$r);
        $data = $this->fetch("ajax-search");
        
        echo $data;
        
    }
    
    
    public function profile(){
        
        if($this->isPost()){
            
            $page = $this->_post("page","intval",1);
        
            $limit = "";
            
            $limit = $page*10 .',10 ' ;
            
            
            $data = M("forum")->where("username = '".session("username")."'")->limit($limit)->select();
            
            $this->assign("data",$data);
            $html = $this->fetch("profile_ajax");
            die($html);
            
        }
        
        $user = M("user")->where("user = '".session("username")."'")->select();
//         $sql = "select f.*,u.name,u.avatar from forum f left join `user` u on f.username = u.`user`  where f.username = '".session("username")."' limit 0,10 ";

//         echo $sql;die;
//         $data = M()->query($sql);
        $data = M("forum")->where("username = '".session("username")."'")->limit("0,10")->select();
        
        
        $this->assign("user",$user[0]);
        $this->assign("data",$data);
        $this->display("profile");
        
    }
    public function faild(){
        
        $this->display("faild");
    }
    
    
}