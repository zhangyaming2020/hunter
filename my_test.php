<?php
	//word转换成html
	ConvertToHtml('test.doc','gym.html');
	function ConvertToHtml($source,$htmlFileName){
	
	    $a='./zym.doc';
		$b='./uploads';
		//$str ="libreoffice --headless --convert-to pdf:writer_pdf_Export ./doc/2.doc --outdir ./pdf";
		//$str ='ls '.'./';
		$str = "export HOME=/tmp && libreoffice --headless -convert-to pdf /webdata/zym.doc -outdir /var/www/html/pdf";
		var_dump(shell_exec($str));
//      return json_encode(array('status'=>1,'msg' => '读取文件成功！','title'=>$title,'content'=>$content));
//		return $htmlPath;
	}
	
	/**
	     * 在html文件中找出正文
	     * @param $filename  string 路径url
	     * @return array|bool
	     */
	    function get_body_from_html($filename)
	    {
	        if (!file_exists($filename)) {
	            return false;
	        }
	
	        $file = file_get_contents($filename);
	        $result = array();
	        preg_match("/<body[^>]*?>(.*\s*?)<\/body>/is",$file,$result);
	
	        return $result;
	    }
	/**
	     * ajax上传文件
	     */
	    function docReader(Request $request)
	    {
	        if ($request->ajax()) {
	            $file = $request->file('file');
	            $title = str_replace('.docx','',$file->getClientOriginalName());
	            $title = str_replace('.doc','',$title);
	            $file_size = $file->getClientSize();
	            if($file_size>=4*1024*1024){
	                return json_encode(array('status'=>0,'msg' => '上传文件大小不能超过4M！'));
	            }
	            $savePath = public_path('').'/uploads/word';
	            $filename = md5($title).'.docx';
	            if($file->move($savePath,$filename)){
	                $source_path = public_path().'/uploads/word/'.$filename;
	
	                $html_file_name = md5($title).'.html';
	
	                try{
	                    $html_path = ConvertToHtml($source_path,$html_file_name);
	
	                    $html = get_body_from_html($html_path);
	
	                    $content = $html[1];
	
	                    $content = mb_convert_encoding($content,'utf-8','gbk');
	
	                    $content = cutstr_html($content);
	
	                    return json_encode(array('status'=>1,'msg' => '读取文件成功！','title'=>$title,'content'=>$content));
	
	                }catch (\Exception $exception){
	                    return json_encode(array('status'=>0,'msg' => $exception->getMessage()));
	                }
	            }
	            return json_encode(array('status'=>0,'msg' => '文件上传失败！'));
	        }else{
	            return json_encode(array('status'=>0,'msg' => '读取文件失败！'));
	        }
	    }
?>