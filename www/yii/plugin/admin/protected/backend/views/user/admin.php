<?php
$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List User','url'=>array('index')),
	array('label'=>'Create User','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('user-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'kid',
		'email_address',
		'password',
		'display_name',
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>




<?php
$this->widget('bootstrap.widgets.TbExtendedGridView', array(
'filter'=>$model,
'type'=>'striped bordered',
'dataProvider' => $model->search(),
'template' => "{items}",
'columns' =>array(
array('name'=>'id', 'header'=>'#', 'htmlOptions'=>array('style'=>'width: 60px')),
array('name'=>'kid', 'header'=>'K Id'),
array('name'=>'email_address', 'header'=>'Email'),
array('name'=>'password', 'header'=>'Password'),
array('name'=>'display_name', 'header'=>'Display Name'),
array(
'name'=>'kid',
'header'=>'K.Id',
'headerHtmlOptions' => array('style' => 'width:50px'),
'class'=>'bootstrap.widgets.TbJEditableColumn',
'jEditableOptions' => array(
'type' => 'text',
// very important to get the attribute to update on the server!
'submitdata' => array('attribute'=>'kid'),
'cssclass' => 'form',
'width' => '80px'
)
),
array(
'htmlOptions' => array('nowrap'=>'nowrap'),
'class'=>'bootstrap.widgets.TbButtonColumn',
'viewButtonUrl'=>null,
'updateButtonUrl'=>null,
'deleteButtonUrl'=>null,
)
)
));
?>

