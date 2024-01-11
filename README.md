# Laravel RuSender Transport Unofficial
![Image alt](https://github.com/qoonmax/laravel-rusender-transport-unofficial/blob/main/lrtu.png)

## Установка

### Шаг 1: Установка пакета
Чтобы установить пакет, выполните следующую команду в терминале:

```bash
composer require qoonmax/laravel-rusender-transport-unofficial
```

### Шаг 2: Публикация сервис-провайдера и файла конфигурации
Опубликуйте сервис-провайдер и файл конфигурации с помощью:

```bash
php artisan vendor:publish
```

### Шаг 3: Изменение переменных окружения
Обновите ваш .env файл, чтобы включить следующие параметры:

```
MAIL_MAILER=rusender
RUSENDER_API_KEY="example-key"
```
