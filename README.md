# Cartelo

Dostawa online dla restauracji, produkty, warianty produktów, dodatki, grupy dodatków, koszyk z api, kategorie, zamówienia, płatności payu itp. (przykłady).

## Klasa użytkownika

app/Models/User.php

```php
<?php

namespace App\Models;

use Cartelo\Models\User as CarteloUser;

class User extends CarteloUser
{
  function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    $this->mergeFillable([
      // 'mobile', 'website'
    ]);

    $this->mergeCasts([
      // 'status' => StatusEnum::class,
      // 'email_verified_at' => 'datetime:Y-m-d H:i:s',
    ]);

    // $this->hidden[] = 'secret_hash';
  }

  protected $dispatchesEvents = [
    // 'saved' => UserSaved::class,
    // 'deleted' => UserDeleted::class,
  ];
}
```

### Klasa zamówień

app/Models/Order.php

```php
<?php

namespace App\Models;

use Cartelo\Models\Order as CarteloOrder;

class Order extends CarteloOrder
{
}
```

### Dodaj plik konfiguracji atomjoy/payu

config/payu.php

```sh
php artisan vendor:publish --tag=payu-config
```

### Migracja tabel

```sh
# Aktualizuj
php artisan migrate

# Nowe
php artisan migrate:fresh
php artisan migrate:fresh --env=testing
```

### Punkty końcowe interfejsu API

```sh
php artisan route:list
```

## Błędy

```sh
sudo mkdir -p storage/framework/cache/payu
sudo chown -R www-data:www-data storage/framework/cache/payu
sudo chmod -R 770 storage/framework/cache/payu
```

### Dodaj w app/Exceptions/Handler.php dla tłumaczeń

```php
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

public function register()
{
  $this->renderable(function (AuthenticationException $e, $request) {
   if ($request->is('web/api/*') || $request->wantsJson()) {
    return response()->errors($e->getMessage(), 401);
   }
  });

  $this->renderable(function (NotFoundHttpException $e, $request) {
   if ($request->is('web/api/*') || $request->wantsJson()) {
    return response()->errors('Not Found.', 404);
   }
  });
}
```
