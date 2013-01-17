<?php
/* @var $this SiteController */

/* $this->pageTitle=Yii::app()->name; */
?>

<link rel="stylesheet" href="/scripts/imageflow/imageflow.css" type="text/css" />
<script src="/scripts/imageflow/imageflow.js" type="text/javascript"></script>

<script>
domReady(function(){
	var instanceOne = new ImageFlow();
	instanceOne.init({
		ImageFlowID:'myCarousel',
		reflectPath:'/scripts/imageflow/',
		imagePath:'/home/kim/dev/src/www/yii/glitzaratti.com/',
		circular:true});
	});
</script>


<center>
    <div class="left_col">
        <div id="myCarousel" class="imageflow">
            <img src="/userdata/image/AB large multi jewel.jpg" longdesc="" width="500" height="500" alt="A Shoe" />
            <img src="/userdata/image/apple clutch.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_2.png" width="300" height="400" alt="Image 2" />
            <img src="/userdata/image/baby2.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_3.png" width="400" height="500" alt="Image 3" />
            <img src="/userdata/image/baby.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_2.png" width="300" height="400" alt="Image 4" />
            <img src="/userdata/image/bag.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_1.png" width="400" height="300" alt="Image 5" />
            <img src="/userdata/image/encrusted.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_1.png" width="400" height="300" alt="Image 5" />
            <img src="/userdata/image/heart.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_1.png" width="400" height="300" alt="Image 5" />
            <img src="/userdata/image/ring clutch.jpg" longdesc="http://finnrudolph.de/content/imageflow/example_1.png" width="500" height="500" alt="Image 5" />
        </div>

		<div>
            <img src="/img/glassbutton.png" alt="Gall-1" style="position:absolute;top:720px;left:150px;z-index:1000"/>
            <img src="/img/glassbutton.png" alt="Gall-2" style="position:absolute;top:720px;left:300px;z-index:1000"/>
		</div>

    </div>
</center>


<div style='font-family:"Arial", sans-serif; color:#ffffff; font-size:16.0px; line-height:1.27em;'>
<br><br>
    <center>
	<table>
		<tr>
			<td>
				Coming Soon - The new Glitzaratti Collection
			</td>
		</tr>
		<tr>
			<td>
				Enter your details <?php echo CHtml::link('here', $this->createAbsoluteUrl('site/contact')); ?> and we shall notify you when the new collection has been made available
			</td>
		</tr>
	</table>
    </center>







</div>