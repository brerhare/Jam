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

<!-- Menu -->

/* Custom css for bxslider */
.slider {
    position:relative;
    z-index:1;   
} 
.imgoverlay {
    position: relative;   
    width:100%;
/*    margin-left:26px;
    margin-top:-75px;*/
    z-index:9999;
}
.bx-wrapper {
max-height:250px;
height:250px;
overflow:hidden;
}
ul.bxslider>li {
	left:-25px;
	top:-5px
}
</style>

</head>

<body text="#000000" style="background:transparent url(<?php Yii::app()->request->baseUrl ?>/img/blackboard_bg.jpg) repeat scroll top center">

<div class="container">

    <div class="row" style="height:5px"></div>

<!-- HEADER ROW -->

    <div class="row">
  
	<!-- LEFT COLUMN -->

		<!-- Top shell logo -->
        <div class="span2">
            <div style="Xtext-align:right;">
    	        <br/>
	            <img src="<?php Yii::app()->request->baseUrl ?> /img/Logo.png" border="0" width="229" height="327" style="margin-right:-120px">
	        </div>
        </div>

	<!-- MIDDLE COLUMN -->

        <div class="span7">
	        
			<!-- bxSlider Javascript file -->
			<script src="/bxslider/jquery.bxslider.js"></script>
			<!-- bxSlider CSS file -->
			<link href="/bxslider/jquery.bxslider.css" rel="stylesheet" />

			<style type="text/css">
			.bx-wrapper img {
				height: 260px;
			}
			.bx-wrapper .bx-viewport {
				border: none;
				left: 0px;
			}
			</style>

			<div class="slider">
				<ul class="bxslider">
				<?php
				$carouselItems = CarouselBlock::model()->findAll();
				foreach ($carouselItems as $carouselItem):
					echo "<li>" . "<div style='height:260px'>" . $carouselItem->content . "</div>" . "</li>";
				endforeach;
				?>
				</ul> 
			</div>

			<div class="imgoverlay" style="margin-top:-110px; margin-left:-2px;">
				<img style="max-width:none; z-index:10000" src="<?php Yii::app()->request->baseUrl ?> /img/CarouselShapedOverlay.png" border="0" >
			</div>
        </div>

	<!-- RIGHT COLUMN -->

        <div class="span3">
	        <br/>
	        <!-- Tickets logo/link -->
            <div style="margin-left:10px">
                <a href="<?php Yii::app()->request->baseUrl ?> /index.php/contentBlock/page?url=tickets">
                <img src="<?php Yii::app()->request->baseUrl ?> /img/book_now.png" border="0" width="150" >
	            </a>
            </div>
        </div>

    </div> <!-- /ROW -->


<!-- BODY ROW -->

    <div class="row">
  
	<!-- LEFT COLUMN -->

        <div class="span2">

            <!--  menu content-->
	        <br/>
            <div style="position:relative; margin-right:12px; background-color:#ffffff; border-radius: 10px; -moz-border-radius: 10px; -webkit-border-radius: 10px; padding: 10px 0 0 20px">
	        <?php $menuitems = ContentBlock::model()->findAll(array('order'=>'sequence'));
	        $first = true;
	        $showFirst = false;
	        $isHomePage = false;
	        if (!isset($_GET['url']))
	        	$showFirst = true;
            foreach ($menuitems as $menuitem):?>
	            <?php if (strtoupper($menuitem->active == 'Y')):
	            	$menuclass = "menuitemx";
	            	$menustyle = "color:#000000;";
	            	if ((isset($_GET['url'])) && (!strcmp($menuitem->url, $_GET['url'])))
	            	{
	            		$menuclass = "menuitemsel";
	            		$menustyle .= " text-decoration:none;";
	            	}
	            	echo "<a class='" . $menuclass . "' style='" . $menustyle . "' href='" . Yii::app()->request->baseUrl . "/index.php/contentBlock/page?url=" . $menuitem->url . "'>";?>
                    <?php echo $menuitem->title . "</a>";
                    if (($showFirst) || ((isset($_GET['url'])) && (!strcmp($menuitem->url, $_GET['url'])))):?>
						<img src="<?php Yii::app()->request->baseUrl ?>/img/Menu_line.png"/>
						<div style="height:4px;"></div>
					<?php if ($first)
							$isHomePage = true; ?>
					<?php else:?>
						<div style="height:8px;"></div>
                    <?php endif;?>
                    <?php $showFirst = false; ?>
                    <?php $first = false; ?>
			    <?php endif;?>
            <?php endforeach;?>
	        </div>
        </div>

	<!-- MIDDLE COLUMN -->

        <div class="span7">
        	<!-- Front page block images -->
			<?php if ($isHomePage): ?>

<style>
img.frontpage{
	border:1px solid #;
	-webkit-border-radius: 20px;
	-moz-border-radius: 20px;
	border-radius: 20px;
	width:320px;
	height:175px;
	/* Now unround the bottom corners */
    -webkit-border-bottom-left-radius: 0px;
    -webkit-border-bottom-right-radius: 0px;
    -moz-border-radius-bottomleft: 0px;
    -moz-border-radius-bottomright: 0px;
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
}
</style>

