<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'program-form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	'type'=>'horizontal',
)); ?>

	<p class="help-block">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>255)); ?>

	<?php //echo $form->textFieldRow($model,'thumb_path',array('class'=>'span5','maxlength'=>255)); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model,'thumb_path'); ?>
        <?php echo CHtml::activeFileField($model,'thumb_path',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'thumb_path'); ?>
    </div>

	<?php //echo $form->textFieldRow($model,'icon_path',array('class'=>'span5','maxlength'=>255)); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model,'icon_path'); ?>
        <?php echo CHtml::activeFileField($model,'icon_path',array('size'=>60,'maxlength'=>255)); ?>
        <?php echo $form->error($model,'icon_path'); ?>
    </div>

	<?php //echo $form->textFieldRow($model,'event_program_fields_id',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>

	<div class = "row">
		<div class = "well">
			<h3>Member permissions for <?php echo $model->name;?></h3>
			<table>
			<thead><tr><th style='width:30%'>Username</th>
			<th style='width:14%'>Admin</th>
			<th style='width:14%'>Moderator</th>
			<th style='width:14%'>Trusted</th>
			<th style='width:14'>Member</th>
			<th style='width:14%'>None</th>
			</thead>
			<tbody>
			<?php
			$members = Member::model()->findAll();
			foreach($members as $member)
			{
// @@TODO: These privilege levels should be constants from the MemberHasProgram model
				// Try to pick up an event privilege record for this member
				$criteria = new CDbCriteria;
				$criteria->addCondition("event_member_id = " . $member->id);
				$criteria->addCondition("event_program_id = " . $model->id);
				$memberHasProgram = MemberHasProgram::model()->find($criteria);
				if ($memberHasProgram)
					$val = $memberHasProgram->privilege_level;
				else
					$val = 0;
				echo "<input type='hidden' name='od_" . $member->id . "' value='" . $val . "'>";
				echo "<tr>";
				echo "<td>" . $member->user_name . "</td>";
				$lev = 4;
				for ($i = 0; $i < 5; $i++)
				{
					echo "<td>";
					$str = "<input type='radio' name='id_" . $member->id . "' value='" . $lev . "'";
					if ($lev == $val)
						$str .= " checked='checked'";
					$str .= ">"; 
					echo $str;
					echo "</td>";
					$lev--;
				}
				echo "<tr>";
			}
			?>
			</tbody>
			</table>
		</div>
	</div>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Create' : 'Save',
		)); ?>
	</div>
	

<?php $this->endWidget(); ?>

