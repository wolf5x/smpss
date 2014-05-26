<?php

/**
 * 商品管理
 * @author 齐迹  email:smpss2012@gmail.com
 *
 */
class c_goods extends base_c {

    function __construct($inPath) {
        parent::__construct();
        if (self::isLogin() === false) {
            $this->ShowMsg("请先登录！", $this->createUrl("/main/index"));
        }
        if (self::checkRights($inPath) === false) {
            //$this->ShowMsg("您无权操作！",$this->createUrl("/system/index"));
        }
        $this->params['inpath'] = $inPath;
        $this->params ['head_title'] = "商品管理-" . $this->params ['head_title'];
    }

    private function dealPicture($picture) {
        if (!$picture) {
            return "";
        }
        //	echo "FUCK........................................................".$picture;
        $image_fn = md5($picture);
        //	echo $image_fn;
        $thumb_fn = $image_fn . "thumb.jpg";
        $image_fn = $image_fn . ".jpg";
        $path = SlightPHP::$appDir . "/cache/";
        $shit = 50;
        //	echo $path;
        if (!file_exists($path . $thumb_fn)) {
            $tmp_content = base64_decode($picture);
            //	echo $tmp_content;
            //create thumb
            $imgObj = imagecreatefromstring($tmp_content);
            //		echo "FUCK:::".$imgObj;
            $imgW = imagesx($imgObj);
            $imgH = imagesy($imgObj);
            $ratio = $imgW > $imgH ? ($imgW > $shit ? $imgW / $shit : 1) : ($imgH > $shit ? $imgH / $shit : 1);
            $thumbW = intval($imgW / $ratio);
            $thumbH = intval($imgH / $ratio);
            $thumbObj = imagecreatetruecolor($thumbW, $thumbH);
            imagecopyresampled($thumbObj, $imgObj, 0, 0, 0, 0, $thumbW, $thumbH, $imgW, $imgH);
            imagejpeg($imgObj, $path . $image_fn);
            imagejpeg($thumbObj, $path . $thumb_fn);
            imagedestroy($imgObj);
            imagedestroy($thumbObj);
        }
        $url = base_Constant::ROOT_DIR . "/app/cache/";

        $ret = array();
        $ret['imageurl'] = $url . $image_fn;
        $ret['thumburl'] = $url . $thumb_fn;
        //	print_r($ret);
        return $ret;
    }

    function pageindex($inPath) {
        $url = $this->getUrlParams($inPath);
        $page = $url['page'] ? (int) $url['page'] : 1;
        $url['ac'] = $url['ac'] ? $url['ac'] : "";

        $condi = '';

        $goodsObj = new m_goods();
        switch ($url ['ac']) {
            case "del":
                $goodsObj = new m_goods((int) $url['gid']);
                if ($url ['gid']) {
                    if ($goodsObj->deleteOne($url ['gid'])) {
                        $this->ShowMsg("删除成功！", $this->createUrl("/goods/index"), 2, 1);
                    }
                    $this->ShowMsg("删除出错！原因：" . $goodsObj->getError());
                }
                break;
            default:
                if ($_POST) {
                    $key = base_Utils::getStr($_POST['key'], 'html');
                    $this->params['key'] = $key;
                    $tableName = $goodsObj->tableName();
                }
                $key['sn'] = base_Utils::getStr($_POST ['key_sn']);
                $key['chn'] = base_Utils::getStr($_POST ['key_chn']);
                $key['tha'] = base_Utils::getStr($_POST ['key_tha']);

                $this->params ['key'] = $key;
                $goodsObj->setCount(true);
                $goodsObj->setPage($page);
                $goodsObj->setLimit(base_Constant::PAGE_SIZE);
                $rs = $goodsObj->getByCondition($key);
                if ($rs->items) {
                    foreach ($rs->items as &$item) {
                        $pic_url['imageurl'] = $item['goods_pic'];
                        if ($pic_url['imageurl'] == "")
                            $pic_url['thumburl'] = "";
                        else
                            $pic_url['thumburl'] = $pic_url['imageurl'] . ".thumb.jpg";
                        //	echo"FUCK.......................".$pic_url['image_url'];
                        $item['goods_pic'] = $pic_url;
                    }
                }
                break;
        }
        $this->params ['goods'] = $rs->items;
        $this->params ['pagebar'] = $this->PageBar($rs->totalSize, base_Constant::PAGE_SIZE, $page, $inPath);

        return $this->render('goods/index.html', $this->params);
    }

