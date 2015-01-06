<?php

$this->menu=array(
	array('label'=>'Create Mailing List','url'=>array('create')),
);

?>

<h2>Manage Mailing Lists</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'mailer-list-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		//'uid',

        array(
            'name'  => 'name',
            'value' => 'CHtml::link($data->name, Yii::app()->createUrl("mailerMember/session",array("list_id"=>$data->id)))',
            'type'  => 'raw',
        ),


/****
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
****/


        Array(
            'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{update}{members}{import}{download}{delete}',
            'htmlOptions'=>array('width'=>'250px'),
            'buttons'=>array(                               // @@EG: Buttons
                'members' => array(
                    'imageUrl'=>false,
                    'label'=>'Members',
                    'url'=>'Yii::app()->createUrl("mailerMember/session", array("list_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
                'import' => array(
                    'imageUrl'=>false,
                    'label'=>'Import',
                    'url'=>'Yii::app()->createUrl("mailerList/import", array("list_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
                'download' => array(
                    'imageUrl'=>false,
                    'label'=>'Download',
                    'url'=>'Yii::app()->createUrl("mailerList/download", array("list_id"=>$data->id))',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
            ),                                              // End of @@EG


        ),




	),
)); ?>
