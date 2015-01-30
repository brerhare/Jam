<h4 style="display:inline">Approve Events</h4>

<?php
$selPending = "";
$selApproved = "";
if ($showType == '0')
	$selPending = " selected";
else
	$selApproved = " selected";
?>

<div style="display:inline; padding-left:100px">
<select onChange="document.location = this.value" value="GO">
        <option value="/event/backend.php/program/approve/<?php echo $pid;?>" <?php echo $selPending;?> >Events pending approval</option>
        <option value="/event/backend.php/program/approveShowApproved/<?php echo $pid;?>" <?php echo $selApproved;?> >Approved events</option>
</select>
</div>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'event-grid',
	//'dataProvider'=>$model->search(),
	'dataProvider'=>$model->searchSingleProgram($pid, $showType),
	//'filter'=>$model,
	'columns'=>array(
		'id',

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("event/update",array("id"=>$data->primaryKey,"updateMode"=>"view")))',
            'type'  => 'raw',
			'htmlOptions' => array('style'=>'width:390px'),
        ),

		'start',
		'active:boolean',


/*
'buttonID' => array
(
    'label'=>'...',     //Text label of the button.
    'url'=>'...',       //A PHP expression for generating the URL of the button.
    'imageUrl'=>'...',  //Image URL of the button.
    'options'=>array(), //HTML options for the button tag.
    'click'=>'...',     //A JS function to be invoked when the button is clicked.
    'visible'=>'...',   //A PHP expression for determining whether the button is visible.
),
*/

		//'end',
		//'address',
		//'post_code',
		/*
		'web',
		'contact',
		'description',
		'thumb_path',
		'approved',
		'member_id',
		'program_id',
		*/
		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
			'htmlOptions' => array('style'=>'width:80px'),	// @@ CButtonColumn override width
            'template'=>'{approve}{update}',
            'buttons'=>array(
            	'approve' => array(
                	'label'=> $showType == 0 ? 'Approve' : 'Un-approve',
                    'icon'=>false,
                	'imageUrl'=>$showType == 0 ? Yii::app()->request->baseUrl.'/img/tick.png' : Yii::app()->request->baseUrl.'/img/cross.png',
                	'url'=>'Yii::app()->controller->createUrl("admin/toggleEventApproval", array("id"=>$data->primaryKey))',
            	),
            	'update' => array(
                	'label'=> 'View',
                    'icon'=>false,
                	'imageUrl'=>yii::app()->request->baseUrl.'/img/edit.png',
                	'url'=>'Yii::app()->controller->createUrl("event/update", array("id"=>$data->primaryKey,"updateMode"=>"view"))',
            	),
			),

        ),
	),
)); ?>
