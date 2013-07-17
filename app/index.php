<?php

namespace App;

use Calendar\Record;
use Calendar\Template;
use Silex\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->get('/', function() use($app) {
    $url = 'http://pse.com.ph/stockMarket/dividendRights.html?method=getDividends&ajax=true';
    $fetcher = new Record($url, __DIR__ . '/../cache');
    $records = $fetcher->getRecords();
    $events = array();
    foreach ($records as $record) {
        if (!isset($record['exDividendDate'])) continue;

        $exdivdate = strtotime($record['exDividendDate']);
        $recdate = strtotime($record['recordDate']);
        $paydate = strtotime($record['datePayable']);
        $summary = $record['companyName'] . ' ('.$record['securitySymbol'].')';
        $description = $record['dividendType'] . ': '.$record['dividendValue'] . 
                '\r\nEx-div date: '. date('M j, Y', $exdivdate).
                '\r\nRecord date: '. date('M j, Y', $recdate).
                '\r\nDate payable: '. date('M j, Y', $paydate).
                '\r\nhttp://www.pse.com.ph'.$record['disclosureLocation'];
        $events[] = Template::getEvent(array(
            '@dstart' => date('Ymd', $exdivdate),
            '@dend' => date('Ymd', $exdivdate),
            '@created' => date('Ymd', $exdivdate),
            '@lastmodified' => date('Ymd', $recdate),
            '@until' => date('Ymd', $paydate),
            '@stamp' => date('Ymd', $exdivdate),
            '@summary' => $summary,
            '@uid' => 'div'.sprintf('%u', crc32($summary)).'@pse.com.ph',
            '@description' => $description,
        ));
        
    }
    return Template::getCalendar(array('@events' => implode($events)));
});

$app->run();
