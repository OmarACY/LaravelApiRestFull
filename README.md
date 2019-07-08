# Laravel API RESTFULL

Crear nuevo controlador ligado a un modelo

```bash
php artisan make:controller Product/ProductoController -r -m Product
```

### Install passport

Install passport with composer:
```bash
composer require laravel\passport
```

Add next line in **config\app.php** in **Package Service Providers section**:
```bash
Laravel\Passport\PassportServiceProvider::class,
```

Run migrates:
```bash
php artisan migrate
```

Generate keys for passport:
```bash
php artisan passport:install
```
