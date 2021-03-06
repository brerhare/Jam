<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

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

<body>

<div class="container" id="page">


<div style="margin-top:50px">

	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => 'null', // null or 'inverse'
	'brand' => 'Home',
	'brandUrl' => array('/site/index'),
	'collapse' => true, // requires bootstrap-responsive.css
    'items' => array(
        array(
            'class' => 'bootstrap.widgets.TbMenu',
            'items' => array(
                array('label' => 'Pages', 'url' => array('/contentBlock/admin'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Sliders', 'url' => array('/jellySliderImage/admin'), 'visible' => !Yii::app()->user->isGuest),
                //array('label' => 'HTML Sliders', 'url' => array('/jellySliderHtml/admin'), 'visible' => !Yii::app()->user->isGuest),

                array('label' => 'Addons', 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
                    'items' => array(
                        array('label' => 'Galleries', 'url' => array('/jellyGallery/admin'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Tabs', 'url' => array('/tabBlock/admin'), 'visible' => !Yii::app()->user->isGuest),
                        //array('label' => 'Ad Blocks', 'url' => array('/jellyAdblock/admin'), 'visible' => !Yii::app()->user->isGuest),
                        //array('label' => 'Ticker', 'url' => array('/jellyTicker/admin'), 'visible' => !Yii::app()->user->isGuest),
                    ),
                ),

                array('label' => 'Plugins', 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
                    'items' => array(
                        //array('label' => 'Products', 'url' => 'https://plugin.wireflydesign.com/product/backend.php/site/rotaryPeaceProjectDirect', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Blog', 'url' => 'https://plugin.wireflydesign.com/news/backend.php/site/rotaryPeaceProjectDirect', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Newsletters', 'url' => 'https://plugin.wireflydesign.com/mailer/backend.php/site/rotaryPeaceProjectDirect', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
						array('label' => 'Events', 'url' => 'https://plugin.wireflydesign.com/event/backend.php/site/login2/?sid=' . Yii::app()->session['admin_user_sid'], 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
                        //array('label' => 'Tickets', 'url' => 'https://plugin.wireflydesign.com/ticket/backend.php/site/rotaryPeaceProjectDirect', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
                    ),
                ),

                array('label' => 'Downloads', 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
                    'items' => array(
                        array('label' => 'Files', 'url' => array('/jellyDownloadFile/admin'), 'visible' => !Yii::app()->user->isGuest),
                        array('label' => 'Collections', 'url' => array('/jellyDownloadCollection/admin'), 'visible' => !Yii::app()->user->isGuest),
                    ),
                ),

                array('label' => 'Settings', 'url' => array('/jellySetting/admin'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                array('label' => 'Logout', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Help', 'url' => 'http://www.knowledgebase.wireflydesign.com/?layout=index&page=jellyshell-website-basics', 'linkOptions' => array("target"=>"_blank"), 'visible' => !Yii::app()->user->isGuest),
            ),
        ),
    ),
)); ?>

        </div><!-- navbar margin adjust -->

	<?php if (isset($this->breadcrumbs)): ?>
		<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
		'links' => $this->breadcrumbs,
	)); ?><!-- breadcrumbs -->
	<?php endif?>

	<?php echo $content; ?>
	<div class="clear"></div>

	<div id="footer">
		Copyright &copy; <?php echo date('Y'); ?> Wirefly Design.<br/>
		All Rights Reserved.<br/>
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
