# Laravel RuSender Transport Unofficial

---

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
Опубликуйте сервис-провайдер и файл конфигурации с помощью:

```
MAIL_MAILER=rusender
RUSENDER_API_KEY="example-key"
```
