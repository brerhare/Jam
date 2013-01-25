<?php
/* @var $this SiteController */
/* @var $galleries Gallery[] */

/* $this->pageTitle=Yii::app()->name; */
?>

<link rel="stylesheet" href="/scripts/imageflow/imageflow.css" type="text/css" />
<script src="/scripts/imageflow/imageflow.js" type="text/javascript"></script>

	<style>
		.play, .pause {visibility:hidden}   /* Hide the carousel start and pause buttons */
		.gallerybutton {
            background-image:url(/img/carouselbutton.png);
            background-repeat:no-repeat;
            width:123px;
			height:54px;
			font-size:11px;
			padding-top:20px;
			cursor:pointer;
		}
	</style>

<script>
domReady(function(){
	var instanceOne = new ImageFlow();
	instanceOne.init({
		ImageFlowID:'myCarousel',
		reflectPath:'/scripts/imageflow/',
		imagePath: '<?php echo Yii::app()->basePath . '/../'?>',
		slideshow:true,
        slideshowSpeed:5000,
        slideshowAutoplay:true,
        imageCursor:'pointer',
		circular:true});
	});

	$(".gallerybutton").click(function(){
     window.location=$(this).find("a").attr("href");
     return false;
});
</script>

<center>
	<div class="left_col">
		<div id="myCarousel" class="imageflow">
			<?php foreach ($galleries as $gallery): ?>
				<?php if ($gallery->carousel): ?>
					<?php foreach ($gallery->products as $product): ?>
						<?php foreach ($product->images as $image): ?>
							<img src="/userdata/image/gall_<?php echo $image->filename?>" longdesc="<?php echo $this->createUrl('site/index?product_id=' . $product->id);?>" alt="<?php echo $product->name?>" />
						<?php endforeach; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<!-- Gallery buttons -->
		<div style="position:relative">
			<?php $left=0 ?>
			<?php foreach ($galleries as $gallery): ?>
				<?php if ($gallery->carousel): ?>
					<div onclick="window.location='http://www.bbc.com'" class="gallerybutton" style="position:absolute;top:-80px;left:<?php echo $left; $left+=150; ?>px;z-index:1000"><?php echo $gallery->name?><a href="http://google.com"></a></div>
				<?php endif; ?>
			<?php endforeach; ?>
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
