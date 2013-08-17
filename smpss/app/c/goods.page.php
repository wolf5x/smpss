<?php
/**
 * 商品管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_goods extends base_c {
	function __construct($inPath) {
		parent::__construct ();
		if (self::isLogin () === false) {
			$this->ShowMsg ( "请先登录！", $this->createUrl ( "/main/index" ) );
		}
		if (self::checkRights ( $inPath ) === false) {
			//$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
		}
		$this->params['inpath'] = $inPath;
		$this->params ['head_title'] = "商品管理-" . $this->params ['head_title'];
	}
	
	private function dealPicture($picture) {
		if(!$picture) {
			return "";
		}
		return "";
	//	echo $picture;
		$file_content = base64_decode($picture);
	//	echo $file_content;
		$imgObj=imagecreatefromstring($file_content);
	//	print_r($imgObj);
/*	//	$abcd=base64_decode($picture);
		echo $picture;
		return "";
		$imgObj=imagecreatefromstring($abcd);
		$path=SlightPHP::$appDir . "/cache/";
		$image_fn = $file_name."."."jpg";
		$image_full_path=$path.$image_fn;
		$thumb_fn = $image_fn.".thumb.jpg";
		$thumb_full_path=$path.$thumb_fn;
		$shit = 50;
		imagejpeg($imgObj,$path.$image_fn);
		imagedestroy($imgObj);
		return "";
	//	echo $image_full_path;
		if(!file_exists($image_full_path)) {
			$file_pointer=fopen($image_full_path,"wb");
			fwrite($file_pointer,$file_content);
			fclose($file_pointer);
			$file_info=getimagesize($image_full_path);
	
			switch($file_extension)
			{
				case "gif":
					
					imagegif($imgObj,$image_full_path);
					imagedestroy($imgObj);
					break;
				case "jpg":
					$imgObj=imagecreatefromjpeg($image_full_path);
					imagejpeg($imgObj,$image_full_path);
					imagedestroy($imgObj);
					break;
				case "png":
					$imgObj=imagecreatefrompng($image_full_path);
					imagepng($imgObj,$image_full_path);
					imagedestroy($imgObj);
					break;
				default:
					return false;
			}
		//	imagedestroy($imgObj);
		}
		return "";
		if(!file_exists($path.$thumb_fn)) {
		//	echo $tmp_content;
			//create thumb
			switch($file_extension)
			{
				case "jpg":
					$imgObj=imagecreatefromjpeg($image_full_path);
					break;
				case "gif":
					$imgObj=imagecreatefromgif($image_full_path);
					break;
				case "png":
					$imgObj=imagecreatefrompng($image_full_path);
					break;
				default:
					return false;
			}
	//		$imgObj = imagecreatefromstring($tmp_content);
	//		echo "FUCK:::".$imgObj;
			$imgW = imagesx($imgObj);
			$imgH = imagesy($imgObj);
			$ratio = $imgW>$imgH ? ($imgW>$shit?$imgW/$shit:1) : ($imgH>$shit?$imgH/$shit:1);
			$thumbW = intval($imgW/$ratio);
			$thumbH = intval($imgH/$ratio);
			$thumbObj = imagecreatetruecolor($thumbW, $thumbH);
			imagecopyresampled($thumbObj, $imgObj, 0, 0, 0, 0, $thumbW, $thumbH, $imgW, $imgH);
	//		imagejpeg($imgObj, $path.$image_fn);
			imagejpeg($thumbObj, $path.$thumb_fn);
	//		imagedestroy($imgObj);
			imagedestroy($thumbObj);
		}
		$url = base_Constant::ROOT_DIR . "/app/cache/" ;*/

		$ret = array();
		$ret['imageurl'] = $url.$image_fn;
		$ret['thumburl'] = $url.$thumb_fn;
	//	print_r($ret);
		return $ret;
	}
	
	function pageindex($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$page = $url['page']?(int)$url['page']:1;
		$url['ac'] = $url['ac']?$url['ac']:"";
	
		$condi = '';
		
		$goodsObj = new m_goods();
		switch ($url ['ac']) {
			case "del":
				$goodsObj=new m_goods((int)$url['gid']);
				if ($url ['gid']) {
					if ($goodsObj->deleteOne ( $url ['gid'] )) {
						$dir_name=(string)$url['gid'];
						$path=SlightPHP::$appDir . "/cache/";
						if(is_dir($path.$dir_name))
						{
							$dir_handle=opendir($path.$dir_name);
							$this->del_files($dir_handle,$path.$dir_name);
							closedir($dir_handle);
							rmdir($path.$dir_name);
						}
						$this->ShowMsg ( "删除成功！", $this->createUrl ( "/goods/index" ), 2, 1 );
					}
					$this->ShowMsg ( "删除出错！原因：" . $goodsObj->getError () );
				}
				break;
			default:
				if($_POST){
					$key = base_Utils::getStr($_POST['key'],'html');
					$this->params['key'] = $key;
					$tableName = $goodsObj->tableName();
				}
				$key['sn'] = base_Utils::getStr ( $_POST ['key_sn'] );
				$key['chn'] = base_Utils::getStr ( $_POST ['key_chn'] );
				$key['tha'] = base_Utils::getStr ( $_POST ['key_tha'] );
			
				$this->params ['key'] = $key;
				$goodsObj->setCount ( true );
				$goodsObj->setPage ( $page );
				$goodsObj->setLimit ( base_Constant::PAGE_SIZE );
				$rs = $goodsObj->getByCondition($key,$page);
				if($rs->items)
				{
					foreach ($rs->items as &$item) {
					//	echo $item['goods_pic'];
						$pic_url['imageurl']=$item['goods_pic'];
					//	echo $item['goods_pic'];
						if($pic_url['imageurl']=="")
							$pic_url['thumburl']="";
						else 
							$pic_url['thumburl']=$pic_url['imageurl'].".thumb.jpg";
						$item['goods_pic']=$pic_url;
					
					}
				}
				break;
		}
		$this->params ['goods'] = $rs->items;
		$this->params ['pagebar'] = $this->PageBar ( $rs->totalSize, base_Constant::PAGE_SIZE, $page, $inPath );
		
		return $this->render ( 'goods/index.html', $this->params );
	}
	
	private function createThumbImage($content,$image_path,$thumb_path) {
		if(!file_exists($image_path))
			return false;
		//$file=file_get_contents($path.$name.".".$type,"r");
		$imgObj = imagecreatefromstring($content);
		if(!$imgObj)
			return false;
		$shit = 50;
		$imgW = imagesx($imgObj);
		$imgH = imagesy($imgObj);
		$ratio = $imgW>$imgH ? ($imgW>$shit?$imgW/$shit:1) : ($imgH>$shit?$imgH/$shit:1);
		$thumbW = intval($imgW/$ratio);
		$thumbH = intval($imgH/$ratio);
		$thumbObj = imagecreatetruecolor($thumbW, $thumbH);
		imagecopyresampled($thumbObj, $imgObj, 0, 0, 0, 0, $thumbW, $thumbH, $imgW, $imgH);
	//	echo $thumbObj;
		imagejpeg($thumbObj, $thumb_path);
		imagedestroy($imgObj);
		imagedestroy($thumbObj);
		return true;
	}
	function uploadpicture() {
		
	}
	function pageaddgoods($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$goods_id = ( int ) $url ['gid'] > 0 ? ( int ) $url ['gid'] : ( int ) $_POST ['goods_id'];
		$goodsObj = new m_goods($goods_id);
		if($_POST){
			$url_img="";
			$dir_name="tmp";
			$path=SlightPHP::$appDir . "/cache/";
			if(!is_dir($path.$dir_name))
				mkdir($path.$dir_name,0755);
			else
			{
				$dir_handle=opendir($path.$dir_name);
				$this->del_files($dir_handle,$path.$dir_name);
				closedir($dir_handle);
			}
						
			if(isset($_FILES["goods_pic_origin"]) and $_FILES["goods_pic_origin"]["name"]!=="") {
				if(!$_FILES["goods_pic_origin"]["error"]) {
					$file_info=getimagesize($_FILES["goods_pic_origin"]["tmp_name"]);
					$file_type=$file_info[2];
					if($file_type!==1 && $file_type!==2 && $file_type!==3)
						$this->ShowMsg ("图片格式只能为jpg、gif、png". $goodsObj->getError() );
					$file_name_tmp=pathinfo($_FILES["goods_pic_origin"]["name"]);
					$file_content=file_get_contents($_FILES["goods_pic_origin"]["tmp_name"]);
					if($file_content===false)
						$this->ShowMsg ( "图片路径有误" . $goodsObj->getError () );
					$file_name=md5(base64_encode($file_content));
					$file_path=$path.$dir_name."/".$file_name;
					switch($file_type)
					{
						case 1:
							$file_extension=".gif";
							break;
						case 2:
							$file_extension=".jpg";
							break;
						case 3:
							$file_extension=".png";
							break;
						default:
							$this->ShowMsg ("图片格式只能为jpg、gif、png". $goodsObj->getError() );
					}
					$file_full_name=$file_path.$file_extension;
					$thumb_full_name=$file_full_name.".thumb.jpg";

					if(!file_exists($file_full_name))
					{
						if(!move_uploaded_file($_FILES["goods_pic_origin"]["tmp_name"],$file_full_name))
							$this->ShowMsg ( "图片创建失败" . $goodsObj->getError () );
					}
					if(!file_exists($thumb_full_name))
					{	if(!$this->createThumbImage($file_content,$file_full_name,$thumb_full_name))
							$this->ShowMsg ( "缩略图创建失败" . $goodsObj->getError () );
					}
					$url = base_Constant::ROOT_DIR . "/app/cache/";
					$url_img=$url.$dir_name."/".$file_name.$file_extension;
			} else
				{
					$this->ShowMsg ( "图片出错" . $goodsObj->getError () );
				}
			}
			
	    	$_POST['goods_pic']=$url_img;
			$post = base_Utils::shtmlspecialchars ( $_POST );
			if ($goodsObj->create ( $post )) {
				$dir_name=(string)$goodsObj->getPkid();
			//	echo $dir_name;
				rename($path."tmp",$path.$dir_name);
				if($url_img!=="")
					$url_img=$url.$dir_name."/".$file_name.$file_extension;
				$_POST['goods_pic']=$url_img;
				$_POST['goods_id']=$goodsObj->getPkid();
			//	print_r($_POST);
				$post = base_Utils::shtmlspecialchars ( $_POST );
				$goodsObj->create ( $post );
			//		echo $dir_name;
				$this->ShowMsg ( "添加成功！", $this->createUrl ( "/goods/addgoods" ), 2, 1 );
			}
			$this->ShowMsg ( "操作失败" . $goodsObj->getError () );
		}

		$this->params['goods'] = $goodsObj->selectOne("goods_id={$goods_id}");
		return $this->render ( 'goods/addgoods.html', $this->params );
	}
	
	public function del_files($dir_handle,$root_dir) {
		while($file=readdir($dir_handle))
		{
			if($file!="." && $file!=".." && $file!=$root_dir) {
				$fullpath=$root_dir."/".$file;
			//	echo $fullpath;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				}
				else {
					$this->del_files($dir_handle,$fullpath);
					rmdir($fullpath);
				} 
			}
		}	
		
	}
	
	function pageeditgoods($inPath) {
		$url = $this->getUrlParams ( $inPath );
		$goods_id = ( int ) $url ['gid'] > 0 ? ( int ) $url ['gid'] : ( int ) $_POST ['goods_id'];
		$goodsObj = new m_goods($goods_id);
		if($_POST){
			$url_img="";
			$dir_name=$_POST['goods_id'];
			$path=SlightPHP::$appDir . "/cache/";
			if(!is_dir($path.$dir_name))
				mkdir($path.$dir_name,0755);
			
			if(isset($_FILES["goods_pic_origin"]) and $_FILES["goods_pic_origin"]["name"]!=="") {
				if(!$_FILES["goods_pic_origin"]["error"]) {
					$file_info=getimagesize($_FILES["goods_pic_origin"]["tmp_name"]);
					$file_type=$file_info[2];
					if($file_type!==1 && $file_type!==2 && $file_type!==3)
						$this->ShowMsg ("图片格式只能为jpg、gif、png". $goodsObj->getError() );
					$file_name_tmp=pathinfo($_FILES["goods_pic_origin"]["name"]);
					$file_content=file_get_contents($_FILES["goods_pic_origin"]["tmp_name"]);
					if($file_content===false)
						$this->ShowMsg ( "图片路径有误" . $goodsObj->getError () );
					$file_name=md5(base64_encode($file_content));
					$file_path=$path.$dir_name."/".$file_name;
					
					switch($file_type)
					{
						case 1:
							$file_extension=".gif";
							break;
						case 2:
							$file_extension=".jpg";
							break;
						case 3:
							$file_extension=".png";
							break;
						default:
							$this->ShowMsg ("图片格式只能为jpg、gif、png". $goodsObj->getError() );
					}
					$file_full_name=$file_path.$file_extension;
				//	echo "shit:".$file_full_name;
					$thumb_full_name=$file_full_name.".thumb.jpg";
					if(!file_exists($file_full_name))
					{
						$dir_handle=opendir($path.$dir_name);
						$this->del_files($dir_handle,$path.$dir_name);
						closedir($dir_handle);
						if(!move_uploaded_file($_FILES["goods_pic_origin"]["tmp_name"],$file_full_name))
							$this->ShowMsg ( "图片创建失败" . $goodsObj->getError () );
					}
					if(!file_exists($thumb_full_name))
					{	if(!$this->createThumbImage($file_content,$file_full_name,$thumb_full_name))
							$this->ShowMsg ( "缩略图创建失败" . $goodsObj->getError () );
					}
					$url = base_Constant::ROOT_DIR . "/app/cache/";
					$url_img=$url.$dir_name."/".$file_name.$file_extension;
				//	echo $url_img;
			} else
				{
				//	echo "FUCK";
					$this->ShowMsg ( "图片出错" . $goodsObj->getError () );
				}
			}
			else
			{
				$dir_handle=opendir($path.$dir_name);
				$this->del_files($dir_handle,$path.$dir_name);
				closedir($dir_handle);
			}
			$_POST['goods_pic']=$url_img;
			$post = base_Utils::shtmlspecialchars ( $_POST );
			
			if ($goodsObj->create ( $post )) {
						$this->ShowMsg ( "修改成功！", $this->createUrl ( "/goods/index" ), 2, 1 );
			}
			$this->ShowMsg ( "操作失败" . $goodsObj->getError () );
		}
		$this->params['goods'] = $goodsObj->selectOne("goods_id={$goods_id}");
		return $this->render ( 'goods/editgoods.html', $this->params );
	}

	
}
