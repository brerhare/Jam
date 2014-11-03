<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'ticket-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

<style>
#page {
	border:0;
}
/* @@NB: This overrides the some bootstrap settings */
.span7 {width:94%}
.span-19 {margin-left:1px}
</style>

<!--- TICKET DIV ---------------------------------------------------------------------------------------------------->

<style>

.row {
	margin-left: 3px;
}
table td, table th {
	padding: 0px 5px 0px 5px;
}
.hide {
	display:none;
}
.show {
	display:visible;
}
div.form input, div.form textarea, div.form select {
margin: 0.2em 0 0.2em 0;
}
</style>

<script>
var lc = 0;
var bookingFee = 0;

function calcValues()
{
//alert(lc);
    var total = 0;
    bookingFee = 0;
    for (var x = 0; x < lc; x++)
    {
        var linePrefix = 'line_' + x;
		elem = document.getElementById(linePrefix);
		if (!(elem))
			continue;
        lineTotal = parseFloat(document.getElementById(linePrefix).textContent);
        total += lineTotal;
    }
    document.getElementById('total').textContent = '£ ' + total.toFixed(2);
    document.getElementById('rtotal').value = total.toFixed(2);
}

$(document).ready(function() {
    calcValues();
 });
 
</script>

<?php
	$fst = 1;
	$lc = 0;
	$curEvent = -1;
	$curArea = -1;
	$curTicketType = -1;
	$criteria = new CDbCriteria;
//echo $ip . ':' . Yii::app()->session['uid'];
	$criteria->addCondition("ip = '" . $ip . "'");
//	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$criteria->order = "vendor_id ASC, event_id ASC, http_ticket_type_area ASC, http_ticket_type_id ASC";
	$orders = Order::model()->findAll($criteria);
	foreach ($orders as $order):
		// First item? If so display the page header
		if ($fst)
		{
			echo '<div class="row" style="margin-top:10px;">
				<div class="span7 well" style="background-color:#d3d3d3; margin-left:0px; margin-bottom:0px; padding-bottom:0px">
					<center><p style="font-size:25px;"><i>Please check your order details below before proceeding.<br/>&nbsp</center></i></p>
				</div>
			</div>';
			$fst = 0;
		}

		// Event change?
		if ($order->event_id != $curEvent)
		{
			$curEvent = $order->event_id;
//echo 'New Event Starting';
			// Pick up event
			$criteria = new CDbCriteria;
//			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("id = " . $order->event_id);
			$event = Event::model()->find($criteria);
			if (!($event))
				die("No event");
			genHeading($event);
			?>
			<table>
	            <tr style="color:#000000;">
	                <td width="30%">
	                </td>
	                <td width="20%">
	                </td>
	                <td width="10%" style="text-align:right">
	                <b>Qty</b>
	                </td>
	                <td width="15%" style="text-align:right">
	                <b>Price</b>
	                </td>
	                <td width="15%" style="text-align:right">
	                <b>Line Total</b>
	                </td>
	                <td width="10%" >
					<b>Remove</b>
	                </td>
	            </tr>
			</table>
			<?php
		}
		// Area change?
		if ($order->http_ticket_type_area != $curArea)
		{
			$curArea = $order->http_ticket_type_area;
//echo 'New Area Starting';
        	$criteria = new CDbCriteria;
//        	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$criteria->addCondition("id = " . $curArea);
        	$area = Area::model()->find($criteria);
			if (!($area))
				die("No area");
?>
<?php
		}
		// Ticket Type change?
		// We no longer care about ticket type change - users can go back and add tickets of the same event/area/tt to their cart
		/////////////////////////////////if ($order->http_ticket_type_id != $curTicketType)
		{
			$curTicketType = $order->http_ticket_type_id;
//echo 'New Ticket Type Starting';
			$criteria = new CDbCriteria;
			$criteria->addCondition("ticket_area_id = " . $curArea);
			$criteria->addCondition("id = " . $curTicketType);
//			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$ticketType = TicketType::model()->find($criteria);
			if (!($ticketType))
				die("No ticket type");
			$linePrefix = "event_" . $curEvent . "_area_" . $curArea . "_tickettype_" . $curTicketType;
?>
			<table style="margin:0px">
			<tr id="<?php echo $linePrefix;?>">



                <td width="30%">
                <?php $tmp = $linePrefix . "id_" . $ticketType->id; ?>

                <?php echo $area->description;?>

                <?php echo "<div id='" . $tmp . "'><div>";?>
                <?php echo "<input type='hidden' [id='p" . $linePrefix . "id' name='p" . $linePrefix . "id' ] value='" . $ticketType->id . "' />";?>
                <?php echo "<input type='hidden' [id='p" . $linePrefix . "area' name='p" . $linePrefix . "area' ] value='" . $area->id . "' />";?>
                </td>


				<!-- ticket type -->
				<td width="20%">
					<?php echo $ticketType->description;?>
				</td>
				<!-- num -->
				<td width="10%" style="text-align:right">
					<?php echo $order->http_ticket_type_qty;?>
				</td>
				<!-- unit price -->
				<td width="15%" style="text-align:right">
					<?php echo ($order->http_ticket_type_price + $event->booking_fee_per_ticket);?>
				</td>
				<!-- line total -->
				<td width="15%" id="line_<?php echo $lc++;?>" style="text-align:right">
					<?php echo $order->http_ticket_type_total;?>
				</td>
				<!-- Delete -->
				<td width="10%" style="text-align:center">
					<a style="margin-left:15px; color:#000000; font-size:20px; text-decoration:none;" onClick="ajaxDeleteCall('<?echo $linePrefix;?>'); return false;" href="#"><b>X</b></a>
				</td>
			</tr>
			</table>
<?php
		}
	endforeach;		// foreach ($orders as $order):

	// Were there no order items?
	if ($fst)
	{
		echo '<div class="row">
			<div class="span7 well" style="margin-left:0px; padding-bottom:0px">
				<center>You have no tickets on order.<br/>&nbsp</center>
			</div>
		</div>';
	}

