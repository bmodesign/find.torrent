<?php

namespace FindDotTorrent\Feeds;

class KickAssTorrents extends BaseFeed implements IFeed
{
    protected $url = 'http://kat.ph';
    protected $base_search_url = 'http://kat.ph/usearch/%s/?rss=1';

    public function getUrl()
    {
        return $this->url;
    }

    public function makeSearchUrl($term)
    {
        return sprintf($this->base_search_url, urlencode($term));
    }

    public function fetchResults($response)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($response);

        $xmlPath = new \DOMXPath($dom);
        $itemPath = $xmlPath->query('*/item');

        $results = array();
        foreach($itemPath as $item) {
            $result = new SearchResult();
            $result->setId($item->getElementsByTagName('guid')->item(0)->nodeValue)
                   ->setTitle($item->getElementsByTagName('title')->item(0)->nodeValue)
                   ->setLink($item->getElementsByTagName('enclosure')->item(0)->getAttribute('url'));

            $results[] = $result;
        }

        return $results;
    }
}
