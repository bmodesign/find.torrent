<?php

namespace FindDotTorrent\Feeds;

class Fetch
{
    final public static function fetchResults($term, IFeed $feed)
    {
        $url = $feed->makeSearchUrl($term);

        $request = curl_init();
        curl_setopt($request, CURLOPT_ENCODING, "gzip");
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($request);
        curl_close($request);

        return $feed->fetchResults($data);
    }

    final public static function fetchTorrent($target, $download_path)
    {
        $ch = curl_init($target);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'find.torrent');

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ( 200 != $httpCode ) {
            return false;
        }

        $path = $download_path . DIRECTORY_SEPARATOR . uniqid(rand(), true) . '.torrent';
        $result = file_put_contents($path, $data);

        return ( false === $result ) ? false : $path;
    }
}
