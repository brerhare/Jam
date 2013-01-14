<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

<!--	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" /> -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body text="#000000" style="background-color:#000000; /*text-align:center;*/ height:1000px;">

<div class="container" id="page">

	<div id="header">
        <div style="background-color:#000000;text-align:left;margin-left:auto;margin-right:auto;position:relative;width:960px;height:330px;">
            <map id="map0" name="map0">
                <area shape="poly" coords="97,279,125,278,153,278,209,279,209,214,217,216,222,208,223,206,219,197,210,188,209,181,211,173,213,174,217,165,217,154,214,155,209,152,209,0,0,0,0,280,84,280" href="listing.html" alt="">
            </map>
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wp65d1b97d_06.png" width="234" height="313" border="0" alt="" onload="OnLoadPngFix()" usemap="#map0" style="position:absolute;left:650px;top:28px;">
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wp984d43e2_06.png" width="334" height="57" border="0" title="" alt="Glitzaratti Couture Design" onload="OnLoadPngFix()" style="position:absolute;left:217px;top:256px;">
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wpc84d212f_06.png" width="427" height="216" border="0" alt="" onload="OnLoadPngFix()" style="position:absolute;left:164px;top:32px;">
        </div>
	</div><!-- header -->

	<?php echo $content; ?>

	<div class="clear"></div>

	<div id="footer">

	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
