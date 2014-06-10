<?php
//error_reporting(E_ALL | E_STRICT);
/**
 * API for Blog filter
 *
 * Notes
 * -----
 * This will use the width and height of your container
 */

class blogs
{

// Not used ...
    public $apiOption = array(
    );

    /*
     * Set up any code pre-requisites (onload/document-ready reqs)
     * Apply options
     * Return an array containing [0]localContent [1]globalContent
     */
    public function init($options, $jellyRootUrl)
    {
//      var_dump( $options );

        // Generate the content into the html, replacing any <substituteN> tags
        $content = "";
		$category = "";
		$recent = "";
		$blogwidth = "auto";
        foreach ($options as $opt => $val)
        {
            switch ($opt)
            {
                case "recent":	// Get the most recent article for the applicable category (or all if 0)
                    $recent = $val;
                    break;
                case "category":
                    $category = $val;
                    break;
                case "blogwidth":
                    $blogwidth = $val;
                    break;
                default:
                    break;
            }
        }

        // Apply all defaults that werent overridden

        // HTML

        // JS

        // Insert the data
        $content = "<div style='padding-left:20px; font-size: 15px; background-color: #f8f8f8; color:#575757;'>";      // Your basic solemn grey font color
        $uid = Yii::app()->session['uid'];

        // Most recent article?
        $criteria = new CDbCriteria;
        $criteria->addCondition("uid=" . $uid);
		if (($category != "") && ($category != "0"))
			$criteria->addCondition("blog_category_id=" . $category);
        $articles = Article::model()->findAll($criteria);
        //$articles  = Article::model()->findAll(array('order'=>'date DESC', 'condition'=>'uid=' . Yii::app()->session['uid'] . ' and ' ));
/*
        if ($articles)
        {
			$content .= "<br>";
			$content .= "<b>";
          	$content .= "<a onClick=makeSel(0) href='#'>All Categories</a><br>";
            foreach ($categories as $category):
            	$content .= "<a onClick=makeSel(" . $category->id . ") href='#'>$category->name</a><br>";
            endforeach;
			$content .= "<br/>";
			$content .= "</b>";
        }
*/

        // List of categories?
		if ($category != "")
		{
        	$categories  = Category::model()->findAll(array('order'=>'name', 'condition'=>'uid=' . $uid));
        	if ($categories)
        	{
				$content .= "<br>";
				$content .= "<b>";
          		$content .= "<a onClick=makeSel(0) href='#'>All Categories</a><br>";
            	foreach ($categories as $category):
            		$content .= "<a onClick=makeSel(" . $category->id . ") href='#'>$category->name</a><br>";
            	endforeach;
				$content .= "<br/>";
				$content .= "</b>";
        	}
		}

		$content .= "</div>";

		// Insert SID into js
		$content .= "<script> var SID = '" . $_GET['sid'] . "'; </script>";

		// Insert blog width into js
		$content .= "<script>var blogwidth='" . $blogwidth . "'; </script>";

        $html = str_replace("<substitute-data>", $content, $this->apiHtml);
        $this->apiHtml = $html;

        // This is kind of a standard replace
        $this->apiHtml = str_replace("<substitute-path>", $jellyRootUrl, $this->apiHtml);

        $retArr = array();
        $retArr[0] = $this->apiHtml;
        $retArr[1] = $this->apiJs;
        return $retArr;
    }

    private $apiHtml = <<<END_OF_API_HTML

        <div id="jelly-blog-filter-container">
            <!--Blog Filter-->
            <link rel="stylesheet" type="text/css" href="<substitute-path>/blogs.css" />
            <substitute-data>
        </div>

END_OF_API_HTML;

    private $apiJs = <<<END_OF_API_JS

    function makeSel(id)
    {
		sel = '?layout=index';
		if (blogwidth == '750')
			sel = '?layout=index750';

		sel += '&sid=' + SID;
       	sel += '&category=' + id; 

        // Activate the link
        window.location.href = sel;
    }

    jQuery(document).ready(function($){
    });

END_OF_API_JS;

}
?>
