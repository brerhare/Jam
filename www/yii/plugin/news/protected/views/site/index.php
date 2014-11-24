<?php

// @@NB This is the place to amend the number of item on each of the two page types.
//      @@TODO The iframe height is set by the first page but not adjusted for the height of the second. This results in padding or clipping

$maxDisplayItems = 52;
$maxDisplayArchiveItems = ($maxDisplayItems * 4);

if (Yii::app()->session['news_type'] == 'traditional')
	require('index_traditional.php');
else if (Yii::app()->session['news_type'] == 'pinterest')
	require('index_pinterest.php');
else
	die('Invalid or missing newstype');
?>

