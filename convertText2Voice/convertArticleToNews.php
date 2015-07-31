<?php

require_once 'vendor/autoload.php';

use Goutte\Client;

$pages = [
    "intro-whatis",
    "intro-cando",
    "tutorial.requirements",
    "tutorial.firstpage",
    "tutorial.useful",
    "tutorial.forms",
    "tutorial.oldcode",
    "tutorial.whatsnext"
];

$voicePath = "/usr/share/hts-voice/mei";
$voiceType = "mei_normal.htsvoice";
$dictPath = "/var/lib/mecab/dic/open-jtalk/naist-jdic";

foreach ($pages as $pageName) {
    $client = new Client();
    try {
        $crawler = $client->request('GET', "http://php.net/manual/ja/{$pageName}.php");

        // 音声変換する記事を配列に格納していく
        $news[] = $crawler->filter('h2.title')->first()->text();
        $news[] = $crawler->filter('p.simpara')->first()->text();
        $crawler->filter('p.para')->each(function ($element) use (&$news) {
            $news[] = $element->text();
        });
    } catch (\Exception $e) {
        echo "pageName: {$pageName} スクレイピング処理でエラーが発生しました。";
        throw $e;
    }

    try {
        // 音声に変換していく
        $input = implode("\r", $news);
        exec("echo {$input} | open_jtalk -m {$voicePath}/{$voiceType} -ow {$pageName}.wav -x {$dictPath}", $output);
        unset($client, $crawler, $news, $input);
    } catch (\Exception $e) {
        echo "pageName: {$pageName} 音声変換処理でエラーが発生しました。";
        throw $e;
    }
}

