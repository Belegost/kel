<?php

namespace App\Service;

use App\Entity\Integrity\News;
use App\Exception;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class RSSFeed
 * @package App\Service
 */
class RSSFeed
{
    use ContainerAwareTrait;

    /**
     * @link https://www.marketwatch.com/rss/
     */
    private static $marketWatchChannels = [];

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getMarketWatchNews($limit = null)
    {
        try {
            $rss = simplexml_load_file('http://feeds.marketwatch.com/marketwatch/marketpulse/');

            $data = [];
            $i = 0;

            foreach ($rss->channel[0]->item as $item) {

                if ($i === $limit) {

                    break;
                }

                $data[$i]['title'] = $item->title;
                $data[$i]['date'] = $item->pubDate;

                $start = strpos($item->description, '<p>');
                $end = strpos($item->description, '</p>', $start);
                $data[$i]['description'] = substr($item->description, $start, $end-$start+4);

                $i++;
            }
        } catch (\Exception $e) {
            $data = [];
        }

        return $data;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getCryptoNews($limit = null)
    {
        try {
            $lastRSS = new LastRSS();
            $feed = $lastRSS->Get('https://cryptocurrencynews.com/category/daily-news/crypto-news/feed/');

            $data = [];
            $i = 0;

//        var_dump($feed);
//        die();

//        @todo check if feed is available
            foreach ($feed['items'] as $item) {

                if ($i === $limit) {

                    break;
                }

//            var_dump($feed);
//            var_dump($item);
//            die();

                $data[$i]['title'] = $item['title'];
                $data[$i]['date'] = $item['pubDate'];

                $start = strpos($item['description'], '<div class="mh-excerpt">');
                $end = strpos($item['description'], '</div>', $start);
                $str = substr($item['description'], $start, $end-$start+4);

                $str = preg_replace('~(.*)<p[^>]*>.*</p>\R?~s', '$1', $str);
                $data[$i]['description'] = strip_tags($str);

                $i++;
            }
        } catch (\Exception $e) {
            $data = [];
        }

//        echo "<pre>";
//        var_dump($feed);
//        echo "</pre>";
//        die();

        return $data;
    }

    /**
     * @return array
     */
    public function getIntegrityNews()
    {
        $formattedData = [];
        $news = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('Integrity:News')->findAll();

        /** @var News $item */
        foreach ($news as $key => $item) {

            $formattedData[$key]['title'] = $item->getTitle();
            $formattedData[$key]['description'] = $item->getDescription();
            $formattedData[$key]['date'] = $item->getPublicationDate();
        }

        return $formattedData;
    }
}