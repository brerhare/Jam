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



</head>

<body text="#000000" style="background:transparent url(<?php Yii::app()->request->baseUrl ?>/img/blackboard_bg.jpg) repeat scroll top center">

<div class="container">
    <div class="row" style="height:30px"></div>
    <div class="row">
        <div class="span3">
            <div style="text-align:right;">
	            <img src="<?php Yii::app()->request->baseUrl ?> /img/shell.png" border="0" width="229" height="327" >
	        </div>
            <!-- lhs menu content-->
	        <br/>
            <div style="text-align:right;">
	        <?php $toppos=-30;
            $menuitems = ContentBlock::model()->findAll(array('order'=>'sequence'));
            foreach ($menuitems as $menuitem):?>
	            <?php if (strtoupper($menuitem->active == 'Y')):?>
              <a class="menuitemx" style="color:#b2b2da" href="<?php Yii::app()->request->baseUrl?>/index.php/contentBlock/page?url=<?php echo $menuitem->url;?>">
<!--          <img style="position:absolute;top:<?php echo $toppos; $toppos+=40;?>px;left:-20px" src="<?php Yii::app()->request->baseUrl ?>/img/menuline.png"> -->
              <?php echo $menuitem->title;?>
              </a>
              <br/><br/>
			            <?php endif;?>
            <?php endforeach;?>
	        </div>
        </div>
        <div class="span7">
	        <div style="height:50px"></div>
	        <div style="text-align:center">
              <img src="<?php Yii::app()->request->baseUrl ?> /img/heading_title1.png" border="0" width="529" height="53" >
	          <br/>
              <img src="<?php Yii::app()->request->baseUrl ?> /img/heading_title2.png" border="0" width="554" height="106" >
		    </div>

            <!--Body content-->
	        <div style="height:50px"></div>
	        <div id="content">
	            <?php echo $content; ?>
		    </div>
        </div>
        <div class="span2">
	        <br/>
            <div >
                <a href="<?php Yii::app()->request->baseUrl ?> /index.php/contentBlock/page?url=tickets">
                <img src="<?php Yii::app()->request->baseUrl ?> /img/get_tickets.png" border="0" width="229" height="327" >
	            </a>
            </div>
        </div>
    </div>

	<!-- footer -->
	<div class="row" style="height:60px"></div>
	<div class="row">
        <div class="span5"></div>
        <div class="span4" style="position:relative">
            <p class="Body-P"><span class="Body-C">Copyright &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Training, Coaching &amp; Media 2013 </span></p>

            <div style="width:414px;height:51px;overflow:hidden;">
                <p class="Body-P"><span class="Body-C">Contact Details email@insightKLG</span></p>
                <p class="Body-P"><span class="Body-C">Hosted and Designed by <a href="http://www.wireflydesign.com" style="text-decoration:underline;">Wirefly Design</a></span></p>
            </div>
            <div style="position:absolute;left:66px;top:-43px;z-index:1">
                <img src="<?php Yii::app()->request->baseUrl ?> /img/footer_shell.png" border="0" >
            </div>
        </div>
        <div class="span3"></div>
	</div>
</div>
</body>

</html>
