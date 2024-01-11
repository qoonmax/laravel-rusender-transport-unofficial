# Laravel RuSender Transport Unofficial
![Image alt](https://github.com/qoonmax/laravel-rusender-transport-unofficial/blob/main/lrtu.png)

## Установка

### Шаг 1: Установка пакета
Чтобы установить пакет, выполните следующую команду в терминале:

```bash
composer require qoonmax/laravel-rusender-transport-unofficial:dev-main
```

### Шаг 2: Публикация файла конфигурации
Опубликуйте файл конфигурации с помощью:

```bash
php artisan vendor:publish --provider="Qoonmax\RuSenderApiMailTransport\RuSenderTransportProvider"
```

### Шаг 3: Добавление почтового драйвера
Добавьте следующий код в файл config/mail.php:

```
'rusender' => [
    'transport' => 'rusender',
]
```

### Шаг 4: Изменение переменных окружения
Обновите ваш .env файл, чтобы включить следующие параметры:

```
MAIL_MAILER=rusender
RUSENDER_API_KEY="example-key"
```
