<?php
function watermark($img_file) {
	$offset = 10;
	$types = array('','gif','jpeg','png');
	list($w_i, $h_i, $type) = getimagesize($img_file);
    $ext = strtolower($types[$type]);
		 
	/*
	$watermark_file=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/logo.png'; 
	$font = $_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/KomikaTitle.ttf';
	if (!$w_i || !$h_i) { return 'Невозможно получить длину и ширину изображения'; }
	if ($ext) {	$func = 'imagecreatefrom'.$ext;	$img = $func($img_file);  } else { return 'Некорректный формат файла'; }
	$watermark = imagecreatefrompng($watermark_file); list($sx, $sy, $stype) = getimagesize($watermark_file);
	imagecopy($img, $watermark, imagesx($img) - $sx - $offset, imagesy($img) - $sy - $offset, 0, 0, $sx, $sy);
	*/

	/* watermark эксклюзив */
	$exf=$_SERVER['DOCUMENT_ROOT'].'/modules/standart/watermark/exclusive.png';			
	if($_POST["exclusive"]==1 && is_file($exf)) {
		if ($ext) {	$func = 'imagecreatefrom'.$ext;	$img = $func($img_file);  } else { return 'Некорректный формат файла'; }
		$exc = imagecreatefrompng($exf); list($sx, $sy, $stype)=getimagesize($exf);
		imagecopy($img, $exc, imagesx($img)-$sx-$offset, $offset, 0, 0, $sx, $sy);
		if ($type == 2) { imagejpeg($img, $img_file, 100); } else { $func = 'image'.$ext; $func($img, $img_file); }
	}
	
}
?>