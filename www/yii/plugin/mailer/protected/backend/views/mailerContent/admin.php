<?php
$this->menu=array(
	array('label'=>'Create Mail Content','url'=>array('create')),
);

?>

<h2>Manage Mail Content</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-content-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("mailerContent/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'date',
            'value' => 'CHtml::link($data->date, Yii::app()->createUrl("mailerContent/update",array("id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'content',

		// @@EG: Show Yes/No in grid column for boolean fields
		array(
			'name' => 'sent',
			'header' => "Sent?",
			'value' => '$data->sent?Yii::t(\'app\',\'Yes\'):Yii::t(\'app\', \'No\')',
			'filter' => array('0' => Yii::t('app', 'No'), '1' => Yii::t('app', 'Yes')),
			'htmlOptions' => array('style' => "text-align:left;"),
		),

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{publish}{delete}',
            'htmlOptions'=>array('width'=>'100px'),
            'buttons'=>array(                               // @@EG: Buttons
                'publish' => array(
                    'imageUrl'=>false,
                    'label'=>'Publish',
                    'url'=>'Yii::app()->createUrl("mailerContent/publish", array("content_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
            ),                                              // End of @@EG
		),

	),
)); ?>
