<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>

<style type="text/css" media="screen">

</style>
<div style="width:100%" ng-app>

<span style="background-color:pink; display:inline-block; width:23%">
<?php
	$criteria = new CDbCriteria;
	$criteria->addCondition("uid=" . Yii::app()->session['uid']);
	$criteria->order = "name ASC";
	$categories = Category::model()->findAll($criteria);
	if ($categories)
	{
		foreach ($categories as $category)
		{
			echo "<a style='text-decoration:none' href='https://plugin.wireflydesign.com/news/play/?cat=" . $category->id . "'>" . $category->name . "</a><br>";
		}
	}
?>
</span>

<span style="background-color:yellow; display:inline-block; width:75%">

</span>

<?php //var_dump($_GET); ?>

</div>

