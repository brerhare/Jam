<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		if ($this->createPrograms == 0)
		{
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
		}
		else
		{
			echo '<style> .programcb { color:#0066A4; font: bold 12px Arial; } </style>';
			echo '<div class="portlet-decoration">';
			echo '  <div class="portlet-title">Choose applicable event listings</div>';
			echo '</div>';
			echo '<div class="portlet-content">';
			echo '  <ul class="operations" id="someid">';
//echo '    <li><a href="/event/backend.php/event/admin">DG Link</a></li>';

	        $criteria = new CDbCriteria;
        	$criteria->addCondition("id = 13");	// Only DG Link
        	$program = Program::model()->find($criteria);
			if ($program)
				echo '<li style="color:#969a9b" class="programcb"><input type="checkbox" checked="checked" disabled="disabled" name="programcb" value="' . $program->id . '"> ' . $program->name . '</li>';

			// Get the default program to tick
			$member=Member::model()->findByPk(Yii::app()->session['eid']);
			if (!($member))
				throw new CHttpException(400, 'The request cannot be fulfilled. Please logout and login again');

	        $criteria = new CDbCriteria;
        	$criteria->addCondition("id != 13");	// Everything but DG Link
        	$criteria->order = 'name ASC';
        	$programs = Program::model()->findAll($criteria);
			foreach ($programs as $program)
			{
				$disabled = "";
				$disabledStyle = "";
				$checked = "";
                $criteria = new CDbCriteria;
                $criteria->addCondition("event_program_id = " . $program->id);
                $criteria->addCondition("event_member_id = " . Yii::app()->session['eid']);
                $memberHasProgram = MemberHasProgram::model()->find($criteria);
				if ($program->private)
				{
					if (($memberHasProgram) && ($memberHasProgram->privilege_level == 2))
						$checked = " checked='checked' ";
					else
					{
						$disabled = " disabled='disabled' ";
						$disabledStyle = " style='color:#969a9b' ";
					}
				}
				else
				{
                    if (($memberHasProgram) && ($memberHasProgram->privilege_level == 2))
						$checked = " checked='checked' ";
				}
				if ($program->id == $member->lock_program_id)
					$checked = " checked='checked' ";

				echo '<li ' . $disabledStyle . ' class="programcb"><input type="checkbox" ' . $checked . $disabled . ' name="programcb" value="' . $program->id . '"> ' . $program->name . '</li>';
			}
			echo '<li style="padding-top:10px">';
		    	$this->widget('bootstrap.widgets.TbButton', array(
		        	'buttonType'=>'submit',
        			'type'=>'primary',
        			'label'=>'Create Event',
        			'htmlOptions'=>array(
						'style'=>'width:100%',
            			'onClick'=>'js:return programButtonClick()',
        			)
    			));
			echo '</li>';

			echo '  </ul></div>';
			echo '</div>';
		}
	?>
	</div><!-- sidebar -->
</div>

<script>
function programButtonClick()
{
	var cb = [];
	var eleNodelist = document.getElementsByTagName("input");
	for (i = 0; i < eleNodelist.length; i++) {
		if ((eleNodelist[i].type == 'checkbox') && (eleNodelist[i].checked == true)) {
			cb.push(eleNodelist[i].value);
		}
	}
	// Set the cookie
	cookieStr = "";
	for (i = 0; i < cb.length; i++) {
		if (i > 0)
			cookieStr += '|';
		cookieStr += cb[i];
	}
	validDays = 3;
	var date = new Date();
	date.setTime(date.getTime()+(validDays*24*60*60*1000));
	var expires = "; expires="+date.toGMTString();
    document.cookie = "createEventPrograms" + "=" + cookieStr + expires +"; path=/";
	url="/event/backend.php/event/create";
	window.location.href = url;
}
</script>


<?php $this->endContent(); ?>
