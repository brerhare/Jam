<?php

$this->menu=array(
	array('label'=>'Create list Member','url'=>array('create')),
	array('label'=>'Back to Manage Lists','url'=>array('mailerList/admin')),
);

?>

<h2>Manage List Members for
<?php
	$model_list = MailerList::model()->findByPk(Yii::app()->session['list_id']);
	echo $model_list->name;
?>

</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-member-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',

        array(
            'name'  => 'email_address',
            'value' => 'CHtml::link($data->email_address, Yii::app()->createUrl("mailerMember/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'first_name',
            'value' => 'CHtml::link($data->first_name, Yii::app()->createUrl("mailerMember/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'last_name',
            'value' => 'CHtml::link($data->last_name, Yii::app()->createUrl("mailerMember/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'telephone',
		//'address',
		//'active',
		//'mailer_list_id',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{delete}',
		),
	),
)); ?>
