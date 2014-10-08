<?php

$this->menu=array(
	array('label'=>'Create Ticker','url'=>array('create')),
);
?>

<h2>Manage Tickers</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-ticker-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'ticker',

        array(
            'name'  => 'heading',
            'value' => 'CHtml::link($data->heading, Yii::app()->createUrl("jellyTicker/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'text',
            'value' => 'CHtml::link($data->text, Yii::app()->createUrl("jellyTicker/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
