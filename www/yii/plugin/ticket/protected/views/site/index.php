<!-- Display a list of events to pick from -->

<!- @@EG Radio in view. See controller update and create. -->

<div class="row">
    <div class="span3 well">
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
        <td width="90%">
        <?php echo $event->title; ?>
        <br>
        <?php echo $event->date; ?>
        </td>
        <td width="10%">
			<?php $this->widget('bootstrap.widgets.TbButton',array(
				'label' => 'Book',
				'url' => 'bookEvent',
				'type' => 'primary',
				'size' => 'small'
			));?>
        </td>
        </tr>
            <?php endforeach; ?>
        </table>
    </div><!-- /span -->
</div> <!-- /row -->
