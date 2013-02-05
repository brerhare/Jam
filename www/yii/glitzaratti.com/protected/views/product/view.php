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
	    width:800px;
	    text-align:left;
	    font-weight:400;
	    color:#ede587;
    }
    .Normal-P:first-line { color:#4b482a; font-weight:800; font-size: 200%;}
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

<p class=Normal-P">
        <center>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="dilbert@microboot.com">
<input type="hidden" name="lc" value="GB">
<input type="hidden" name="item_name" value="Clutch bag">
<input type="hidden" name="item_number" value="123">
<input type="hidden" name="button_subtype" value="services">
<input type="hidden" name="no_note" value="0">
<input type="hidden" name="shipping" value="0.00">
<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
	<center>
<table>
<tr><td><input type="hidden" name="on0" value="Bag size">Bag size</td></tr><tr><td><select name="os0">
    <option value="Small">Small 0.01 GBP</option>
    <option value="Medium">Medium 0.01 GBP</option>
    <option value="Large">Large 0.01 GBP</option>
</select> </td></tr>
</table>
		</center>
<input type="hidden" name="currency_code" value="GBP">
<input type="hidden" name="option_select0" value="Small">
<input type="hidden" name="option_amount0" value="0.01">
<input type="hidden" name="option_select1" value="Medium">
<input type="hidden" name="option_amount1" value="0.01">
<input type="hidden" name="option_select2" value="Large">
<input type="hidden" name="option_amount2" value="0.01">
<input type="hidden" name="option_index" value="0">
<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">

</form>
        </center>
</p>

        <center>
        <p class="Normal-P">
			<?php echo nl2br($model->name . ' - £' . $model->price .'<br>' . $model->description)?>
        </p>
			</center>
		<p></p>
		<table><tr><td style="vertical-align:top; width:150px">

            <ul>
				<?php foreach ($model->images as $image): ?>
                <li><img src="/userdata/image/gall_<?php echo $image->filename;?>" alt="/userdata/image/<?php echo $image->filename;?>" style="padding:5px" width='150'/></li>
				<?php endforeach; ?>
            </ul>

			</td><td style="vertical-align:top">

		<span class='zoom' id='zoom-container'>
			<p>Hover</p>
				<?php foreach ($model->images as $image): ?>
					<img src="/userdata/image/<?php echo $image->filename;?>" height='500' id='gallery-image'/>
					<?php break; ?>
				<?php endforeach; ?>

		</span>
			</td></tr></table>
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
