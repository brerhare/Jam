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
(isset($_GET['ref']) && ($_GET['ref'] == 'bktji5308')) ? $isBackend = 1 : $isBackend = 0;
?>

<style>
#page {
	border:0;
}
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
        var num = parseInt(e.options[e.selectedIndex].value);
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
		<?php
		$lc = 0;
		$criteria = new CDbCriteria;
		$criteria->addCondition("ticket_event_id = " . $model->id);
		$criteria->addCondition("uid = " . Yii::app()->session['uid']);
		$areas = Area::model()->findAll($criteria);
		foreach ($areas as $area):
		?>
			<tr style="background-color:#745882; color:#ffffff">
				<td colspan="2">
					<h5><?php echo $area->description;?></h5>
				</td>
				<td>
				Tickets
				</td>
				<td style="text-align:right">
				Price Each
				</td>
				<td style="text-align:right">
				Line Total
				</td>
			</tr>
			<?php
			$criteria = new CDbCriteria;
			$criteria->addCondition("ticket_area_id = " . $area->id);
			$criteria->addCondition("uid = " . Yii::app()->session['uid']);
			$ticketTypes = TicketType::model()->findAll($criteria);
			foreach ($ticketTypes as $ticketType):
				$linePrefix = "line_" . $lc++ . "_";
			?>
			<tr style="background-color:#EDE4F2">
				<!-- blank -->
				<td width="10%">
				<?php $tmp = $linePrefix . "id_" . $ticketType->id; ?>
				<?php echo "<div id='" . $tmp . "'><div>";?>
				<?php echo "<input type='hidden' [id='p" . $linePrefix . "id' name='p" . $linePrefix . "id' ] value='" . $ticketType->id . "' />";?>
				<?php echo "<input type='hidden' [id='p" . $linePrefix . "area' name='p" . $linePrefix . "area' ] value='" . $area->id . "' />";?>
				</td>
				<!-- ticket type -->
				<td width="40%">
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
					<?php echo CHtml::dropDownList($linePrefix . 'select', $select, $arr, array('style'=>'width:55px', 'onchange'=>'calcValues()'));?>
				</td>
				<!-- unit price -->
				<td width="20%" style="text-align:right">
					<?php echo "<div id='" . $linePrefix . "price'>" . $ticketType->price . "</div>";?>
					<?php if ($ticketType->price != 0) $isFreeEvent = 0;?>
					<?php echo "<input type='hidden' id='p" . $linePrefix . "price' name='p" . $linePrefix . "price' value='" . $ticketType->price. "' />";?>
				</td>
				<!-- line total -->
				<td width="20%"  style="text-align:right">
					<?php echo "<div id='" . $linePrefix . "total'></div>";?>
					<?php echo "<input type='hidden' id='p" . $linePrefix . "total' name='p" . $linePrefix . "total' value='0' />";?>
				</td>
			</tr>
			<?php endforeach;?>
		<?php endforeach;
		echo "<script>lc=$lc;</script>";
		?>
		<!-- grand total -->
		<tr>
			<td colspan="3">
			</td>
			<td  style="text-align:right; background-color:#745882; color:#ffffff">
				<h5>Total Payable</h5>
			</td>
			<td style="text-align:right; background-color:#745882; color:#ffffff">
				<?php echo "<div id='total'></div>";?>
				<?php echo "<input type='hidden' id='ptotal' name='ptotal' value='0' />";?>
				<?php echo "<input type='hidden' id='ttotal' name='ttotal' value='0' />";?>
			</td>
		</tr>
	</table>
</div>

<div class="span7 well" style="margin-left:0px;padding-bottom:0px">
	<table>
		<tr class="row">
<?php if (($isFreeEvent) || ($isBackend)): ?>
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
<?php if (($isFreeEvent) || ($isBackend)): ?>
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
<?php if (($isFreeEvent) || ($isBackend)): ?>
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
<?php if (($isFreeEvent) || ($isBackend)): ?>
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

<?php if ($isFreeEvent):?>
<input type="hidden" id="is_free_event" name="is_free_event" value="" class="" MaxLength="90"/>
<?php endif;?>
<?php if ($isBackend):?>
<input type="hidden" id="is_backend" name="is_backend" value="" class="" MaxLength="90"/>
<?php endif;?>

<div class="row">

	<div style="float:left">
	By using this service you agree to our <a href="https://secure.dglink.co.uk/cboxoffice/tandc.html" target="_blank">Terms & Conditions</a> as well as the conditions displayed below
	</div>

	<div style="float:right">
	<?php

	$free1 = ''; $free2 = ''; $free3 = ''; $notBackend = '';
	if ($isFreeEvent)
	{
		$free1 = ' if (document.getElementById("free_name").value == "") err += "Invalid name\n"; ';
		$free2 = ' if (document.getElementById("free_address1").value == "") err += "Invalid address\n"; ';
		$free3 = ' if (document.getElementById("free_post_code").value == "") err += "Invalid postcode\n"; ';
	}
	if (!($isBackend))
	{
		$notBackend = '
					var email1 = document.getElementById("email1").value;
					var email2 = document.getElementById("email2").value;
					if ((email1 != email2) || (!email1) || (email1.indexOf(".") == -1) || (email1.indexOf("@") == -1))
					err += "Invalid email\n";
					';
	}

	$caption = "Continue";
	if ($isBackend)
		$caption = "Buy";
	if ($isFreeEvent)
		$caption = "Reserve";
	$this->widget('zii.widgets.jui.CJuiButton', array(
		'name'=>'submit',
		'caption'=>$caption,
		'htmlOptions' => array('class'=>'ui-button-success'),
		'onclick'=>new CJavaScriptExpression(
			'function(){
	
				var err = "";
				if (numTickets < 1)
					err += "No tickets selected\n";
	
	' . $free1 . $free2 . $free3 . $notBackend . '
	
				if (err != "")
				{
					alert(err);
					return false;
				}
			}'
		),
	));
	?>
	</div>

</div> <!-- row -->

<div>
<br>
<?php echo $model->ticket_terms; ?>
</div>
<!-- <a href=<?php echo Yii::app()->request->baseUrl . '/';?>00002.pdf>PDF HERE </a> -->


<!--
	<div class="row">
		<input id="email2" value="" class="" MaxLength="50" />
	</div>
	<div class="row">
		<input name="phone" value="" class="InputTextField" MaxLength="15" style="width:100px"/>
	</div>
-->

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
