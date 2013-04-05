<?php
/*
$this->breadcrumbs=array(
	'Transactions'=>array('index'),
	'Manage',
);
*/

$this->menu=array(
/*	array('label'=>'List Transaction', 'url'=>array('index')), */
/*	array('label'=>'Create Transaction', 'url'=>array('create')), */
);

/*
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('transaction-grid', {
		data: $(this).serialize()
	});
	return false;
});
"); */
?>

<h3>Manage Transactions</h3>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'transaction-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'timeStamp',
		'ip',
		'email',
		'adults',
		/*
		'telephone',
		'orderNum',
		'amount',
		'address1',
		'address2',
		'address3',
		'address4',
		'city',
		'state',
		'postCode',
		'countryShort',
		'message',
		*/

		array(
			'name'=>'amount',
			'header'=>'Amount',
			'value'=>function($data){
				return number_format($data->amount / 100, 2);
			},
		),

		/*array(
			'class'=>'CButtonColumn',
            'template'=>'{view}',
		),*/
	),
)); ?>
