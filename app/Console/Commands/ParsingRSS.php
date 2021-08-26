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
        $this->info("-Начало скачивания файла...");
        $ch = curl_init("http://static.feed.rbc.ru/rbc/logical/footer/news.rss");
        //Создание буфферного файла
        $fp = fopen("List.rss", "w");
        //Определение опций для cURL
        curl_setopt($ch, CURLOPT_USERAGENT, "google");
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info["http_code"] != 200) {
            $this->msg .= "Ответ от сервера при загрузке файла:" . $info;
            curl_close($ch);
            fclose($fp);
            return false;
        }
        //Проверка ошибок
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
        }
        curl_close($ch);
        if (isset($error_msg)) {
            $this->info($error_msg);
            return false;
        }
        //Запись лога в бд
        $log = new log();
        $log->request_method = $info["request_header"];
        $log->request_url = $info["url"];
        $log->response_http_code = $info["http_code"];
        $log->response_body = $info["header_size"];
        $log->save();
        fclose($fp);
        $this->info("Успешно!");
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
            $this->info("-Начало анализа RSS...");
            if (filesize('List.rss') == 0) {
                $this->info("Буфферный файл - пуст.\n-Прекращение выполнение команды...");
                return 0;
            }
            //Загрузка файла в SimpleXML - простейший способ анализа XML, но не самый быстрый.
            $data = simplexml_load_file("List.rss");
            foreach ($data->channel->item as $item) { //Перебор всех новостей
                //Запись новости в базу данных.
                $article = new article();
                $article->title = $item->title;
                $article->url = $item->link;
                $article->description = $item->description;
                $article->datetime = new \DateTime((string)$item->pubDate);
                if (!empty($item->author)) {
                    $article->author = $item->author;
                }
                $article->save();
                //Перебор атрибутов картинок
                if (!empty($item->enclosure->attributes())) {
                    foreach ($item->enclosure as $i) {
                        //Запись картинок в базу данных
                        $enclosure = new enclosure();
                        $enclosure->img_url = (string)$i["url"];
                        $enclosure->article_id = $article->id;
                        $enclosure->save();
                    }
                }
            }
            $this->info("Успешно!");
        }
        return 0;
    }
}
