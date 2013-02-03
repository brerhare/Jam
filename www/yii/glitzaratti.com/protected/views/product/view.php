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

<style>
	/* Glitz specific */
    .Normal-P
    {
        margin:0.0px 0.0px 0.0px 0.0px;
	    width:600px;
	    text-align:left;
	    font-weight:400;
	    color:#ede587;
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
    $('#zoom-container').zoom();
	var imgSwap = [];
    $("#gallery li img").each(function(){
		imgUrl = this.alt;
		imgSwap.push(imgUrl);
	});
	$(imgSwap).preload();
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
				<?php foreach ($model->images as $image): ?>
					<img src="/userdata/image/<?php echo $image->filename;?>" height='500' id='gallery-image'/>
					<?php break; ?>
				<?php endforeach; ?>

		</span>
		<p class="Normal-P">
		<?php echo nl2br($model->description)?>
		</p>
		<ul>
			<?php foreach ($model->images as $image): ?>
				<li><img src="/userdata/image/gall_<?php echo $image->filename;?>" alt="/userdata/image/<?php echo $image->filename;?>" height='150'/></li>
			<?php endforeach; ?>
		</ul>
	</div>

<!-- start of a thumbs-down-the-left container to replace the above
<div class="grid">
    <div style="width:100%; height:500px">
        Sidebar
    </div>
    <div style="width:150px">
        Main Content
    </div>
</div>         -->
