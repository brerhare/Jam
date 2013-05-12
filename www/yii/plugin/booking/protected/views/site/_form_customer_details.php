<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($baseUrl.'/js/jquery-ui.min.js');
?>

<?php // Control variables
$maxRooms = 3; // How many rooms can be booked
$showDays = 14; // How many days to show on calendar grid
//-------------------
echo "<script>var maxRooms=" . $maxRooms . ";</script>";
echo "<script>var showDays=" . $showDays . ";</script>";
//-------------------
?>







<!-------------------------------------------------------------------->
<?php
echo "<p>";
				foreach (Yii::app()->session as $field => $value)
				{
					echo $field . ":" . $value . "<br>";
					//Yii::app()->session[$field] = $value;
					//Yii::log("GIVING INDEX2 VALUES FOR " . Yii::app()->session[$field] . " = " . $value , CLogger::LEVEL_WARNING, 'system.test.kim');
				}
?>
<!-------------------------------------------------------------------->


<?php $this->endWidget(); ?>
</div><!-- form -->