<?

session_start(); 
if ($_SESSION['userid']!=0) { 
	
	$GLOBAL["sitekey"]=1;
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/DataBase.php";
	@require $_SERVER['DOCUMENT_ROOT']."/modules/standart/JsRequest.php";
	$JsHttpRequest=new JsHttpRequest("utf-8");

	// полученные данные ================================================
	
	$R = $_REQUEST;
	$uid = $_SESSION['userid'];
	$path=$_SERVER['DOCUMENT_ROOT']."/userfiles/avatar/";
	$paths=$_SERVER['DOCUMENT_ROOT']."/userfiles/avatar/";
	
	//=================================================================================================================================================================================================

	$max_image_w=10000; $max_image_h=10000; $max_image_s=10000; $msgre="loaded"; $neW=100; $neH=100; $valid_types=array("gif","jpg", "png", "jpeg"); $picname=$_FILES['userpic']['tmp_name'];
	$ext=substr($_FILES['userpic']['name'], 1+strrpos($_FILES['userpic']['name'], ".")); $ext=strtolower($ext); $picrename=$uid.".".$ext;
	
	if (filesize($picname) > ($max_image_s*1024)) { $msgre="Файл больше $max_image_s килобайт"; } elseif (!in_array($ext, $valid_types)) { $msgre="Файл не является форматом gif, jpg или png!"; } else { $size=getimagesize($picname);
	if ($size[0]<$max_image_w && $size[1]<$max_image_h) { if (move_uploaded_file($_FILES['userpic']['tmp_name'], $path.$picrename)) { list($width, $height)=@getimagesize($path.$picrename);
	
	if ($width>101 || $height>101) { $kk=$width/$height; $image_v=imagecreatetruecolor(100,100);
		if ($ext=='jpg' || $ext=='jpeg') { $image=imagecreatefromjpeg($path.$picrename); }
		if ($ext=='png') { $image=imagecreatefrompng($path.$picrename); }
		if ($ext=='gif') { $image=imagecreatefromgif($path.$picrename); }
		if ($height >= $width) {
			$neW=100; $neH=100/$kk; $image_p=imagecreatetruecolor($neW, $neH);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $neW, $neH, $width, $height); $y=($neH - 100)/2; imagecopyresized($image_v, $image_p, 0, 0, 0, $y, 100, 100, 100, 100);
		} else {
			$neH=100; $neW=100*$kk; $image_p=imagecreatetruecolor($neW, $neH);
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $neW, $neH, $width, $height); $x=($neW - 100)/2; imagecopyresized($image_v, $image_p, 0, 0, $x, 0, 100, 100, 100, 100);
		}
		if ($ext=='jpg' || $ext=='jpeg') { imagejpeg($image_v, $paths.$picrename, 95);	}
		if ($ext=='png') {imagepng($image_v, $paths.$picrename); }
		if ($ext=='gif') { imagegif($image_v, $paths.$picrename); }
	}
	DB("UPDATE `_users` SET `avatar`='userfiles/avatar/".$picrename."' WHERE (id='$uid')");	
	} else { $msgre="Ошибка сервера. Свяжитесь с администратором!"; }} else { $msgre="Картинка больше, чем $max_image_w на $max_image_h пикселей!"; }}
	
	

} else { $msgre="error user"; }
// отправляемые данные ==============================================
if ($msgre=="loaded") {
	$result["Answer"]="ok"; $result["Pic"]=$picrename."?".time();
} else {
	$result["Answer"]=$msgre;
}
$GLOBALS['_RESULT']=$result;
?>
