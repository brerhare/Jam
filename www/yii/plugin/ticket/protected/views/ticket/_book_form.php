<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'ticket-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

<?php
// Get logo, or default
if (strlen($model->ticket_logo_path) > 0)
	$logo = Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $model->ticket_logo_path;
else
	$logo = Yii::app()->baseUrl . '/img/default_logo.jpg';
	
// Set global flags
$isFreeEvent = 1;
(isset($_GET['ref']) && ($_GET['ref'] == 'bktji5308')) ? Yii::app()->session['isBackend'] = 1 : Yii::app()->session['isBackend'] = 0;
?>

<style>
#page {
	border:0;
}
/* @@NB: This overrides the some bootstrap settings */
.span7 {width:94%}
.span-19 {margin-left:1px}
</style>

	<div class="row">
		<div class="span7 well" style="margin-left:0px; padding-bottom:0px">
			<table>
				<tr>
					<td width="25%" >
<?php Yii::log("TICKET FORM : image " . Yii::app()->session['uid'], CLogger::LEVEL_WARNING, 'system.test.kim'); ?>
						<?php
						if (file_exists(Yii::app()->basePath . "/../../" . $logo))
						{
							$imgdim = getimagesize(Yii::app()->basePath . "/../../" . $logo);
							$imgw=$imgdim[0];
							$imgh=$imgdim[1];
							$imgstr="width:120px";
							if ($imgh>120)
								$imgstr="height:120px";
							echo CHtml::image(
								$logo,
								'Event Image',
								array('style'=>$imgstr));
						}
						?>
					</td>
					<td width="1%"></td>
					<td width="74%">
						<b><?php echo $model->title; ?></b>
						<br>
						<i><?php echo $model->date; ?></i>
						<div style="padding:0; margin:0; color:#696d6e; font-size:95%">
							<?php echo nl2br($model->banner_text); ?>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>

<!--- TICKET DIV ------------------------------------------------------------------------------------------------- -->

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
var numTickets = 0;

function calcValues()
{
    var total = 0;
    numTickets = 0;
    for (var x = 0; x < lc; x++)
    {
        var linePrefix = 'line_' + x + '_';
        price = parseFloat(document.getElementById(linePrefix + 'price').textContent);
        var e = document.getElementById(linePrefix + 'select');
		if (e)
        	var num = parseInt(e.options[e.selectedIndex].value);
		else
			var num = 0;
        numTickets += num;

        lineTotal = price * num;
        document.getElementById(linePrefix + 'total').textContent = lineTotal.toFixed(2);
        document.getElementById('p'+linePrefix + 'total').value = lineTotal.toFixed(2);

        total += lineTotal;
    }
    document.getElementById('total').textContent = '£ ' + total.toFixed(2);
    document.getElementById('ptotal').value = total.toFixed(2);
    document.getElementById('ttotal').value = numTickets;
}

$(document).ready(function() {
    calcValues();
 });
 
</script>

<div class="row" style="padding-right:5px">
	<table>	
			<tr style="color:#000000;">
				<td colspan="2">
				</td>
				<td>
				<b>Qty</b>
				</td>
				<td style="text-align:right">
				<b>Price</b>
				</td>
				<td style="text-align:right">
				<b>Line Total</b>
				</td>
			</tr>
		<?php
		$lc = 0;
		$criteria = new CDbCriteria;
		$criteria->addCondition("ticket_event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$areas = Area::model()->findAll($criteria);
		foreach ($areas as $area):
			$criteria = new CDbCriteria;
			$criteria->addCondition("ticket_area_id = " . $area->id);
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$ticketTypes = TicketType::model()->findAll($criteria);
			foreach ($ticketTypes as $ticketType):
				$linePrefix = "line_" . $lc++ . "_";
			?>
			<tr>
				<!-- area -->
				<td width="45%">
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
				<td width="10%">
					<?php
					$select=0;
					$arr = array();
					$arrNum = $ticketType->max_tickets_per_order;
					if ($arrNum == 0)
						$arrNum = 9;
					$multiplier = 1;
					$avail_places = ($area->max_places - $area->used_places);
					if ($ticketType->places_per_ticket > 0)
						$multiplier = $ticketType->places_per_ticket;
					for ($x = 0; $x <= $arrNum; $x++)
					{
						if (($x * $multiplier) > $avail_places)
							break;
						array_push($arr, $x);
					}
					?>
					<?php
						if (count($arr) > 0)
							echo CHtml::dropDownList($linePrefix . 'select', $select, $arr, array('style'=>'width:55px', 'onchange'=>'calcValues()'));
						else
							echo "<span style='color:red'>Sold out</span>";
					?>
				</td>
				<!-- unit price -->
				<td width="10%" style="text-align:right">
					<?php echo "<div id='" . $linePrefix . "price'>" . ($ticketType->price + $model->booking_fee_per_ticket). "</div>";?>
					<?php echo "<input type='hidden' id='p" . $linePrefix . "price' name='p" . $linePrefix . "price' value='" . $ticketType->price. "' />";?>
				</td>
				<!-- line total -->
				<td width="15%"  style="text-align:right">
					<?php echo "<div id='" . $linePrefix . "total'></div>";?>
					<?php echo "<input type='hidden' id='p" . $linePrefix . "total' name='p" . $linePrefix . "total' value='0' />";?>
				</td>
			</tr>

<!--
<tr style="padding:0px">
<td style="padding:0px"colspan="5">
<hr/>
</td>
</tr>
-->

			<?php endforeach;?>
		<?php endforeach;
		echo "<script>lc=$lc;</script>";
		?>
		<!-- grand total -->
		<tr>
			<td colspan="2">
			</td>
			<td colspan="2" style="text-align:right;">
				<h5>Total Payable</h5>
			</td>
			<td style="text-align:right;">
				<h5>
				<?php echo "<div id='total'></div>";?>
				<?php echo "<input type='hidden' id='ptotal' name='ptotal' value='0' />";?>
				<?php echo "<input type='hidden' id='ttotal' name='ttotal' value='0' />";?>
				</h5>
			</td>
		</tr>
	</table>
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
		background: url(//plugin.wireflydesign.com/ticket/img/res-tickets.png);
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
	echo "<script>function todglinkFunction(){ window.location.href = 'http://dglink.co.uk'  }</script>";

	echo "<div style='float:right'>";
	$this->widget('zii.widgets.jui.CJuiButton', array(
		'name'=>'submit',
		'htmlOptions' => array('class'=>'ui-button-success'),
		'onclick'=>new CJavaScriptExpression(
			'function(){
				var err = "";
				if (numTickets < 1)
					err += "No tickets selected\n";
				if (err != "")
				{
					alert(err);
					return false;
				}
			}'
		),
	));

	echo "</div>";
	?>

</div> <!-- row -->

<div class="row">

	<div><center>
	<br/>
	By using this service you agree to our <a href="https://plugin.wireflydesign.com/ticket/tandc.html" target="_blank">Terms & Conditions</a> as well as any conditions displayed below
	</center></div>

</div>


<div>
<br>
<?php echo $model->ticket_terms; ?>
</div>
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
