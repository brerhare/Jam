<?php

$form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
    'id'=>'extra-form',
    'enableAjaxValidation'=>false,
    'type'=>'horizontal',
)); ?>

<?php $this->menu=array(
	array('label'=>'Back to Calendar','url'=>array('site/calendar/?sid='.Yii::app()->session['sid'])),
	array('label'=>'Mark as Deposit taken','visible' => !Yii::app()->user->isGuest,'url'=>'#','htmlOptions'=>array('style'=>'color: cc433c'),'linkOptions'=>array('submit'=>array('deposit','id'=>$model->id),'confirm'=>'Deposit taken?')),
);
?>

<style>
div.portlet-content {
	margin: 0 0 0 0;
}
#page {
	border: 0;
}
#footer {
	margin-top:450px;
}
#undermenu {
	padding:15px 0;
	background-color:#effdff;
	width:170px;
	margin-left:770px;
	margin-top:-450px;
}
#cancelreason {
	margin: 0 15px 10px;
	padding: 0 0 0 5px;
	width:135px;
}
</style>

<h2>View Booking for ref <?php echo $ref; ?></h2>

<?php
$basiccols=array(
		'name',
		'ref',
		'address_1',
		'address_2',
		'town',
		'county',
		'post_code',
		'telephone',
		'email',
		'card_name',
	);
$cardcols=array(
		'name',
		'ref',
		'address_1',
		'address_2',
		'town',
		'county',
		'post_code',
		'telephone',
		'email',
		'card_name',
		'card_number',
		'card_expiry_mm',
		'card_expiry_yy',
		'card_cvv',
	);

	if (Yii::app()->user->isGuest)
		$cols = $basiccols;
	else
		$cols = $cardcols;

	$this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>
		$cols
	));

echo 'Reservation total : ' . $model->reservation_total . '<br>';

$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$param=Param::model()->find($criteria);
if (($param) && ($param->deposit_percent > 0))
{
	if ($model->deposit_taken > 0)
	{
		echo ' </b>£' . sprintf("%.2f", $model->deposit_taken) . ' deposit was paid on booking, <b>£' . sprintf("%.2f", $model->reservation_total  - $model->deposit_taken) . ' due on arrival</b>';
	}
	else
	{
		echo ' </b>£' . sprintf("%.2f", $model->reservation_total  * $param->deposit_percent / 100) . ' deposit was <b>not</b> paid on booking. <b>Full amount</b> due on arrival<br>';
	}
}

?>

<hr>

<?php	// Show the reservation details (rooms, extras)
echo "<b>Reservation details</b><br>";
$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$criteria->addCondition("ref = '" . $model->ref . "'");
$reservationRooms=ReservationRoom::model()->findAll($criteria);
foreach ($reservationRooms as $reservationRoom)
{
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$criteria->addCondition("id = " . $reservationRoom->room_id);
	$room=Room::model()->find($criteria);
	echo $room->title . " - " . $reservationRoom->num_adult . " adults, " . $reservationRoom->num_child . " children. ";
	echo $reservationRoom->occupancy_type_description . "<br>";
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid = " . Yii::app()->session['uid']);
	$criteria->addCondition("ref = '" . $model->ref . "'");
	$reservationExtras=ReservationExtra::model()->findAll($criteria);
	$hasExtras = false;
	foreach ($reservationExtras as $reservationExtra)
	{
		echo "Extra - " . $reservationExtra->extra_description . "<br>";
		$hasExtras = true;
	}
	if ($hasExtras)
		echo "<br>";
}
?>

<div id="undermenu">
	<input type="hidden" id="id" name="id" value="<?php echo $model->id;?>"/>
	<input type="text" name="cancelreason" id="cancelreason" value="Reason">
	<div style="margin-left:25px">
    <?php $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=>'submit',
	    'type'=>'primary',
    	'label'=>'Cancel booking',
    	'htmlOptions'=>array(
			'onClick'=>'js:return cancelButtonClick()',
		)
    )); ?>
    </div>
</div>

<script>
function cancelButtonClick()
{
	return confirm('Do you really want to DELETE this booking?');
}
</script>

<?php $this->endWidget(); ?>
