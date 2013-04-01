
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
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Single</th>
            <th>Double</th>
            <th>Any</th>
            <th>Per Adult</th>
            <th>Per Child</th>
        </tr>
        </thead>
        <tbody>
        <tr class="odd">
            <td>Room Only</td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        <tr class="even">
            <td>Bed & Breakfast</td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        <tr class="odd">
            <td>Dinner, Bed & Breakfast</td><td></td><td></td><td></td><td></td><td></td>
        </tr>
        </tbody>
    </table>
<!--
	< ?php $this->endWidget();? >
-->