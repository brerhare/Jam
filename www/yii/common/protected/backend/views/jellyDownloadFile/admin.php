<?php

$this->menu=array(
	array('label'=>'Create Download File','url'=>array('create')),
);

?>

<h2>Manage Download Files</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'download-file-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

        array(
            'name'  => 'filename',
            'value' => 'CHtml::link($data->filename, Yii::app()->createUrl("jellyDownloadFile/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'description',
            'value' => 'CHtml::link($data->description, Yii::app()->createUrl("jellyDownloadFile/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		// @@EG: grid view lookup field in column
		array(
			'name' => 'collection',
			'value' => '$data->jellyDownloadCollection->name',
		),

        array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{delete}',
        ),

	),
)); ?>
