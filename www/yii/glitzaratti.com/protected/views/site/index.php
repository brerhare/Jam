<?php
/* @var $this SiteController */

/* @var $galleries Gallery[] */
/* @var $galleryId selected gallery to show */


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
			font-size:12px;
			font-weight:bold;
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
        slideshowSpeed:2000,
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
			<?php $method = 1;?>
			<?php if ($galleryId != 0): // --- List all products' images in selected gallery ?>
				<?php foreach ($galleries as $gallery): ?>
					<?php if ($gallery->carousel): ?>
						<?php if ($gallery->id == $galleryId): ?>
							<?php foreach ($gallery->products as $product): ?>
								<?php foreach ($product->images as $image): ?>
									<img src="/userdata/image/gall_<?php echo $image->filename?>" longdesc="<?php echo $this->createUrl('product/view?id=' . $product->id);?>" alt="<?php echo $product->name?>" />
									<?php break; /* Only show 1 image for each product */ ?>
								<?php endforeach; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php else: // --- List only the most recent products' images ?>
				<?php $productlist = Product::model()->recently()->findAll();?>
				<?php foreach ($productlist as $product):?>
					<?php foreach ($product->images as $image): ?>
                        <img src="/userdata/image/gall_<?php echo $image->filename?>" longdesc="<?php echo $this->createUrl('product/view?id=' . $product->id);?>" alt="<?php echo $product->name?>" />
						<?php break; /* Only show 1 image for each product */ ?>
					<?php endforeach; ?>
				<?php endforeach;?>
			<?php endif;?>
		</div>

		<!-- Gallery buttons -->
		<div style="position:relative;">
			<?php $left=0 ?>
			<?php foreach ($galleries as $gallery): ?>
				<?php if ($gallery->carousel): ?>
					<div onclick="window.location='<?php echo $this->createUrl('site/indexGallery?galleryId=' . $gallery->id);?>'" class="gallerybutton" style="position:absolute;top:-80px;left:<?php echo $left; $left+=130; ?>px;z-index:1000"><?php echo $gallery->name?><a href="http://google.com"></a></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</center>

