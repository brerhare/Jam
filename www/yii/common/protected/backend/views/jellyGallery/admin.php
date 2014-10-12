<?php
$this->menu=array(
	array('label'=>'Create Album','url'=>array('create')),
);

?>

<h2>Manage Gallery Albums</h2>

<style>
Ximg { height:50px;}
</style>


<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'jelly-gallery-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		//'id',

        array(
            'name'  => 'sequence',
            'value' => 'CHtml::link($data->sequence, Yii::app()->createUrl("jellyGalleryImage/session",array("gallery_id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

        array(
            'name'  => 'title',
            'value' => 'CHtml::link($data->title, Yii::app()->createUrl("jellyGalleryImage/session",array("gallery_id"=>$data->primaryKey)))',
            'type'  => 'raw',
        ),

		//'image',

        array('name'=>'image',
            'type'=>'html',
			'htmlOptions'=>array('width'=>'60px'),
            'header'=>'',
            'value'=> 'CHtml::image("/userdata/jelly/gallery/" . $data->image, "image", array("height"=>"50"))',
        ),


		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template'=>'{update}{images}{delete}',
            'buttons'=>array(
                'images' => array(
                    'label'=>'Images',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/image.png',
                    'url'=>'Yii::app()->controller->createUrl("jellyGalleryImage/session", array("gallery_id"=>$data->primaryKey))',
                ),
            ),

		),
	),
)); ?>
