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

<!-- body style -->
<style type="text/css">
  body{margin:0;padding:0;}
  .Body-P
  {
    margin:0.0px 0.0px 12.0px 0.0px; text-align:center; font-weight:400;
  }
  .Body-C
  {
    font-family:"Verdana", sans-serif; color:#ffffff; font-size:13.0px; line-height:1.23em;
  }
</style>

<!-- menu style -->
<style type="text/css">
  .menu{margin:0;padding:0;}
  .Button1,.Button1:link,.Button1:visited{background-image:url(<?php Yii::app()->request->baseUrl ?>/img/menuline.png));background-position:0px 0px;text-decoration:none;display:block;position:absolute;}
  .Button1:focus{outline-style:none;}
  .Button1:active{background-position:0px -29px;}
  .Button1 span,.Button1:link span,.Button1:visited span{color:#6659a2;font-family:Arial,sans-serif;font-weight:normal;text-decoration:none;text-align:left;text-transform:none;font-style:normal;left:19px;top:5px;width:83px;height:19px;font-size:15px;display:block;position:absolute;cursor:pointer;}
</style>

</head>


<body text="#000000" style="background:transparent url(<?php Yii::app()->request->baseUrl ?>/img/blackboard_bg.jpg) repeat scroll top center; height:340px;">
  <div style="background-color:transparent;margin-left:auto;margin-right:auto;position:relative;width:960px;height:340px;">
    <map id="map0" name="map0">
      <area shape="rect" coords="46,120,186,180" href="index.php" alt="">
      <area shape="poly" coords="69,308,73,307,79,301,84,295,94,279,97,270,102,262,109,246,114,237,121,230,127,227,135,224,141,220,150,217,159,213,165,207,174,202,185,194,188,191,194,183,207,162,209,152,209,145,208,139,205,132,202,126,194,113,194,109,196,101,197,92,195,86,192,81,188,73,191,66,195,58,191,49,192,39,190,29,184,23,177,21,169,23,166,25,159,28,151,33,143,39,135,44,129,53,122,57,115,60,106,65,100,72,96,75,90,83,83,85,71,91,62,95,54,100,37,121,32,129,28,138,22,156,19,171,19,181,21,200,23,206,24,214,27,221,32,229,34,236,39,245,40,254,39,271,40,283,42,293,46,301,53,307,60,310" href="index.php" alt="">
    </map>
    <img src="<?php Yii::app()->request->baseUrl ?> /img/shell.png" border="0" width="229" height="327" title="" alt="Insight&#10;" usemap="#map0" style="position:absolute;left:128px;top:14px;">
    <img src="<?php Yii::app()->request->baseUrl ?> /img/heading_title1.png" border="0" width="529" height="53" title="" alt=" Training, Coaching &amp; Media&#10;" style="position:absolute;left:342px;top:65px;">
    <map id="map1" name="map1">
      <area shape="poly" coords="1,76,2,73,3,66,3,60,0,50,0,78" href="index.php" alt="">
    </map>
    <img src="<?php Yii::app()->request->baseUrl ?> /img/heading_title2.png" border="0" width="554" height="106" alt="" usemap="#map1" style="position:absolute;left:334px;top:99px;">
  </div>


  <div style="z-index:2000; background-color:transparent;margin-left:auto;margin-right:auto;position:relative;width:960px;kheight:556px;">
      <div style="/*background-color:#ffffff;*/position:absolute;left:127px;top:21px;width:131px;height:129px;background-image:url('wpimages/wp531e26d2_06.png');">

	      <?php $toppos=-30;
	      $menuitems = ContentBlock::model()->findAll(array('order'=>'sequence'));
	      foreach ($menuitems as $menuitem):?>
            <a class="menuitemx" href="<?php Yii::app()->request->baseUrl?>?r=site/<?php echo $menuitem->url;?>">
              <img style="position:absolute;top:<?php echo $toppos; $toppos+=40;?>px;left:-20px" src="<?php Yii::app()->request->baseUrl ?>/img/menuline.png">
	          <?php echo $menuitem->title;?>
            </a>
            <br/><br/>
	      <?php endforeach;?>

      </div>
  </div>

  <div style="background-color:transparent;margin-left:auto;margin-right:auto;position:relative;width:660px;height:100%;">

    <div style="position:absolute;top:-100px">
      <?php echo $content; ?>

      <div class="clear"></div>

      <div id="footer" text="#000000" style="background:transparent url('wpimages/wp8f23f5b6_06.jpg') repeat scroll top center; height:133px;">
        <div style="background-color:transparent;margin-left:auto;margin-right:auto;position:relative;width:960px;height:133px;">
          <img src="<?php Yii::app()->request->baseUrl ?> /img/footer_shell.png" border="0" width="86" height="108" title="" alt="Insight&#10;" style="position:absolute;left:404px;top:0px;">
          <div style="position:absolute;left:351px;top:42px;width:192px;height:31px;overflow:hidden;">
            <p class="Wp-Body-P"><span class="Body-C">Copyright</span></p>
          </div>
          <div style="position:absolute;left:479px;top:43px;width:314px;height:31px;overflow:hidden;">
            <p class="Wp-Body-P"><span class="Body-C">Training, Coaching &amp; Media 2013</span></p>
          </div>
          <div style="position:absolute;left:347px;top:75px;width:414px;height:51px;overflow:hidden;">
            <p class="Body-P"><span class="Body-C">Contact Details info@insightKLG</span></p>
            <p class="Body-P"><span class="Body-C">Hosted and Designed by <a href="http://www.wireflydesign.com" target="_blank" style="text-decoration:underline;">Wirefly Design</a></span></p>
          </div>
        </div>
      </div>
    </div>

  </div>

</body>
</html>
