<?php
/* @var $this SiteController */

?>




<?php
$criteria = new CDbCriteria;
$criteria->addCondition("url = '" . $url . "'");
$page=Page::model()->find($criteria);
if ($page)
{
    echo $page->content;
}
?>
<br>