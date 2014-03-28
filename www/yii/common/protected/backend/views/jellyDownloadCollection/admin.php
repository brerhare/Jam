<?php

$this->menu=array(
	array('label'=>'Create Download Collection','url'=>array('create')),
);

?>

<h2>Manage Download Collections</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'download-collection-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',

        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("jellyDownloadCollection/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
