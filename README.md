# О приложении

-   Laravel version: **8.56**
-   Data Base: **MySql**
-   PHP version: **8.0.7**

## Возможности
- Запуск скрипта по парсингу и логированию RSS ленты РБК.
    ```bash
    php artisan rss:parsing
    ```

## **Laravel Schedule/Cron**

Команда добавлена в обработку **Schedule-worker**:

```php
 protected function schedule(Schedule $schedule)
    {
        //Выполнение команды артисан, через планирование задач Laravel
        $schedule->command('rss:parsing')->daily();
    }
```

Для запуска воркера на сервере необходимо выполнить команду:

```bash
cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
Локальный запуск:
```bash
php artisan schedule:work
```
## **Модули**
- *App\Console\Commands\ParsingRSS.php*
    ### Методы:
    ```php
    //Скачивание файла, обработка запроса, запись лога запроса в БД
    private function DownloadFile();
    //Обработка скаченного файла, запись статей в БД 
    public function handle();
    ```
## **Модели**
- *App\Models\Article.php*
    ```php
    //Получение картинок
    public function images()
    {
    return $this->hasMany(enclosure::class,'article_id', 'id');
    }
    ```
## Добавленные миграции 
- *Database\Migrations\Article* - Миграция таблицы со статьями
- *Database\Migrations\Log* - Миграция таблицы логов запросов
- *Database\Migrations\Enclosure* - Миграция таблицы картинок