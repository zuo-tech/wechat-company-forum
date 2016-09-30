/**
 * HTML5多图压缩上传
 * jan_wang 2016/8/16
 */
$(function(){

	$("input[type='file']").change(function() {
		
		var $fileUpload = $(this);
		var img_html = "";
    	if(parseInt($fileUpload.get(0).files.length) > 5){
    		   $.alert({
    			   title:'图片数量不能超过5张',
    			   content:false
    		   });
    		   return false;
    	 }else{
             $("#priview").html("");
        	 
    		 for(i =0 ; i < $fileUpload.get(0).files.length ; i++){

         	        var objUrl = getObjectURL(this.files[i]) ;
         	        //getfileinfo(this.files[i])
                    imagezip(objUrl)
         	       //$("#priview").append("<img src="+ objUrl + " width='50' height='50' />"); 
        	  }


         }


	});

    $("form").submit(function(){

    	if($("input[name=title]").val() == ""){
    		
    		$("input[name=title]").focus();
    		return false;
    	}
    	if($("textarea[name=content]").val() == ""){
    		
    		$("textarea[name=content]").focus();
    		return false;
    	}
    	
    	var formData = new FormData();
		
	    var pr = document.getElementById("priview").childNodes;
	    for(j = 0; j< pr.length; j++){

	    	if(pr[j].src != undefined){
	    		
	    		getfileinfo($("input[type='file']").get(0).files[j]);
	    		//formData.append('files[]', $("input[type='file']").get(0).files[j]);
	    		formData.append('files[]',dataURLtoBlob(pr[j].src));
		    }
	    	
	    	
    	}
	    
	    
		
		formData.append('title',$("input[name=title]").val());
		formData.append('content',$("textarea[name=content]").val());
		formData.append('category_id',$("select[name=category_id]").val());
		formData.append('isshow',$("input[name=isshow]").is(":checked") ? 1 : 0);
	
     	var xhr = new XMLHttpRequest();
		var jc =  $.alert({
     	    title: '正在上传,请稍后!',
     	    content: $("#subcomment").html(),
     	    cancelButton: false,  // hides the cancel button.
     	    confirmButton: false, // hides the confirm button.
     	    closeIcon: false // hides the close icon.
     	});

        if( xhr != null){
            xhr.onreadystatechange = state_change;
         	xhr.open("post",$('#submit').attr("data-target"));
         	xhr.timeout = 30000;
         	xhr.ontimeout = function(event){
         		  $.alert({
         			  title:"网络超时,请重试!",
         			  content:false,
         			  confirmButton:"确定",
         			  confirm:function(){
         				  jc.close();
         				  
         			  }
         			  
         		  })
             	}
         	xhr.upload.onprogress = updateprogress;
         	xhr.send(formData);
         	
                
         }

        return false;

        function state_change(){
            if(xhr.readyState==4 && xhr.status == 200){
                jc.setTitle("上传成功!")
                $.alert({
                	title:"上传成功!",
                	content:"你的话题已经发布成功!",
                	confirmButton:"确定",
                	confirm:function(){
                		jc.close();
                		$("form")[0].reset();
                		$(".progress-bar").attr("style","width: "+30+'%');
                		$("#priview").html("");
                	}
                		
                })
                console.log(xhr.response);
            }
        }

        function updateprogress(event){
        	
        	$(".progress").show();
            if(event.lengthComputable){
                var num = Math.floor ( (event.loaded / event.total)*100)
                
            }
            if(num % 10 == 0 && num > 30){
            	$(".progress-bar").attr("style","width: "+num+'%');
             }
            
         }

        
		
    })
})
    
function imagezip(url){

       var $img = new Image();  
       $img.src = url;
       $img.onload = function(){
           var width = $img.width,  
           height = $img.height,  
           scale = width / height;  
           
           //图片宽度<600 , gif 不进行压缩
   
           width = parseInt(600);  
           height = parseInt(width / scale);
      
           //生成canvas  
           var $canvas = $('#canvas');  
           var ctx = $canvas[0].getContext('2d');  
           $canvas.attr({width : width, height : height});  
           ctx.drawImage($img, 0, 0, width, height);  
           var base64 = $canvas[0].toDataURL('image/jpeg',0.7);  
           $("#priview").append("<img src="+ base64 + " width='50' height='50' />"); 
           URL.revokeObjectURL(url);
           
       }

}

function dataURLtoBlob(dataurl) {
    var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
        bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
    while(n--){
        u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], {type:mime});
 }
	
/**
 */
function getObjectURL(file) {
    var url = null ; 
    if (window.createObjectURL!=undefined) { // basic
        url = window.createObjectURL(file) ;
    } else if (window.URL!=undefined) { // mozilla(firefox)
        url = window.URL.createObjectURL(file) ;
    } else if (window.webkitURL!=undefined) { // webkit or chrome
        url = window.webkitURL.createObjectURL(file) ;
    }
    return url ;
}


function getfileinfo(file) {  
    if (file) {
        var fileSize = 0;
        if (file.size > 1024 * 1024){
            fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        }else{          
            fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
        }
        console.log(file.name + '/' + fileSize + '/' + file.type);
        return fileSize;
        
    }
}