echo "<script>lc=$lc;</script>";
?>

	<!-- grand total -->
	<table>
		<tr>
		</tr>
			<br/>
		<tr>
			<td width="65%">
			</td>
			<td width="15%" style="text-align:right;">
				<b>Total Payable</b>
			</td>
			<td width="10%" style="text-align:right;">
				<?php echo "<b><div id='total'></div></b>";?>
				<?php echo "<input type='hidden' id='rtotal' name='rtotal' value='0' />";?>
			</td>
			<td width="10%">
			</td>
		</tr>
	</table>

	<script>
	function ajaxDeleteCall(id)
	{
		//alert(id);
		res = id.split('_');
		event = res[1];
		area = res[3];
		ttype = res[5];
		jQuery.ajax({'url':'//plugin.wireflydesign.com/ticket2/index.php/ticket/ajaxDeleteTicket','data':{'id':id, 'event':event, 'area':area, 'ttype':ttype},'type':'POST','dataType':'json','success':function(val){ajaxDeleteReturn(val);},'cache':false});
	}

	var ajaxDeleteReturn= function(val) {
//alert('back');
		var row = document.getElementById(val.id);
   		row.parentNode.removeChild(row);	
		calcValues();
	}
	</script>
<?php

function genHeading($event)
{
    if (strlen($event->ticket_logo_path) > 0)
        $logo = Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $event->ticket_logo_path;
    else
        $logo = Yii::app()->baseUrl . '/img/default_logo.jpg';

?>
    <div class="row" style="margin:0px";>
        <div class="span7 well" style="padding:0px; margin-bottom:5px; margin-top:20px; padding-bottom:0px">
            <table style="margin-bottom:0px">
                <tr>
                    <td width="25%" >
<?php Yii::log("TICKET FORM : image " . Yii::app()->session['uid'], CLogger::LEVEL_WARNING, 'system.test.kim'); ?>
                        <?php
                        if (file_exists(Yii::app()->basePath . "/../../" . $logo))
                        {
                            $imgdim = getimagesize(Yii::app()->basePath . "/../../" . $logo);
                            $imgw=$imgdim[0];
                            $imgh=$imgdim[1];
                            $imgstr="width:40px";
                            if ($imgh>120)
                                $imgstr="height:40px";
                            echo CHtml::image(
                                $logo,
                                'Event Image',
                                array('style'=>$imgstr));
                        }
                        ?>
                    </td>
                    <td width="1%"></td>
                    <td width="74%">
                        <b><?php echo $event->title; ?></b>
                        <br>
                        <i><?php echo $event->date; ?></i>
                        <div style="padding:0; margin:0; color:#696d6e; font-size:95%">
                            <?php echo nl2br($event->banner_text); ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
<?php
}

