<!-- Display a list of events to pick from -->

<div class="row">
    <div class="span6 well">
        <h3>Available Events</h3> 
        <table>
        <?php
            $criteria = new CDbCriteria;
            $criteria->addCondition("active = " . 1);
            $criteria->addCondition("uid = " . Yii::app()->session['uid']);
            $events = Event::model()->findAll($criteria);
            foreach ($events as $event):
        ?>
        <tr>
        <td width="20%">
			<?php echo CHtml::image(Yii::app()->baseUrl . '/userdata/' . Yii::app()->session['uid'] . '/' . $event->ticket_logo_path,
				'My Image Name',
				array('style'=>'height:80px;'));
			?>
        </td>
        <td width="70%">
        <b><?php echo $event->title; ?></b>
        <br>
        <i><?php echo $event->date; ?></i>
        </td>
        <td width="10%">
			<?php $this->widget('bootstrap.widgets.TbButton',array(
				'label' => 'Book',
				'url' => $this->createUrl('ticket/book', array('id'=>$event->id, 'sid'=>Yii::app()->session['sid'])),
				'type' => 'primary',
				'size' => 'small'
			));?>
        </td>
        </tr>
            <?php endforeach; ?>
        </table>
    </div><!-- /span -->
</div> <!-- /row -->

