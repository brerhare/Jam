<?php

$this->menu=array(
	array('label'=>'Create Member','url'=>array('create')),
);

?>

<h2>Manage Members</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'member-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',

// @@EG Clickable rows in grid view!
        array(
            'name'  => 'username',
            'value' => 'CHtml::link($data->username, Yii::app()->createUrl("member/update",array("id"=>$data->primaryKey)))',
			'type'  => 'raw',
		),

		'password',
		'displayname',
		'email',
		'member_type_id',
		array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),
	),
)); ?>
