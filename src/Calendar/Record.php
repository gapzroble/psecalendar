<?php

namespace Calendar;


use Doctrine\Common\Cache\PhpFileCache;
use Guzzle\Http\Client;

/**
 * Description of Record
 *
 * @author RRoble
 */
class Record
{

    protected $url;
    protected $cache;
    protected $cacheId;

    public function __construct($url, $cacheDir)
    {
        $this->url = $url;
        $this->cache = new PhpFileCache($cacheDir);
        $this->cacheId = date('YmdH');
    }
    
    protected function getCachedRecords()
    {
        if ($this->cache->contains($this->cacheId)) {
            return $this->cache->fetch($this->cacheId);
        }
    }
    
    protected function saveRecords(array $records)
    {
        $this->cache->save($this->cacheId, $records, 60*60); // 1h
        return $records;
    }

    protected function getFreshRecords()
    {
	$client = new Client();	
	$request = $client->get($this->url);
	$response = $request->send();
	$json = $response->json();
        return $this->saveRecords($json['records']);
    }

    public function getRecords()
    {
        if (($records = $this->getCachedRecords())) {
            return $records;
        }
        
        return $this->getFreshRecords();
    }

}