?>







<div class="span7 well" style="margin-left:0px;padding-bottom:0px">
	<table>
		<tr class="row">
<?php if (($this->isFreeEvent) || ($this->isBackend)): ?>
			<td style="text-align:right">
				Name
			</td>
			<td>
				<input id="free_name" name="free_name" value="" class="" MaxLength="255" style="width:240px"/>
			</td>
<?php endif; ?>
			<td style="text-align:right">
				Email Address
			</td>
			<td>
				<input id="email1" name="email1" value="" class="" MaxLength="50" />
			</td>
		</tr>
		<tr class="row">
<?php if (($this->isFreeEvent) || ($this->isBackend)): ?>
			<td style="text-align:right">
				Address
			</td>
			<td>
				<input id="free_address1" name="free_address1" value="" class="" MaxLength="255" style="width:240px"/>
			</td>
<?php endif; ?>
			<td style="text-align:right">
				Again
			</td>
			<td>
				<input id="email2" name="email2" value="" class="" MaxLength="50" />
			</td>
		</tr>
		<tr class="row">
<?php if (($this->isFreeEvent) || ($this->isBackend)): ?>
			<td style="text-align:right">
			</td>
			<td>
				<input id="free_address2" name="free_address2" value="" class="" MaxLength="255" style="width:240px"/>
			</td>
<?php endif; ?>
			<td style="text-align:right">
				Telephone
			</td>
			<td>
				<input id="telephone" name="telephone" value="" class="" MaxLength="50" />
			</td>
		</tr>
<?php if (($this->isFreeEvent) || ($this->isBackend)): ?>
		<tr class="row">
			<td style="text-align:right">
			</td>
			<td>
				<input id="free_address3" name="free_address3" value="" class="" MaxLength="255" style="width:240px"/>
			</td>
			<td style="text-align:right">
			</td>
			<td>
			</td>
		</tr>
		<tr class="row">
			<td style="text-align:right">
			</td>
			<td>
				<input id="free_address4" name="free_address4" value="" class="" MaxLength="255" style="width:240px"/>
			</td>
			<td style="text-align:right">
			</td>
			<td>
			</td>
		</tr>
		<tr class="row">
			<td style="text-align:right">
				Postcode
			</td>
			<td>
				<input id="free_post_code" name="free_post_code" value="" class="" MaxLength="90" style="width:80px"/>
			</td>
			<td style="text-align:right">
			</td>
			<td>
			</td>
		</tr>
<?php endif; ?>
	</table>
</div>

<?php if ($this->isFreeEvent):?>
<input type="hidden" id="is_free_event" name="is_free_event" value="" class="" MaxLength="90"/>
<?php endif;?>
<?php if ($this->isBackend):?>
<input type="hidden" id="is_backend" name="is_backend" value="" class="" MaxLength="90"/>
<?php endif;?>

