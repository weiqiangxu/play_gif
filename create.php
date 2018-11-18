<?php

require('./vendor/autoload.php');

// Include the GIFGenerator class
use ErikvdVen\Gif\GIFGenerator;
use ErikvdVen\Gif\GIFEncoder;

// 

// 初始化一个新的GIFGenerator对象
$gif = new GIFGenerator();

$txt_des = array('我就是用华为','用诺基亚','也不会买你们苹果公司的产品','iphone7真好用');

$static = str_replace('\\', '/', __DIR__).'/static/';
@mkdir('./cache');
$save_gif = './cache/'.date('YmdHis',time()).'.gif';
$thumb_gif = './cache/'.date('YmdHis',time()).'_thumb.gif';

// 拼接gif所有片段图片
$frames = array();
for ($i=1; $i < 53; $i++) {
	if($i<11){
		$txt = $txt_des[0];
		$pos = array(85,138);
	}else if($i<25){
		$txt = $txt_des[1];
		$pos = array(98,138);
	}else if($i<37){
		$txt = $txt_des[2];
		$pos = array(45,138);
	}else if($i<53){
		$txt = $txt_des[3];
		$pos = array(75,138);
	}
	$tmp =  array(
		'image' => $static.'wangjingze/'.$i.'.png',
		'text' => array(
			array(
				'text' => $txt,
				'fonts' => $static.'fonts/huawen.ttf',
				'fonts-size' => 13,
				'angle' => 0,
				'fonts-color' => '#FFFFFF',
				'x-position' => $pos[0],
				'y-position' => $pos[1]
			)
		),
		'delay' => 18
	);
	$frames[] = $tmp;
}


// 创建具有所有图像帧的多维数组
$imageFrames = array(
	'repeat' => 5,
	'frames' => $frames
);

// gif保存
$content = $gif->generate($imageFrames);

if(!empty($_GET['show'])){
	// Caching disable headers
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	// Output as a GIF image
	header ('Content-type:image/gif');
	echo $content;
}else{
	file_put_contents($save_gif, $content);
}

// 压缩gif
$image = new Imagick($save_gif);
$image = $image->coalesceImages();
foreach ($image as $key => $value) {
	$value->thumbnailImage(300,168);
}
$image = $image->optimizeImageLayers();
$image->writeImages($thumb_gif,true);

