<?php
/* @var $this ProductController */
/* @var $dataProvider CActiveDataProvider */

/*
$this->breadcrumbs=array(
	'Products',
);
*/
$this->menu=array(
/*	array('label'=>'Create Product', 'url'=>array('create')),
	array('label'=>'Manage Product', 'url'=>array('admin')),*/
);
?>

<center xmlns="http://www.w3.org/1999/html">
    <div class="left_col">

<br>
        <!-- Category buttons -->
        <div style="position:relative;">
		    <?php $left=0 ?>
		    <?php foreach ($categories as $category): ?>
                <div onclick="window.location='<?php echo $this->createUrl('product/indexGallery?galleryId=' . $category->id);?>'" class="gallerybutton" style="position:absolute;top:-80px;left:<?php echo $left; $left+=130; ?>px;z-index:1000"><?php echo $category->name?><a href="http://google.com"></a></div>
		    <?php endforeach; ?>
        </div>

        <div>
			<?php foreach ($products as $product): ?>
				<?php foreach ($product->images as $image): ?>
					<a href="<?php echo $this->createUrl('product/view?id=' . $product->id);?>"></a><img src="/userdata/image/gall_<?php echo $image->filename?>" longdesc="<?php echo $this->createUrl('product/view?id=' . $product->id);?>" alt="<?php echo $product->name?>" /></a
					<?php break; /* Only show 1 image for each product */ ?>
				<?php endforeach; ?>
			<?php endforeach; ?>
        </div>


    </div>
</center>

