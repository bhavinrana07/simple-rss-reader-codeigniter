<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Feeds extends CI_Controller
{
	protected $mostCommonWords = [];

	const FEED_LIMIT = 200;
	const FEED_URI = 'https://www.theregister.co.uk/software/headlines.atom';

	public function __construct()
	{
		parent::__construct();
		$this->mostCommonWords = $this->getMostCommonWords();
		$this->load->library(array('session'));
	}
	/**
	 * Default function for the controller
	 * loading all the required information
	 * @return void
	 */
	public function index()
	{
		$content['feeds'] = $this->getFeeds(self::FEED_URI, self::FEED_LIMIT);
		$content['filters'] = $this->countAllWords();
		$content['full_name'] = $this->session->userdata('first_name') . ' ' .  $this->session->userdata('last_name');
		$this->load->view('feeds/feeds', $content);
	}

	/**
	 * initialize get feed
	 *
	 * @param string $url
	 * @param integer $feedLimit
	 * @return void
	 */
	private function getFeeds(string $url,int $feedLimit)
	{
		global $feedContent;
		$this->feedRetrieve($url);
		$feeds = array_slice($feedContent, 0, $feedLimit);
		return $feeds;
	}

	/**
	 * Collecting all the words in all the feeds with their count
	 *
	 * @return void
	 */
	private function countAllWords()
	{
		global $allWords;
		$newArray = [];
		foreach ($allWords as $word) {
			if (!empty($word) && strlen($word) > 1)
				$newArray[$word] = empty($newArray[$word]) ? 1 : $newArray[$word] + 1;
		}
		arsort($newArray);
		return $newArray;
	}

	/**
	 * Creating DOMDocument onbject to retrive feeds
	 *
	 * @param string $url
	 * @return void
	 */
	private function feedRetrieve(string $url)
	{
		global $feedContent;
		$doc  = new DOMDocument();
		$doc->load($url);
		$feedContent = array();
		$this->feedCollect($doc);
	}

	/**
	 * Collecting feed entries
	 *
	 * @param DOMDocument $doc
	 * @return void
	 */
	private function feedCollect(DOMDocument $doc)
	{
		global $feedContent;
		$entries = $doc->getElementsByTagName("entry");
		foreach ($entries as $entry) {
			$y = $this->feedTags($entry);
			array_push($feedContent, $y);
		}
	}

	/**
	 * Pick values from a single Feed
	 *
	 * @param $item
	 * @return void
	 */
	private function feedTags($item)
	{
		$y = array();
		$tnl = $item->getElementsByTagName("title");
		$tnl = $tnl->item(0);
		$title = $tnl->firstChild->textContent;

		$tnl = $item->getElementsByTagName("link");
		$tnl = $tnl->item(0);
		$link = $tnl->getAttribute("href");

		$tnl = $item->getElementsByTagName("summary");
		$tnl = $tnl->item(0);
		$description = $tnl->firstChild->textContent;

		$tnl = $item->getElementsByTagName("author");
		$tnl = $tnl->item(0);
		$tnl = $tnl->getElementsByTagName("name");
		$tnl = $tnl->item(0);
		$author = $tnl->firstChild->textContent;

		$tnl = $item->getElementsByTagName("author");
		$tnl = $tnl->item(0);
		$tnl = $tnl->getElementsByTagName("uri");
		$tnl = $tnl->item(0);
		$author_uri = $tnl->firstChild->textContent;

		$y["title"] = $this->cleanAndAddToList($title);
		$y["link"] = $link;
		$y["author"] = $author;
		$y["author_url"] = $author_uri;
		$y["description"] = $this->cleanAndAddToList($description);

		return $y;
	}

	/**
	 * Clean the text and add to list for finding the most repeatative words
	 *
	 * @param string $str
	 * @return void
	 */
	private function cleanAndAddToList(string $str)
	{
		global $allWords;
		$string = $str;
		$string = $this->cleanString($string);
		$words = explode(' ', $string);

		foreach ($words as $word) {
			if (!in_array($word, $this->mostCommonWords) && strlen($word) > 1) {
				$allWords[] = $word;
			}
		}
		return $str;
	}

	/**
	 * Cleaning the String
	 *
	 * @param string $string
	 * @return void
	 */
	private function cleanString(string $string)
	{
		return strip_tags($string);
	}

	/**
	 * Get Most Common words to ingnore the count 
	 *
	 * @return void
	 */
	private function getMostCommonWords()
	{
		$most_common = 'the,be,to,of,and,a,in,that,have,I,it,for,not,on,with,he,as,you,do,at,this,but,his,by,from,they,we,say,her,she,or,an,will,my,one,all,would,there,their,what,so,up,out,if,about,who,get,which,go,me,when,make,can,like,time,no,just,him,know,take,people,into,year,your,good,some,could,them,see,other,than,then,now,look,only,come,its,over,think,also,back,after,use,two,how,our,work,first,well,way,even,new,want,because,any,these,give,day,most,us';
		$exclude = ',is,hi,has,am,are,–,says,still,more';
		return explode(',', $most_common.$exclude);
	}
}
