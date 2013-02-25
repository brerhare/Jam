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
	// Paypal shipping selection change
    $("#os1").change(function() {
        if      ($(this).val() == "UK £7.00")             $('#shipping').val("7.00");
        else if ($(this).val() == "Europe £15.00")        $('#shipping').val("15.00");
        else if ($(this).val() == "USA/Canada £35.00")    $('#shipping').val("35.00");
        else if ($(this).val() == "Rest of World £51.00") $('#shipping').val("51.00");
	    else alert('Error - invalid shipping');
    });
});

$.fn.preload = function(){
	this.each(function(){
		$('<img/>')[0].src = this;
	});
}
</script>



<?php
$mainpanel = new JamVertPanel();
$mainpanel->addHtmlOption('style','width: 700px;');

$title  = new JamElement("h1","Product Title");

$panel1 = new JamHorzPanel();
$panel2 = new JamHorzPanel();

$mainpanel->add($title);
$mainpanel->add($panel1);
$mainpanel->add($panel2);

$picpanel = new JamVertPanel();
$picpanel->addHtmlOptions(array(
	'style'=>'border: 1px solid gray; width: 300px; height: 300px; text-align: center; margin: 3px;'));
$panel1->add($picpanel);

$actionpanel = new JamVertPanel();
$panel1->add($actionpanel);

$paypalpanel = new JamHorzPanel();
$actionpanel->add($paypalpanel);
$paypalpanel->addHtmlOptions(array(
	'style'=>'border: 5px solid gray; width:100px height: 100px; text-align: center; margin: 3px;'));

$thumbspanel = new JamHorzPanel();
$actionpanel->add($thumbspanel);
$thumbspanel->addChildHtmlOptions(array(
	'style'=>'border: 3px solid gray; width: 100px; height: 100px; text-align: center; margin: 3px;'));
$thumbspanel->add(new JamElement("b","bold text"));
$thumbspanel->add(CHtml::image('images/sample2.png'));
$thumbspanel->add("simple raw text");

$panel2->add(new JamElement("b","product desc"));

//$lpanel->add(new JamImage('images/sample1.png'));
//$lpanel->add(CHtml::textArea('text at left built using CHtml','demo text'));


// calling 'render' without any argument will echo the result,
// in order to return the output please use $mainpanel->render(false);
$mainpanel->render();
?>



<!-- Paypal button integration -->
<div style="width:250px; display:block;">
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="charset" value="utf-8"> <!-- This gets rid of the weird A before the £ -->
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="sales@glitzaratti.com">
		<input type="hidden" name="lc" value="GB">
		<!-- Default item_name and amount follow, but could be overridden by the dropdown list -->
		<input type="hidden" name="item_name" value="<?php echo $model->name?>">
		<input type="hidden" name="amount" value="<?php echo $model->price;?>">
		<input type="hidden" name="item_number" value="<?php $model->id?>">
		<input type="hidden" name="button_subtype" value="services">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="shipping" id="shipping" value="7.00">
		<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
		<table >
			<tr>
				<!-- Size -->
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
<?php endif;?>

				<!-- Shipping -->
				<td>
					<p class="Buy-P">
						<input type="hidden" name="on1" value="Shipping">Shipping
					</p>
				</td>
				<td>
					<select name="os1" id="os1">
						<option value="<?php echo "UK £7.00";?>"><?php echo "UK £7.00";?></option>
						<option value="<?php echo "Europe £15.00";?>"><?php echo "Europe £15.00";?></option>
						<option value="<?php echo "USA/Canada £35.00";?>"><?php echo "USA/Canada £35.00";?></option>
						<option value="<?php echo "Rest of World £51.00";?>"><?php echo "Rest of World £51.00";?></option>
	<?php $listItemCount=0;?>
	<?php foreach ($model->category->sizes as $size): ?>
						<input type="hidden" name="option_select<?php echo $listItemCount;?>" value="<?php echo $size->text;?>">
						<input type="hidden" name="option_amount<?php echo $listItemCount++;?>" value="<?php echo $model->price;?>">
	<?php endforeach; ?>
					</select>
				</td>


				<!-- Button -->
				<td>
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
<?php echo nl2br($model->name . '&nbsp&nbsp&nbsp&nbsp £' . $model->price . '<br>' . $model->description)?>
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