<table border="0" cellpadding="10">
<tr>
<td style="width:320px">
<img class="frontpage" src="<?php Yii::app()->request->baseUrl ?>/userdata/frontpage/topleft.jpg"/>
<img style="width:320px;margin-top:-40px" src="<?php Yii::app()->request->baseUrl ?>/img/L_line.png"/>
</td>
<td style="width:320px">
<img class="frontpage" src="<?php Yii::app()->request->baseUrl ?>/userdata/frontpage/topright.jpg"/>
<img style="width:320px;margin-top:-40px" src="<?php Yii::app()->request->baseUrl ?>/img/R_line.png"/>
</td>
</tr>
<tr>
<td style="width:320px">
<img class="frontpage" src="<?php Yii::app()->request->baseUrl ?>/userdata/frontpage/bottomleft.jpg"/>
<img style="width:320px;margin-top:-40px" src="<?php Yii::app()->request->baseUrl ?>/img/L_line.png"/>
</td>
<td style="width:320px">
<img class="frontpage" src="<?php Yii::app()->request->baseUrl ?>/userdata/frontpage/bottomright.jpg"/>
<img style="width:320px;margin-top:-40px" src="<?php Yii::app()->request->baseUrl ?>/img/R_line.png"/>
</td >
</tr>
</table>

			<?php endif; ?>

            <!--Body content-->
	    <!--    <div style="height:50px; margin-top:-20px;"></div> -->
	        <div id="content" style="margin-left:-33px; margin-top:20px;">
	            <?php echo $content; ?>
		    </div>
        </div>

	<!-- RIGHT COLUMN -->

        <div class="span3">

            <!-- Social media links -->
			<div class="Body-C" style="margin-left:10px; margin-top:15px">
			<div style="padding:5px"> <u>Keep in touch</u> </div>

			<table >
				<tr>
					<td style="padding:1px" width="10%" >
						<a href="https://www.facebook.com/InsightKLG" target="_blank"><img src="<?php Yii::app()->request->baseUrl ?> /img/FB_logo.png" border="0" ></a>
					</td>
					<td style="padding:1px" width="90%">
						<a style="color:#ffffff" href="https://www.facebook.com/InsightKLG" target="_blank">/InsightKLG</a>
					</td>
				</tr>
				<tr>
					<td style="padding:1px" width="10%" >
						<a href="https://twitter.com/SalonSenseKLG" target="_blank"><img src="<?php Yii::app()->request->baseUrl ?> /img/Twitter_logo.png" border="0" ></a>
					</td>
					<td style="padding:1px" width="10%" >
						<a style="color:#ffffff" href="https://twitter.com/SalonSenseKLG" target="_blank">@SalonSenseKLG</a>
					</td>
				</tr>
				<tr>
					<td style="padding:1px" width="10%" >
						<a href="https://twitter.com/KathInsight" target="_blank"><img src="<?php Yii::app()->request->baseUrl ?> /img/InsightTwitter_logo.png" border="0" ></a>
					</td>
					<td style="padding:1px" width="10%" >
						<a style="color:#ffffff" href="https://twitter.com/KathInsight" target="_blank">@KathInsight</a>
					</td>
				</tr>
				<tr>
					<td style="padding:1px" width="10%" >
						<a href="http://uk.linkedin.com/in/insightklg" target="_blank"><img src="<?php Yii::app()->request->baseUrl ?> /img/LinkedIn_logo.png" border="0" ></a>
					</td>
					<td style="padding:1px" width="10%" >
						<a style="color:#ffffff" href="http://uk.linkedin.com/in/insightklg" target="_blank">/insightklg</a>
					</td>
				</tr>
			</table>

			</div>
        </div>
        
    </div> <!-- /ROW -->


<!-- FOOTER -->

	<div class="row" style="height:60px"></div>
	<div class="row">
        <div class="span3"></div>
        <div class="span5" style="position:relative">
            <p class="Body-P"><span class="Body-C">Copyright &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp Training, Coaching &amp; Media 2013 </span></p>

            <div style="width:414px;height:51px;overflow:hidden;">
                <p class="Body-P"><span class="Body-C">Contact Details email@insightKLG.co.uk</span></p>
                <p class="Body-P"><span class="Body-C">Hosted and Designed by <a href="http://www.wireflydesign.com" style="text-decoration:underline;">Wirefly Design</a></span></p>
            </div>
            <div style="position:absolute;left:125px;top:-44px;z-index:1">
                <img src="<?php Yii::app()->request->baseUrl ?> /img/footer_shell.png" border="0" >
            </div>
        </div>
        <div class="span3"></div>
	</div>
</div>
</body>

<script>
$(document).ready(function(){


$('.bxslider').bxSlider({
  auto: true,
  slideWidth : 675,
  pause: 4000,
  controls: false,
  pager: true,
  autoControls: false,
  mode: 'fade'
});

});
</script>

</html>
