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
    .Buy-P
    {
        margin:0.0px 0.0px 0.0px 0.0px;        padding:0.0px 0.0px 0.0px 0.0px;
        text-align:left;
        font-weight:400;
	    font-size:130%;
        color:#ede587;
    }
</style>

<style type="text/css">
ul {list-style:none;}
.text-center {text-align: center; padding: 10px 0;}
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

<!-- Paypal button integration -->
<div style="width:250px; display:block;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="sales@glitzaratti.com">
		<input type="hidden" name="lc" value="GB">
		<!-- Default item_name and amount follow, but could be overridden by the dropdown list -->
		<input type="hidden" name="item_name" value="<?php echo $model->name?>">
		<input type="hidden" name="amount" value="<?php echo $model->price;?>">
		<input type="hidden" name="item_number" value="<?php $model->id?>">
		<input type="hidden" name="button_subtype" value="services">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="shipping" value="7.00">
		<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
		<table >
			<tr>
				<td>
<?php if (count($model->category->sizes)):?>
					<p class="Buy-P">
						<input type="hidden" name="on0" value="Size">Size
					</p>
				</td>
				<td>
					<select name="os0">
	<?php foreach ($model->category->sizes as $size): ?>
						<option value="<?php echo $size->text;?>"><?php echo $size->text;?></option>
	<?php endforeach; ?>
	<?php $listItemCount=0;?>
	<?php foreach ($model->category->sizes as $size): ?>
							<input type="hidden" name="option_select<?php echo $listItemCount;?>" value="<?php echo $size->text;?>">
							<input type="hidden" name="option_amount<?php echo $listItemCount++;?>" value="<?php echo $model->price;?>">
	<?php endforeach; ?>
					</select>
				</td>
				<td>
<?php endif;?>
					<input type="image" src="http://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif" border="0" name="submit" alt="Make payments with PayPal - it's fast, free and secure!">
				</td>
			</tr>
		</table>
		<input type="hidden" name="currency_code" value="GBP">
		<input type="hidden" name="option_index" value="0">
	</form>
</div>


<div id="gallery">
	<center>
		<p class="Normal-P">
<?php echo nl2br($model->name . '&nbsp&nbsp&nbsp&nbsp £' . $model->price . '&nbsp&nbsp+ £7.00 UK p+p' . '<br>' . $model->description)?>
		</p>
	</center>
	<p></p>
	<table>
		<tr>
			<td style="vertical-align:top; width:150px">
				<ul>
<?php foreach ($model->images as $image): ?>
					<li><img src="/userdata/image/gall_<?php echo $image->filename;?>" alt="/userdata/image/<?php echo $image->filename;?>" style="padding:5px" width='150'/></li>
<?php endforeach; ?>
				</ul>
			</td>
			<td style="vertical-align:top">
				<span class='zoom' id='zoom-container'>
					<p>Hover</p>
<?php foreach ($model->images as $image): ?>
					<img src="/userdata/image/<?php echo $image->filename;?>" height='500' id='gallery-image'/>
<?php break; ?>
<?php endforeach; ?>
				</span>
			</td>
		</tr>
	</table>
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
