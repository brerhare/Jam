<?php
/* @var $this MailerMemberController */
/* @var $model MailerMember */

$this->menu=array(
	array('label'=>'Create Member', 'url'=>array('create')),
);

?>

<h2>Manage Mailing List Members</h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'mailer-member-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',
		'email_address',
		'first_name',
		'last_name',
		//'telephone',
		//'address',
		//'mailer_list_id',
		'active',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
