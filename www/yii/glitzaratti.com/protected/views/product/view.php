<?php
/* @var $this ProductController */
/* @var $model Product */
?>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="/scripts/jquery.zoom/jquery.zoom.js" type="text/javascript"></script>

<style>
    .zoomIcon {
        width:33px;
        height:33px;
        position:absolute;
        top:0;
        right:0;
        background:url(/zoom/icon.png);
    }
    .zoom {
        display:inline-block;
        position:relative;
    }
    .zoom img {
        display: block;
        max-width:none;
    }
    .zoom p {
        position:absolute;
        top:3px;
        right:28px;
        color:#555;
        font:bold 13px/1 sans-serif;
    }
</style>


<style type="text/css">
ul {list-style:none;}
.text-center {text-align: center; padding: 10px 0;}
#gallery {Xwidth: 660px; margin: 0 auto;}
#gallery ul {padding-right: 10px; padding-top:10px;}
#gallery li {display: inline; margin-right: 3px;}
</style>

<script type="text/JavaScript">
$(document).ready(function(){
	$("#gallery li img").hover(function(){
		$('#gallery-image').attr({src: $(this).attr('alt')});
		$('#zoom-container').zoom();
	});
/*	var imgSwap = [];
    $("#gallery li img").each(function(){
		imgUrl = this.src.replace('thumb/', '');
		imgSwap.push(imgUrl);
	});
	$(imgSwap).preload(); */
    $('#zoom-container').zoom();
});

$.fn.preload = function(){
	this.each(function(){
		$('<img/>')[0].src = this;
	});
}
</script>


	<h2 class="text-center">Product view</h2>
	<div id="gallery">
		<span class='zoom' id='zoom-container'>
			<p>Hover</p>
			<img src='/userdata/image/18.jpg' xwidth='555' height='500' id='gallery-image'/>
		</span>
		<ul>
			<li><img src="/userdata/image/gall_18.jpg" alt="/userdata/image/18.jpg" height='150'/></li>
            <li><img src="/userdata/image/gall_26.jpg" alt="/userdata/image/26.jpg" height='150'/></li>
		</ul>
	</div>




<h1>View Product #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'price',
		'description',
		'weight_kg',
		'pack_height_mm',
		'pack_width_mm',
		'pack_depth_mm',
		'category_id',
	),
)); ?>
