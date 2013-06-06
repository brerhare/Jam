<?php

$this->menu=array(
	array('label'=>'Back to Calendar','url'=>array('index')),
	//array('label'=>'Back to Calendar','url'=>array('index')),
	//array('label'=>'Delete Customer','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this booking?')),
);
?>

<h2>View Booking for ref <?php echo $ref; ?></h2>

<?php
$basiccols=array(
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

echo '<br>';
echo 'Reservation total : ' . $model->reservation_total . '<br>';

$criteria = new CDbCriteria;
$criteria->addCondition("uid = " . Yii::app()->session['uid']);
$param=Param::model()->find($criteria);
if (($param) && ($param->deposit_percent > 0))
{
	echo ' </b>£' . sprintf("%.2f", $model->reservation_total  * $param->deposit_percent / 100) . ' paid on booking, <b>£' . sprintf("%.2f", $model->reservation_total  * (100 - $param->deposit_percent) / 100) . ' due on arrival</b>';
}




?>
