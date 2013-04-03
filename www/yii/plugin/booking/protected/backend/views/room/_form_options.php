
<!--
	<p class="help-block">Fields with <span class="required">*</span> are required.</p>
-->

	<?php echo $form->errorSummary($model); ?>
<!--
	< ?php $box = $this->beginWidget('bootstrap.widgets.TbBox', array(
		'title' => 'Advanced Box',
		//'headerIcon' => 'icon-th-list',

		// when displaying a table, if we include bootstra-widget-table class
		// the table will be 0-padding to the box
		'htmlOptions' => array('class'=>'bootstrap-widget-table')
	));? >
-->

<!-- -->
	 <?php echo $form->checkBoxListRow($model, 'uid', array(
	'Option one is this and that—be sure to include why it\'s great',
	'Option two can also be checked and included in form results',
	'Option three can—yes, you guessed it—also be checked and included in form results',
), array('hint'=>'<strong>Note:</strong> Labels surround all the options for much larger click areas.')); ?>
<!-- -->


<!--
<div class="control-group ">
  <label class="control-label" for="TestForm_checkboxes">Checkboxes</label>
  <div class="controls">
< ?php echo CHtml::label('Your Interests:', 'interests'); ? >
< ?php echo CHtml::checkBoxList('interests', '',
	array('PHP', 'MySQL', 'JavaScript', 'CSS', 'Yii Framework')
); ? >
  </div>
</div>
-->



<br/>

<div class="control-group ">
  <label class="control-label" for="TestForm_checkboxes">Checkboxes</label>
  <div class="controls">
    <input id="ytTestForm_checkboxes" type="hidden" value="" name="TestForm[checkboxes]" />
    <label class="checkbox">
      <input id="TestForm_checkboxes_0" value="0" type="checkbox" name="TestForm[checkboxes][]" />
      <label for="TestForm_checkboxes_0">Option one</label>
    </label>
    <label class="checkbox">
      <input id="TestForm_checkboxes_1" value="1" type="checkbox" name="TestForm[checkboxes][]" />
      <label for="TestForm_checkboxes_1">Option two</label>
    </label>
    <label class="checkbox">
      <input id="TestForm_checkboxes_2" value="2" type="checkbox" name="TestForm[checkboxes][]" />
      <label for="TestForm_checkboxes_2">Option three</label>
    </label>

    <p class="help-block"><strong>Note:</strong> Labels surround all the options for much larger click areas.</p>

  </div>
</div>


<!--
	< ?php $this->endWidget();? >
-->
