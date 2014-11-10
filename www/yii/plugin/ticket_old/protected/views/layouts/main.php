<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

<!-- @@ TODO: NOTSURE... This next line allegedly sets 'trust' for iframes to set cookies. Doesnt work for me tho... -->
<?php header("P3P: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");?> 

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<style>
body {
	padding: 0;
}
#sidebar {
	padding: 0;
}
#content {
	padding: 0;
}
#page {
	margin: 0;
}
div.form .row {
	margin: 0;
}
</style>

<body>

<div class="container" id="page">


	<?php echo $content; ?>
	<div class="clear"></div>

</div><!-- page -->

</body>
</html>
