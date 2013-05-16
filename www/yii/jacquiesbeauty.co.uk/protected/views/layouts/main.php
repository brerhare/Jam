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


<!-- Fixed header style -->
<style>
div#fixed-header-container {position:fixed; top:0px; margin:auto; z-index:10000; width:100%;}
div#fixed-header  {/*background:#000;*/ height:75px; /*opacity:0.8*/}
</style>


</head>

<body text="#000000" style="overflow-x:hidden; background:transparent url(<?php Yii::app()->request->baseUrl ?>/img/bg.jpg) repeat scroll top center">

<div class="container">

<!-- HEADER ROW -->

	<div id="fixed-header-container">
		<div id="fixed-header">
		
			<div class="row">
	  
				<!-- LEFT COLUMN -->
	
<!--Mega Menu-->
<link rel="stylesheet" type="text/css" href="/js/megamenu/jkmegamenu.css" />
<script type="text/javascript" src="/js/megamenu/jkmegamenu.js"></script>
<script>
<!--Mega Menu-->
//jkmegamenu.definemenu("anchorid", "menuid", "mouseover|click");
jkmegamenu.definemenu("megaanchor", "megamenu1", "mouseover");
</script>

				<div class="span2" style="position:relative; z-index:20000;">
					<div style="position:absolute; left:388px; top:30px">
						<!--Mega Menu Anchor-->
						<a href="http://www.wireflydesign.com" id="megaanchor" style="color:#000000;">Menu</a>
					</div>
			
					<!--Mega Menu Dropdown HTML-->
					<div id="megamenu1" class="megamenu" style="position: fixed; margin-top:20px; margin-left:-190px; opacity:0.925">

						<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
						<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
						<br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->

					    <div class="column">
	 				       <h3>Home</h3>
					        <ul>
					            <li><a href="http://www.javascriptkit.com">JavaScript Kit</a></li>
					            <li><a href="http://www.dynamicdrive.com/">Dynamic Drive</a></li>
					            <li><a href="http://www.cssdrive.com">CSS Drive</a></li>
					            <li><a href="http://www.codingforums.com">Coding Forums</a></li>
	        				</ul>
					    </div>

					    <div class="column">
					        <h3>Team</h3>
					        <ul>
					            <li><a href="http://www.cnn.com/">CNN</a></li>
					            <li><a href="http://www.msnbc.com">MSNBC</a></li>
					            <li><a href="http://www.google.com">Google</a></li>
					            <li><a href="http://news.bbc.co.uk">BBC News</a></li>
					        </ul>
					    </div>
	
					    <div class="column">
					        <h3>News</h3>
					        <ul>
					            <li><a href="http://www.news.com/">News.com</a></li>
					            <li><a href="http://www.slashdot.com">SlashDot</a></li>
					            <li><a href="http://www.digg.com">Digg</a></li>
					            <li><a href="http://www.techcrunch.com">Tech Crunch</a></li>
					        </ul>
					    </div>
	
						<div class="column">
					    	<h3>Health</h3>
					    	<ul>
					    	    <li><a href="http://www.news.com/">News.com</a></li>
					    	    <li><a href="http://www.slashdot.com">SlashDot</a></li>
					    	    <li><a href="http://www.digg.com">Digg</a></li>
					    	    <li><a href="http://www.techcrunch.com">Tech Crunch</a></li>
					    	</ul>
					    </div>
					    
					    <br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
					    <br style="clear: left" /> <!--Break after 3rd column. Move this if desired-->
	
					    <div class="column">
					        <h3>Treatment</h3>
					        <ul>
					            <li><a href="http://www.javascriptkit.com">JavaScript Kit</a></li>
					            <li><a href="http://www.dynamicdrive.com/">Dynamic Drive</a></li>
	            				<li><a href="http://www.cssdrive.com">CSS Drive</a></li>
					            <li><a href="http://www.javascriptkit.com/domref/">DOM Reference</a></li>
					            <li><a href="http://www.javascriptkit.com">JavaScript Kit</a></li>
					            <li><a href="http://www.dynamicdrive.com/">Dynamic Drive</a></li>
	            				<li><a href="http://www.cssdrive.com">CSS Drive</a></li>
					            <li><a href="http://www.javascriptkit.com/domref/">DOM Reference</a></li>
					            <li><a href="http://www.javascriptkit.com">JavaScript Kit</a></li>
					            <li><a href="http://www.dynamicdrive.com/">Dynamic Drive</a></li>
	            				<li><a href="http://www.cssdrive.com">CSS Drive</a></li>
					            <li><a href="http://www.javascriptkit.com/domref/">DOM Reference</a></li>
					        </ul>
					    </div>
	
					    <div class="column">
					        <h3>Products</h3>
					        <ul>
					            <li><a href="http://www.cnn.com/">CNN</a></li>
					            <li><a href="http://www.msnbc.com">MSNBC</a></li>
					            <li><a href="http://www.google.com">Google</a></li>
					        </ul>
					    </div>
	
					</div>
	
				</div>
	
				<!-- MIDDLE COLUMN -->
	
				<div class="span8">
		        	<div style="text-align:center;">
						<img src="<?php Yii::app()->request->baseUrl ?> /img/header.png" border="0" style="margin-right:0px; padding:0">
					</div>
				</div>
	
				<!-- RIGHT COLUMN -->
	
		        <div class="span2">
	    	    </div>
	
		    </div> <!-- /ROW -->
	
		</div> <!-- FIXED-HEADER -->
	</div> <!-- FIXED-HEADER-CONTAINER -->


	<!-- SPACER ROW TO ACCOUNT FOR THE FIXED HEADER -->
	<div class="row" style="height:80px">
	</div>