<div class="row">

	<div style="Xfloat:left">
	By using this service you agree to our <a href="https://secure.dglink.co.uk/cboxoffice/tandc.html" target="_blank">Terms & Conditions</a> as well as the conditions displayed below
	</div>

	<div style="Xfloat:right">
	<?php

	$free1 = ''; $free2 = ''; $free3 = ''; $notBackend = '';
	if ($this->isFreeEvent)
	{
		$free1 = ' if (document.getElementById("free_name").value == "") err += "Invalid name\n"; ';
		$free2 = ' if (document.getElementById("free_address1").value == "") err += "Invalid address\n"; ';
		$free3 = ' if (document.getElementById("free_post_code").value == "") err += "Invalid postcode\n"; ';
	}
	if (!($this->isBackend))
	{
		$notBackend = '
					var email1 = document.getElementById("email1").value;
					var email2 = document.getElementById("email2").value;
					if ((email1 != email2) || (!email1) || (email1.indexOf(".") == -1) || (email1.indexOf("@") == -1))
					err += "Invalid email\n";
					';
	}

	$caption = "Continue";
	if ($this->isBackend)
		$caption = "Buy";
	if ($this->isFreeEvent)
		$caption = "Reserve";

	?>
	</div>





<div class="row">

<style>
	#continue-shopping {
		background: url(//plugin.wireflydesign.com/ticket/img/con-shop.png);
		border: 0;
		display: block;
		height: 50px;
		width: 125px;
		outline:none;
	}
	#other-events {
		background: url(//plugin.wireflydesign.com/ticket/img/other.png);
		border: 0;
		display: block;
		height: 50px;
		width: 125px;
		outline:none;
	}
	input[type=submit] {
		background: url(//plugin.wireflydesign.com/ticket/img/pay-now.png);
		border: 0;
		display: block;
		height: 50px;
		width: 125px;
		outline:none;
	}
</style>

	<?php
	// Continue shopping
	echo "<div style='float:left'>";
	echo "<input type='button' id='continue-shopping' onclick='closeFunction()'></input>";
	echo "</div>";
	echo "<script>function closeFunction(){window.close()}</script>";

	// Other events
	echo "<div style='float:left'>";
	echo "<input type='button' id='other-events' onclick='todglinkFunction()'></input>";
	echo "</div>";
	echo "<script>function todglinkFunction(){ window.href('http://dglink.co.uk/dumfries-galloway-whats-on-events.html')  }</script>";

	echo "<div style='float:right'>";
	$this->widget('zii.widgets.jui.CJuiButton', array(
		'name'=>'submit',
		'htmlOptions' => array('class'=>'ui-button-success'),
		'onclick'=>new CJavaScriptExpression(
			'function(){


				var err = "";
				var email1 = document.getElementById("email1").value;
				var email2 = document.getElementById("email2").value;
				if ((email1 != email2) || (!email1) || (email1.indexOf(".") == -1) || (email1.indexOf("@") == -1))
					err += "Invalid email\n";
				if (err != "")
				{
					alert("Invalid email");
					return(false);
				}


			}'
		),
	));

	echo "</div>";
	?>

</div> <!-- row -->


<!-- <a href=<?php echo Yii::app()->request->baseUrl . '/';?>00002.pdf>PDF HERE </a> -->


<!-- @@NB iframe resizer hardcode here -->
<script type="text/javascript" src="/js/iframeResizer.contentWindow.min.js"></script>
    <!-- Iframe resizer -->
    <script type="text/javascript" src="/js/jquery.iframeResizer.min.js"></script>
    <script type="text/javascript">
        jQuery('iframe').iFrameSizer({
            log                    : true,  // For development
            autoResize             : true,  // Trigering resize on events in iFrame
            contentWindowBodyMargin: 8,     // Set the default browser body margin style (in px)
            doHeight               : true,  // Calculates dynamic height
            doWidth                : false, // Calculates dynamic width
            enablePublicMethods    : true,  // Enable methods within iframe hosted page
            interval               : 0,     // interval in ms to recalculate body height, 0 to disable refreshing
            scrolling              : false, // Enable the scrollbars in the iFrame
            callback               : function(messageData){ // Callback fn when message is received
                $('p#callback').html(
                    '<b>Frame ID:</b> '    + messageData.iframe.id +
                    ' <b>Height:</b> '     + messageData.height +
                    ' <b>Width:</b> '      + messageData.width +
                    ' <b>Event type:</b> ' + messageData.type
                );
            }
        });
</script>


	<?php $this->endWidget(); ?>
</div><!-- form -->
