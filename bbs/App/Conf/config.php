<?php
return array(
	//'配置项'=>'配置值'
        //'SHOW_PAGE_TRACE' => true,
	'db_type' => 'mysql', //数据库连接类型
	'db_host' => 'localhost',
	'db_name' => 'bbs', //数据库名
	//'db_name' => 'secomtel',
	'db_user' => 'root', //数据库用户
	//'db_user' => 'root',
	'db_pwd' => 'root', //数据库连接密码
	//'db_pwd' => '',
	'db_port' => '3306', //数据库连接端口
	'db_prefix' => '', //数据库表前缀
	'auth_key' => '', //全局密钥，GUID
	'var_page' => 'p', //分页变量
    "LOAD_EXT_FILE"=>"function",
	'db_fieldtype_check' => true, //是否开启字段类型检查
	'tmpl_strip_space' => true, //是否去除模板文件里面的html空格与换行
	'default_theme' => 'default', //默认模板主题名称
	'cookiedomain' => '', //cookie域
	'cookiepath' => '/', //cookie存放路径
	'URL_CASE_INSENSITIVE'  =>  false, 
	'image_savepath' => 'Uploads/images/editor/', //编辑器图片保存路径
	'image_allowfiles' => array( ".gif", ".png", ".jpg", ".jpeg", ".bmp" ), //编辑器图片上传允许格式
	'file_savepath' => 'Uploads/files/', //编辑器文件保存路径
	'file_allowfiles' => array( '.jpg', '.gif', '.png', '.jpeg', '.pdf', '.flv', '.doc', '.rar', '.ppt', '.zip', '.xls' ),
	'TMPL_PARSE_STRING' => array(
		'__UPLOAD__' => __ROOT__ . '/Uploads', // 添加输出替换
	),
    'LOAD_EXT_CONFIG' => 'manage',
	'log_record' => false, // 开启日志记录
	'token_on' => true, // 是否开启令牌验证
	'token_name' => '__hash__', // 令牌验证的表单隐藏字段名称
	'token_type' => 'md5', //令牌哈希验证规则 默认为MD5
	'token_reset' => true, //令牌验证出错后是否重置令牌 默认为true
	//'LOG_LEVEL'  	 => 'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
	'OUTPUT_ENCODE' => false, //输出编码，要关闭，否则不能实时输出信息，这个主要用来导入时的输出提示
	'TMPL_STRIP_SPACE'=> false,
    'url_model' => 1, //url模式为重写模式。其他:0-传统的URL参数模式。1:PATHINFO模式（默认模式），3：兼容模式
	'URL_ROUTER_ON' => true, //开启路由
    'corpid'=>'wx634b5f1d1cdd096a',
    'corpsecret'=>'',
    'tokenUrl'=>"https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=%s&corpsecret=%s",
    'codeUrl'=>"https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=SCOPE&state=STATE#wechat_redirect",
    'useridUrl'=>"https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token=%s&code=%s&agentid=%s",
    'userinfoUrl'=>"https://qyapi.weixin.qq.com/cgi-bin/user/get?access_token=%s&userid=%s",
    'default_avatar'=>"http://res.mail.qq.com/bizmp/zh_CN/images/dev/icon_avatar_default.png?",
    'secret_key'=>'',
    
);
?>