<?php

$this->menu=array(
	array('label'=>'Back to Calendar','url'=>array('site/calendar/?sid='.Yii::app()->session['sid'])),
	array('label'=>'Mark as Deposit taken','visible' => !Yii::app()->user->isGuest,'url'=>'#','htmlOptions'=>array('style'=>'color: cc433c'),'linkOptions'=>array('submit'=>array('deposit','id'=>$model->id),'confirm'=>'Deposit taken?')),
	array('label'=>'------------------------------------',),
	array('label'=>'Cancel this booking','visible' => !Yii::app()->user->isGuest,'url'=>'#','htmlOptions'=>array('style'=>'color: cc433c'),'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'THIS WILL CANCEL THE BOOKING! Are you sure you want proceed?')),
);
?>

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
	echo ' </b>£' . sprintf("%.2f", $model->reservation_total  * $param->deposit_percent / 100) . ' paid on booking, <b>£' . sprintf("%.2f", $model->reservation_total  * (100 - $param->deposit_percent) / 100) . ' due on arrival</b>';
}

if ($model->deposit_taken_flag == true)
	echo "<br><br>Deposit <b>has</b> been taken<br>";
else
	echo "<br><br>Deposit has <b>not</b> been taken<br>";
?>

