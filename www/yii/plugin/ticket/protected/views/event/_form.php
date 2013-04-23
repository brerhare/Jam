<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
		'id'=>'room-form',
		'enableAjaxValidation'=>false,
		'type'=>'horizontal',
	)); ?>

	<div class="row">
		<div class="span5 well">
			<table>
				<tr>
					<td width="75%">
						<b><?php echo $model->title; ?></b>
						<br>
						<i><?php echo $model->date; ?></i>
					</td>
					<td width="25%">
<?php Yii::log("TICKET FORM : image " . Yii::app()->session['uid'], CLogger::LEVEL_WARNING, 'system.test.kim'); ?>
						<?php echo CHtml::image(Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $model->ticket_logo_path,
							'My Image Name',
							array('style'=>'height:80px;'));
						?>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<?php $this->widget('bootstrap.widgets.TbTabs',array(
		'type'=>'tabs',
		'tabs' => array(
			array('label'=>'Choose Tickets', 'content' => $this->renderPartial('_form_choose_tickets', array('form' => $form, 'model' => $model), true), 'active'=>true),
			array('label'=>'Payment', 'content' => $this->renderPartial('_form_make_payment', array('form' => $form, 'model' => $model),  true)),
			array('label'=>'Confirmation', 'content' => $this->renderPartial('_form_make_payment', array('form' => $form, 'model' => $model),  true)),
		),
	));
	?>

	<?php $this->endWidget(); ?>
</div><!-- form -->
