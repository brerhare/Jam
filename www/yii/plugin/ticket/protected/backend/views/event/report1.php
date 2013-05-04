
<h2>Event Report</h2>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
//	'filter'=>$model,
	'columns'=>array(
//		'id',
//		'uid',
		'title',
		'date',
		'active:boolean', 
//		'active_start_date',
//		'active_start_time',
//		'active_end_date',
//		'active_end_time',

//		'address',
//		'post_code',
//		'ticket_logo_path',
//		'ticket_text',
//		'ticket_terms',
//		'ticket_vendor_id',

		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
            'template'=>'{report}',
			'htmlOptions'=>array('width'=>'80px'),
			'buttons'=>array(
                'report' => array(
                    'label'=>'Report',
                    // @@EG How to use a tbbuttoncolumn to go to a url WITH the gridview value.
                    // Can do JS too - see http://yii.vingtsun-grodno.com/yii-grid-%D1%83%D0%B4%D0%B0%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5-%D0%B7%D0%B0%D0%BF%D0%B8%D1%81%D0%B5%D0%B9-%D1%81-%D0%BA%D0%B0%D1%81%D1%82%D0%BE%D0%BC%D0%BD%D1%8B%D0%BC-%D0%B4%D0%B8%D0%B0%D0%BB%D0%BE/
					'url'   => 'Yii::app()->controller->createUrl("showReport", array("id" => $data->id))',
                    'imageUrl'=>Yii::app()->request->baseUrl.'/img/report.jpg',
                ),
            ),
		),
	),
)); ?>