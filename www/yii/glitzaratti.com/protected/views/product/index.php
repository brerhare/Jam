<?php
/* @var $this ProductController */
/* @var $product Product */
/* @var $category Category */
/* @var $catArray array */

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

<script>
function refreshDisplay()
{
	var inputs = document.getElementsByTagName("input");
	var checkList = '';
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].type == "checkbox") {
			if (inputs[i].checked) {
				checkList += inputs[i].value.substr(4) + '|';
			}
		}
	}
	/* @@TODO: Remove this evil EVIL hack ... */
	url = 'http://www.glitzaratti.com/index.php' + '/product/index?category_id=' + checkList;
	//alert(url);
	this.document.location.href = url;
}
</script>

<style>
        /* Glitz specific */
    .Normal-P
    {
        margin:0.0px 0.0px 0.0px 0.0px;
        /*width:800px;*/
	    font-size:100%;
        text-align:left;
        font-weight:400;
        color:#ede587;
    }
    .Big-P {
	    color:#4b482a;
	    font-weight:800;
	    font-size: 175%;
    }
    .Medium-P {
        color:#4b482a;
        font-weight:800;
        font-size: 120%;
    }

</style>

<center xmlns="http://www.w3.org/1999/html">
    <div class="left_col">

<table>
	<tr>
		<td style="vertical-align:top; width:125px">
            <!-- Category checkboxes -->
			<div class="Big-P">Filter your view</div>
		    <?php foreach ($categories as $category): ?>
				<?php $chk='';?>
				<?php if (in_array($category->id, $catArray) || ($catArray[0] == 'all')):?>
					<?php $chk=' checked ';?>
				<?php endif;?>
				<p class="Normal-P">
				<input type="checkbox" id="cat-<?php echo $category->id;?>" <?php echo $chk;?> value="cat-<?php echo $category->id;?>" onClick="refreshDisplay();">
				<?php echo $category->name;?>
				</p>
		    <?php endforeach; ?>
		</td>
		<td style="vertical-align:top; width:400px">
			<?php foreach ($categories as $category): ?>
				<?php if (in_array($category->id, $catArray) || ($catArray[0] == 'all')):?>
					<?php foreach ($category->products as $product): ?>
						<?php foreach ($product->images as $image): ?>
							<table>
								<tr>
									<td style="vertical-align:top; width:125px">
										<a href="<?php $showName = str_replace(" ", "-", $product->name); echo $this->createUrl('product/view?id=' . $product->id) . '&name=' . $showName;?>"><img src="/userdata/image/gall_<?php echo $image->filename?>" longdesc="<?php echo $this->createUrl('product/view?id=' . $product->id);?>" alt="<?php echo $product->name?>" width=120/></a><br/>
									</td>
									<td>
										<div class=Medium-P><?php echo $product->name;?></div>
										<p class=Normal-P><?php echo $product->description?></p>
									</td>
								</tr>
							</table>
							<?php break; /* Only show 1 image for each product */ ?>
						<?php endforeach; ?>
					<?php endforeach; ?>
				<?php endif ?>
			<?php endforeach; ?>
		</td>
	</tr>
</table>

    </div>
</center>

