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

<body text="#000000" style="background-color:#000000; /*text-align:center;*/ /*height:1000px;*/">

<div class="container" id="page">


	<div id="header">
        <div style="background-color:#000000;text-align:left;margin-left:auto;margin-right:auto;position:relative;width:960px;height:330px;">
            <map id="map0" name="map0">
                <area shape="poly" coords="97,279,125,278,153,278,209,279,209,214,217,216,222,208,223,206,219,197,210,188,209,181,211,173,213,174,217,165,217,154,214,155,209,152,209,0,0,0,0,280,84,280" href="<?php echo $this->createUrl('product/index?category_id=all');?>" alt="">
            </map>
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wp65d1b97d_06.png" width="234" height="313" border="0" alt="" onload="OnLoadPngFix()" usemap="#map0" style="position:absolute;left:650px;top:28px;">
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wp984d43e2_06.png" width="334" height="57" border="0" title="" alt="Glitzaratti Couture Design" onload="OnLoadPngFix()" style="position:absolute;left:217px;top:256px;">
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wpc84d212f_06.png" width="427" height="216" border="0" alt="" onload="OnLoadPngFix()" style="position:absolute;left:164px;top:32px;">
            <a href="<?php echo $this->createUrl('product/index?category_id=all');?>" id="nav_418_B3" class="Button1" style="z-index:1000;display:block;position:absolute;left:705px;top:280px;width:94px;height:37px;"><span style="color:#d2d29f">Gallery</span></a>
        </div>
	</div><!-- header -->

	<?php echo $content; ?>

	<div class="clear"></div>

    <style type="text/css">
        footer {margin: 0px; padding: 0px;}
        .Normal-P
        {
            margin:0.0px 0.0px 0.0px 0.0px; text-align:center; font-weight:400;
        }
        .Normal-C
        {
            font-family:"Arial", sans-serif; color:#ffffff; font-size:11.0px; line-height:1.27em;
        }
        .Button1,.Button1:link,.Button1:visited{background-position:0px 0px;text-decoration:none;display:block;position:absolute;background-image:url(<?php Yii::app()->request->baseUrl ?>/img/wp3b737c37_06.png);}
        .Button1:focus{outline-style:none;}
        .Button1:hover{background-position:0px -74px;}
        .Button1:active{background-position:0px -37px;}
        .Button1 span,.Button1:link span,.Button1:visited span{color:#ffffff;font-family:Arial,sans-serif;font-weight:normal;text-decoration:none;text-align:center;text-transform:none;font-style:normal;left:1px;top:10px;width:92px;height:17px;font-size:13px;display:block;position:absolute;cursor:pointer;}
    </style>

	<div id="footer" text="#000000" style="background-color:#000000; text-align:center; height:253px;">
        <div style="background-color:#000000;text-align:left;margin-left:auto;margin-right:auto;position:relative;width:960px;height:253px;">
            <map id="map0" name="map0">
                <area shape="poly" coords="31,99,16,99,12,94,11,90,11,89,15,90,18,91,22,91,26,92,27,94,27,95,29,96" href="index.html" alt="">
                <area shape="poly" coords="31,97,28,94,28,91,31,91,32,90,34,90,36,89,38,88,40,87,40,90,37,92,34,95" href="index.html" alt="">
                <area shape="poly" coords="32,99,37,98,43,95,42,91,45,84,55,89,64,93,68,94,82,94,89,90,98,88,100,81,93,87,91,88,93,78,85,74,81,79,88,76,91,83,87,89,78,84,71,80,62,75,58,69,67,65,61,63,53,69,59,62,69,65,71,67,69,61,75,55,75,52,82,54,83,53,76,46,74,41,79,36,72,40,69,38,78,33,80,28,75,25,65,26,55,29,46,34,40,37,37,35,23,35,15,37,8,42,0,53,0,74,5,83,8,89,11,98,17,101,28,101" href="index.html" alt="">
                <area shape="poly" coords="29,89,19,89,13,87,14,85,22,81,32,81,35,83,37,84,38,84,35,87,33,88,31,88" href="index.html" alt="">
                <area shape="poly" coords="42,82,39,82,38,81,37,81,36,80,35,80,33,79,31,79,27,78,24,79,21,79,20,80,21,79,24,78,28,77,31,77,32,78,34,78,35,79,38,79,38,80,40,80,41,81" href="index.html" alt="">
                <area shape="poly" coords="72,93,68,90,63,89,59,89,56,86,53,85,50,83,47,81,50,78,52,79,56,79,60,77,64,79,67,81,71,83,74,85,82,89,86,90,82,90,81,91,77,91" href="index.html" alt="">
                <area shape="poly" coords="45,79,44,79,40,78,35,76,24,76,22,77,21,77,24,75,29,74,33,74,35,75,40,75,42,76,43,77,44,77" href="index.html" alt="">
                <area shape="poly" coords="57,77,50,77,50,76,52,74,53,74,54,73,54,74,56,74,57,75,59,75,57,76" href="index.html" alt="">
                <area shape="poly" coords="45,75,43,74,42,74,44,73,45,73,46,72,46,74" href="index.html" alt="">
                <area shape="poly" coords="59,73,58,73,57,72,56,72,55,71,57,71" href="index.html" alt="">
                <area shape="poly" coords="29,73,24,73,26,71,29,71,30,70,31,72,30,72" href="index.html" alt="">
                <area shape="poly" coords="38,73,36,72,35,71,34,69,41,69,42,70,45,70,42,71,42,72,39,72" href="index.html" alt="">
                <area shape="poly" coords="10,84,4,77,1,69,1,60,4,53,10,45,16,40,23,37,31,36,40,38,34,45,32,48,27,41,22,47,26,48,24,44,27,43,29,47,27,55,23,49,17,48,12,51,19,55,21,51,18,50,19,53,16,50,18,49,24,54,26,57,22,55,20,60,22,62,24,60,28,69,22,71,15,76" href="index.html" alt="">
                <area shape="poly" coords="47,68,43,68,42,67,34,67,34,63,35,58,38,55,40,51,42,48,44,45,46,44,48,42,50,40,51,43,50,47,49,51,47,52,44,52,41,53,44,55,48,53,52,57,50,60,48,64" href="index.html" alt="">
                <area shape="poly" coords="57,37,54,37,56,35,59,33,61,32,62,31,62,34,61,35,61,36,58,36" href="index.html" alt="">
                <area shape="poly" coords="62,36,63,32,63,30,66,30,67,29,69,28,75,28,77,29,75,33,72,33,68,35,66,35" href="index.html" alt="">
            </map>
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wp10cd6580_06.png" width="100" height="101" border="0" title="" alt="Home" onload="OnLoadPngFix()" usemap="#map0" style="position:absolute;left:860px;top:0px;">
            <div id="txt_1" style="position:absolute;left:0px;top:131px;width:960px;height:82px;overflow:hidden;">
                <p class="Normal-P"><span class="Normal-C">Copyright Glitzarratti Couture Designs 2013</span></p>
                <p class="Normal-P"><span class="Normal-C"><a href="http://www.wireflydesign.com"  target="_blank" style="color:#ffffff;text-decoration:none;">Designed and Hosted by Wirefly Design</a></span></p>
            </div>

	        <!--
            <div style="position:absolute;left:271px;top:42px;width:417px;height:47px; background-image:url('<?php /*Yii::app()->request->baseUrl;*/?> /img/wp83c3de7e_06.png');">
                <a href="<?php /*echo $this->createUrl('site/index');*/?>" id="nav_418_B1" class="Button1" style="z-index:1000;display:block;position:absolute;left:5px;top:5px;width:94px;height:37px;"><span>Home</span></a>
                <a href="custom.html" id="nav_418_B2" class="Button1" style="display:block;position:absolute;left:110px;top:5px;width:94px;height:37px;"><span>Your Design</span></a>
                <a href="<?php /*echo $this->createUrl('product/index?category_id=all');*/?>" id="nav_418_B3" class="Button1" style="z-index:1000;display:block;position:absolute;left:214px;top:5px;width:94px;height:37px;"><span>Gallery</span></a>
                <a href="basket.html" id="nav_418_B4" class="Button1" style="display:block;position:absolute;left:319px;top:5px;width:94px;height:37px;"><span>My Basket</span></a>
            </div>
            -->

			<a href="<?php echo $this->createUrl('site/terms');?>" class="Button1" style="z-index:1000;display:block;position:absolute;left:320px;top:170px;width:94px;height:37px;"><span>Terms & Conditions</span></a>
			<a href="<?php echo $this->createUrl('site/privacy');?>" class="Button1" style="z-index:1000;display:block;position:absolute;left:520px;top:170px;width:94px;height:37px;"><span>Privacy Policy</span></a>


            <map id="map1" name="map1">
                <area shape="poly" coords="880,80,877,79,874,77,872,75,870,71,871,69,877,72,885,72,886,75,888,77,889,79,884,79" href="index.html" alt="">
                <area shape="poly" coords="890,76,887,73,888,71,892,71,896,69,899,67,899,72,896,72,893,75,892,75" href="index.html" alt="">
                <area shape="poly" coords="884,70,880,70,876,68,873,68,872,67,874,65,878,63,883,62,893,62,894,63,896,64,897,65,896,66,893,68,890,68" href="index.html" alt="">
                <area shape="poly" coords="889,80,899,77,902,71,903,63,911,68,915,70,922,73,928,75,939,75,950,70,959,65,959,61,952,67,950,68,952,60,945,53,940,58,945,56,950,64,945,68,934,63,926,58,920,50,917,49,926,46,921,44,913,49,912,50,917,42,919,43,927,42,930,50,928,41,933,36,935,32,941,35,935,27,932,22,937,16,931,20,929,19,937,13,939,10,931,5,921,6,910,11,902,16,892,15,879,15,868,22,862,30,859,38,859,54,863,62,868,71,872,80,882,83" href="index.html" alt="">
                <area shape="poly" coords="898,63,896,61,893,59,883,59,884,58,894,58,896,60,898,61,899,61,900,62" href="index.html" alt="">
                <area shape="poly" coords="937,72,925,72,922,70,918,68,915,66,911,64,907,62,906,61,909,58,911,59,915,60,919,58,920,57,923,60,926,61,929,63,939,68,941,69,944,70,940,71" href="index.html" alt="">
                <area shape="poly" coords="902,60,900,59,899,58,894,56,885,56,888,55,896,55,898,56,900,56,901,57,902,57,904,59,903,59" href="index.html" alt="">
                <area shape="poly" coords="914,58,912,58,910,57,909,57,911,55,912,53,913,53,915,55,917,56,916,56" href="index.html" alt="">
                <area shape="poly" coords="905,56,903,55,902,55,901,54,903,54,904,53,905,53" href="index.html" alt="">
                <area shape="poly" coords="917,54,914,51,916,51,918,53" href="index.html" alt="">
                <area shape="poly" coords="887,53,883,53,884,52,885,52,887,51,889,51,889,52,888,52" href="index.html" alt="">
                <area shape="poly" coords="898,53,895,53,894,50,894,49,895,50,896,50,897,49,899,50,902,50,903,51,902,51,901,52,899,52" href="index.html" alt="">
                <area shape="poly" coords="868,65,861,54,860,44,864,31,870,23,875,21,879,17,884,18,888,17,897,18,899,19,893,26,890,28,883,21,880,25,883,29,884,27,883,25,885,24,887,33,886,36,881,28,873,33,877,36,879,31,876,33,874,32,879,30,884,36,881,35,879,40,881,43,884,41,887,48,879,52,872,59" href="index.html" alt="">
                <area shape="poly" coords="905,49,904,48,900,48,899,47,893,47,893,45,894,39,896,35,898,33,900,30,903,27,906,24,909,21,910,23,908,27,908,31,904,33,901,34,907,34,909,33,910,37,909,39,907,45" href="index.html" alt="">
                <area shape="poly" coords="916,18,914,17,913,17,914,16,915,16,916,15,918,13,919,13,920,12,921,13,921,14,920,16,918,16" href="index.html" alt="">
                <area shape="poly" coords="924,16,922,15,922,11,923,11,929,9,931,8,934,8,937,11,934,12,931,14,928,15,924,15" href="index.html" alt="">
            </map>
            <img src="<?php Yii::app()->request->baseUrl ?> /img/wp98ec7a5b_06.png" width="959" height="222" border="0" alt="" onload="OnLoadPngFix()" usemap="#map1" style="position:absolute;left:1px;top:20px;">
        </div>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
