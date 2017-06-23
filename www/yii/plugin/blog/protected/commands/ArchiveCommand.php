<?php

//
// Trim the blog_article table by moving old articles to blog_article_archive
// Only for DGNEWS-SPORT (UID=74)
//

class ArchiveCommand extends CConsoleCommand
{
	public function run($args)
	{
		$cr = "<br>";
		// Report date range
		$fromdate = new DateTime('5000 days ago');
		$todate = new DateTime('90 days ago');

		$criteria = new CDbCriteria;
		$criteria->addCondition("uid = '" . 74 . "'");
		$criteria->addCondition("date >= '" . $fromdate->format('Y-m-d') . " 00:00:00'");
		$criteria->addCondition("date <= '" . $todate->format('Y-m-d') . " 99:99:99'");
		$criteria->order = 'date ASC';
		$articles = Article::model()->findAll($criteria);
		foreach ($articles as $article)
		{
			$archive = new Archive;
			$archive->id = $article->id;
			$archive->uid = $article->uid;
			$archive->date = $article->date;
			$archive->title = $article->title;
			$archive->intro = $article->intro;
			$archive->content = $article->content;
			$archive->thumbnail_path = $article->thumbnail_path;
			$archive->blog_category_id = $article->blog_category_id;
			$archive->save();
			$article->delete();
		}

	}
}
?>
