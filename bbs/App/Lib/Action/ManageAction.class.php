<?php
// 本类由系统自动生成，仅供测试用途
class ManageAction extends ManageCommonAction {
    

    
    
    public function index(){
	
        $this->forum();
    
    }
    public function forum(){
        
        
        $id = $this->_request("id","intval",0);
        
        $where = "";
        
        if($id == 0){
            
            $where = 1;
        }else{
            
            $where = "f.category_id = '$id'";
        }
        
        $sql = "SELECT
                	f.id,
                	f.title,
                	left(f.content,60) text,
                	f.image,
                	f.isshow,
                	f.istop,
                    f.`status`,
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
                LIMIT 10";
        $data = M()->query($sql);
        
        $this->assign("title","话题管理");
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
        
        $limit = $page*10 .',10 ' ;
        
        if($id == 0){
            
            $where = 1;
        }else{
            
            $where = "f.category_id = '$id'";
        }
        
        
        
        $sql = "SELECT
                	f.id,
                	f.title,
                	left(f.content,30) text,
                	f.image,
                	f.isshow,
                	f.istop,
                    f.`status`,
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
    
        $limit = $page*10 .',10 ' ;
    
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
        $this->assign("title","分类管理");
        $this->display("category");
        
    }
    public function category_add(){

        if($this->isPost()){
            
            
            $data['title'] = $this->_post("title","trim","");
            $data['description'] = $this->_post("description","trim","");
            $data['status'] = 1;
            
            $bool = M("category")->add($data);
            
            if($bool){
                //添加日志
                $this->manage_log("添加分类:".$data['title'], "分类添加");
                $this->redirect("category");
                
                
            }
            
        }else{
            $this->assign("title","分类添加");
            $this->display("category-add");
        }
        
    }
    public function category_edit(){
        
        if($this->isPost()){
        
            $id = $this->_post("id","intval",0);
            $data['title'] = $this->_post("title","trim","");
            $data['description'] = $this->_post("description","trim","");
        
            $bool = M("category")->where("id = '$id'")->save($data);
        
            if($bool){
                //添加日志
                $this->manage_log("编辑分类:".$data['title'], "分类编辑");
                $this->redirect("category");
            }
        
        }else{
            $id = $this->_get("id","intval",0);
            $data = M("category")->field("id,title,description")->where("id = '$id'")->select();
            
            
            $this->assign("data",$data[0]);
            $this->assign("title","分类编辑");
            $this->display("category-edit");
        }
        
        
    }
    public function category_del(){
        $id = $this->_get("id","intval",0);
        
        $count = M("forum")->where("category_id = '$id'")->count();
        
        if($count>0){
            
            die();
        }
        
        $category = M("category")->where("id = '$id'")->getField("title");
        
        
        $bool = M("category")->where("id = '$id'")->delete();
        //添加日志
        $this->manage_log("删除分类:".$category, "分类删除");
        echo $id;
    }
    
    
    public function detail(){
        
        
        $id = intval($_REQUEST['id']);
        if($id == 0){
            
            $this->faild();
            die;
        }
        
        $sql = "SELECT f.*,u.`name`, u.avatar FROM forum f LEFT JOIN USER u ON f.username = u. USER WHERE f.id = '$id' LIMIT 1";
        
        $data = M()->query($sql);
        
        
        if(!$data){
            
            $this->faild();
            exit;
        }
        
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
                        GROUP BY
                        	f.id
                        ORDER BY
                        	id ASC
                        LIMIT 0,
                         10
                        ";
        
            
        $comment = M()->query($comment_sql);
        
        $count = M("forum_comment")->where("forum_id = '$id' and  p_id = 0" )->count();
        
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
        
        echo $id;
        
    }
    public function forum_edit(){
        
        if($this->isPost()){
            
            $category_id = $this->_post("category_id","intval",1);
            $id = $this->_post("id","intval",0);
            
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
            
            $id = M("forum")->where("id = '$id'")->save($data);
            //添加日志
            $this->manage_log("编辑话题".$data['title'], "话题编辑");
            
        }else{
            
            $category = M("category")->where("status = 1")->order("sort asc")->select();
            
            $id = $this->_request("id","intval",0);
            $data = M("forum")->field("id,title,content,category_id,isshow")->where("id = '$id'")->select();
            
            $this->assign("title","话题修改");
            $this->assign("category",$category);
            $this->assign("data",$data[0]);
            $this->display("forum-edit");
            
        }
        
    }
    public function forum_delete(){
        
        $id = $this->_request("id","intval",0);
        $forum_title = M("forum")->where("id = '$id'")->getField("title");
        $bool = M("forum")->where("id = '$id'")->delete();
        if($bool){
            M("forum_comment")->where("forum_id = '$id'")->delete();
            echo $id;
            //添加日志
            $this->manage_log("删除话题:".$forum_title, "话题删除");
        }
        
    }
    public function forum_status(){
        
        $id = $this->_request("id","intval",0);
        $sql = "UPDATE forum set `status` = not `status` where id = '$id'";
        $bool = M()->query($sql);
        $forum_title = M("forum")->where("id = '$id'")->getField("title");
        echo 1;
        //添加日志
        $this->manage_log("审核话题:".$forum_title, "话题审核");
        
    }
    public function forum_top(){
        
        $id = $this->_request("id","intval",0);
        $sql = "UPDATE forum set `istop` = not `istop` where id = '$id'";
        
        $bool = M()->query($sql);
        $forum_title = M("forum")->where("id = '$id'")->getField("title");
        echo 1;
        $this->manage_log("置顶话题:".$forum_title, "话题置顶");
       
        
    }
    public function forum_add(){
        
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
        
        //print_r($_FILES);
        genRandomString(13);
        
        $path = "./Public/Uploads/".date(Ym,time()).'/';
        $name = $id.'_'.genRandomString(10).'.jpg';
        $file = $path.$name;
        $url = date(Ym,time()).'/'.$name;
        
        if(count($_FILES) > 0){
            
            if( createpath($path) ){
            
                move_uploaded_file($_FILES['files']['tmp_name'][0], $file);
                M("forum")->where("id = $id")->setField("image",$url);
                die("上传完成!");
                
            
            }else{
                
                die("目录不存在!");
            }
            
            
        }else{
            
            die("上传完成!");
        }
        
        
        
    }
    
