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

Import **Laravel\Passport\HasApiTokens** in **User** model:
```php
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
use HasApiTokens;
...
}
```

Register require routes in **app\Providers\AuthServiceProvider**:
```php
class AuthServiceProvider extends ServiceProvider
{
    ...
    
    public function boot()
    {
        ...
        
        Passport::routes();
    }
}
```

And add this line in **routes\api.php**:
```php
...

Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
```

Modify guard driver in **config\auth.php**:
```php
'guards' => [        
        ...        
        'api' => [
            'driver' => 'passport',
            ...
        ],
    ],
```

Assign expiration time token **app\Providers\AuthServiceProvider** in **config\auth.php**:
```php
class AuthServiceProvider extends ServiceProvider
{
    ...
    
    public function boot()
    {
        ...
        
        Passport::tokensExpireIn(Carbon::now()->addMinutes(30));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
    }
}
```

### Install CORS
Install passport with composer:
```bash
composer require barryvdh/laravel-cors
```

Add next line in **config\app.php** in **Package Service Providers section**:
```bash
\Barryvdh\Cors\ServiceProvider::class,
```

Publich provider
```bash
php artisan vendor:publish --provider="Barryvdh\Cors\ServiceProvider"
```