<!-- BODY ROW -->

    <div class="row">
  
	<!-- LEFT COLUMN -->

<!--		<div class="span1">
		</div> -->
		
	<!-- MIDDLE COLUMN -->

		<div class="span12" style="margin-left: 160px;">

			<!--Flex Slider-->
			<link rel="stylesheet" href="/js/flexslider/flexslider.css" type="text/css">
			<script src="/js/flexslider/jquery.flexslider.js"></script>

			<!-- Flex Slider custom CSS to handle sizing/clipping-->
			<style>
			/* NOTE! height wins in case of conflict */
			.flexslider {
				width: 900px;   /* Setting this clips the calculated height */
			}
			.slides {
				overflow:hidden;
				height: 250px; /* Setting this clips the calculated width */
			}
			</style>

			
			<!--Flex Slider-->
			<div class="flexslider">
				<ul class="slides">
					<li>
						<img src="/userdata/carousel/0.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/1.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/2.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/3.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/4.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/5.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/6.jpg" />
					</li>
					<li>
						<img src="/userdata/carousel/7.jpg" />
					</li>
				</ul>
			</div>

		</div>

	<!-- RIGHT COLUMN -->

<!--		<div class="span1">
		</div> -->

    </div> <!-- /ROW -->


<!-- TAB CONTENT -->

	<div class="row" style="height:60px">

	<!-- LEFT COLUMN -->

<!--		<div class="span1">
		</div> -->
		
	<!-- MIDDLE COLUMN -->

		<div class="span10" style="margin-left: 160px; width:908px; background-color:#ffffff;">


            <!--Body content-->
        <!--    <div style="height:50px; margin-top:-20px;"></div> -->
            <div id="content" style="margin-left:-33px; margin-top:20px;">
                <?php echo $content; ?>
            </div>



		</div>

	</div>


<!-- FOOTER -->

	<div class="row" style="height:60px"></div>

</div>
</body>

<script>
jQuery(document).ready(function($){

	$('.flexslider').flexslider({
		//itemHeight: 300,
		//itemWidth: 610,
		itemMargin: 5,
		//minItems: 1,
		//maxItems: 1,
		animation: "fade",
	});

});
</script>

</html>