    public function comment_edit(){
        
        $id = $this->_request("id","intval",0);
        $data['content'] = $this->_post("content","strip_tags","");
        $comment = M("forum_comment")->where("id = '$id'")->getField("content");
        $bool = M("forum_comment")->where("id = '$id'")->save($data);
        
        if($bool){
            $this->manage_log("编辑评论:".$comment, "评论编辑");
            $this->redirect("category");
        }
        
    }
    public function comment_delete(){
        
        $id = $this->_request("id","intval",0);
        $comment = M("forum_comment")->where("id = '$id'")->getField("content");
        $bool = M("forum_comment")->where("id = '$id'")->delete();

        $this->manage_log("删除评论:".$comment, "评论删除");
    }
    public function analysis(){
        
        
        
        
        $data['forum_count'] = M("forum")->count();
        
        $data['forum_comment'] =M("forum_comment")->count();
        
        
        
        $this->assign("data",$data);
        $this->display("analysis");
        
        
    }
    public function system(){
        
        $this->display("system");
        
    }
    
    public function search(){
        
        
        $this->faild();
        
    }
    public function faild(){
        
        $this->display("faild");
    }
    public function logs(){
        
        
        if($this->isPost()){
            
            $date_start = $this->_post("date_start","trim","");
            $date_end = $this->_post("date_end","trim","");
            $page = $this->_post("page","intval",1);
            
            
            $where = " 1 ";
            $limit = "";
            
            if($date_start != ""){
                
                $time = strtotime("$date_start");
                $where .= " and createtime > $time";
            }
            if($date_end != ""){
                
                $time = strtotime("$date_end");
                $where .= " and createtime < $time";
            }
            
            $limit = $page*10 .',10 ' ;
            
            
            $data = M("log")->where($where)->order("id desc")->limit($limit)->select();
            
            $this->assign("data",$data);
            $html = $this->fetch("logs_fetch");
            
            die($html);
            
            
        }
        $data = M("log")->order("id desc")->limit("0,10")->select();
        
        $this->assign("data",$data);
        $this->display("logs");
        
    }
    private function manage_log($info,$type){
        
        $log['createtime'] = time();
        $log['operator'] = session("username");
        $log['info'] = $info;
        $log['type'] = $type;
        
        M("log")->add($log);
        
    }
    
    
}