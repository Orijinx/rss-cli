<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\log;
use App\Models\article;
use App\Models\enclosure;

class ParsingRSS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:parsing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parsing RSS and save into data base';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // Скачивание файла через cURL
    private function DownloadFile(): bool
    {
        $ch = curl_init("http://static.feed.rbc.ru/rbc/internal/rss.rbc.ru/rbc.ru/news.rss");
        $fp = fopen("test_parser.rss", "w");
        curl_setopt($ch, CURLOPT_USERAGENT, "google");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_exec($ch);
        $info = curl_getinfo($ch);
        $log = new log();
        $log->request_method = $info["request_header"];
        $log->request_url = $info["url"];
        $log->response_http_code = $info["http_code"];
        $log->response_body = $info["header_size"];
        $log->save();
        curl_close($ch);
        fclose($fp);
        return true;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->DownloadFile()) {
            $data = simplexml_load_file("test_parser.rss");
            foreach ($data->channel->item as $item) {
                $article = new article();
                $article->title = $item->title;
                $article->url = $item->link;
                $article->description = $item->description;
                $article->datetime = new \DateTime((string)$item->pubDate);
                if (!empty($item->author)) {
                    $article->author = $item->author;
                }

                $article->save();
                // var_dump($item->enclosure->attributes());
                if (!empty($item->enclosure->attributes())) {
                    foreach ($item->enclosure as $i) {
                        $enclosure = new enclosure();
                        $enclosure->img_url = (string)$i["url"];
                        $enclosure->article_id = $article->id;
                        $enclosure->save();
                    }
                }
                
            }
        }
    }
}