    private function createThumbImage($path, $name, $type) {
        if (!file_exists($path . $name . "." . $type))
            return false;
        $file = file_get_contents($path . $name . "." . $type, "r");
        $imgObj = imagecreatefromstring($file);
        if (!$imgObj)
            return false;
        $shit = 50;
        $imgW = imagesx($imgObj);
        $imgH = imagesy($imgObj);
        $ratio = $imgW > $imgH ? ($imgW > $shit ? $imgW / $shit : 1) : ($imgH > $shit ? $imgH / $shit : 1);
        $thumbW = intval($imgW / $ratio);
        $thumbH = intval($imgH / $ratio);
        $thumbObj = imagecreatetruecolor($thumbW, $thumbH);
        imagecopyresampled($thumbObj, $imgObj, 0, 0, 0, 0, $thumbW, $thumbH, $imgW, $imgH);
        //	echo $thumbObj;
        imagejpeg($thumbObj, $path . $name . "." . $type . ".thumb.jpg");
        imagedestroy($imgObj);
        imagedestroy($thumbObj);
        return true;
    }

    function pageaddgoods($inPath) {
        $url = $this->getUrlParams($inPath);
        $goods_id = (int) $url ['gid'] > 0 ? (int) $url ['gid'] : (int) $_POST ['goods_id'];
        $mode = (int) $url['gid'] > 0 ? 0 : 1;
        $goodsObj = new m_goods($goods_id);
        if ($_POST) {

            //图片需要在这里获得保存路径，goods_pic只存路径
            $image_relative_path = "";
            //	$thumb_relative_path="";
            if (isset($_FILES["goods_pic_origin"]) and $_FILES["goods_pic_origin"]["name"] !== "") {
                //		print_r($_FILES["goods_pic_origin"]);
                if (!$_FILES["goods_pic_origin"]["error"]) {
                    $type = array(
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                        'image/pjpeg',
                        'image/gif',
                        'image/bmp',
                        'image/x-png'
                    );
                    if (!in_array($_FILES["goods_pic_origin"]["type"], $type))
                        $this->ShowMsg("图片类型不符" . $goodsObj->getError());
                    $file_name_tmp = pathinfo($_FILES["goods_pic_origin"]["name"]);
                    $file_type = $file_name_tmp['extension'];
                    $file_content = file_get_contents($_FILES["goods_pic_origin"]["tmp_name"]);
                    if ($file_content === false)
                        $this->ShowMsg("图片路径有误" . $goodsObj->getError());
                    $file_content = base64_encode($file_content);
                    $file_name = md5($file_content);
                    $path = SlightPHP::$appDir . "/cache/";
                    $file_full_path = $path . $file_name . "." . $file_type;
                    if (!file_exists($file_full_path)) {
                        if (!move_uploaded_file($_FILES["goods_pic_origin"]["tmp_name"], $file_full_path))
                            $this->ShowMsg("图片上传失败" . $goodsObj->getError());
                    }
                    $thumb_full_path = $file_full_path . ".thumb.jpg";
                    if (!file_exists($thumb_full_path)) {
                        if (!$this->createThumbImage($path, $file_name, $file_type))
                            $this->ShowMsg("缩略图创建失败" . $goodsObj->getError());;
                    }
                    $url = base_Constant::ROOT_DIR . "/app/cache/";
                    $image_relative_path = $url . $file_name . "." . $file_type;
                    ///	$thumb_relative_path=$url.$file_name."thumb.jpg";
                } else {
                    //	echo "FUCK";
                    $this->ShowMsg("图片出错" . $goodsObj->getError());
                }
            }
            $_POST['goods_pic'] = $image_relative_path;
            //	print_r($_POST);
            /*             * *************************** */
            $post = base_Utils::shtmlspecialchars($_POST);
            if ($goodsObj->create($post)) {
                //	base_Utils::ssetcookie(array('cat_id'=>$post['cat_id']));
                $this->ShowMsg("操作成功！", $this->createUrl("/goods/addgoods"), 2, 1);
            }
            $this->ShowMsg("操作失败" . $goodsObj->getError());
        }
        //	$categoryObj = new m_category ();
        //	$this->params['cat_id'] = (int)$_COOKIE['cat_id'];
        //	$this->params['catelist'] = $categoryObj->getOrderCate('&nbsp;&nbsp;&nbsp;&nbsp;');
        $this->params['goods'] = $goodsObj->selectOne("goods_id={$goods_id}");
        //	print_r($this->params['goods']);
        //	$this->params['goods']['goods_pic']= $this->dealPicture($this->params['goods']['goods_pic']);
        if ($mode === 0)
            return $this->render('goods/editgoods.html', $this->params);
        else if ($mode === 1)
            return $this->render('goods/addgoods.html', $this->params);
    }

}
