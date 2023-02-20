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

### Dodaj plik konfiguracji payu

config/payu.php

```sh
php artisan vendor:publish --tag=payu-config
```

### Migracja tabel

```sh
php artisan migrate:fresh
php artisan migrate:fresh --env=testing

# Options
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed --seeder=CarteloSeeder
php artisan migrate:fresh --seed --seeder=CarteloSeeder --env=testing
```

### Wersje językowe walidacji logowania (en, pl)

```sh
php artisan lang:publish
php artisan vendor:publish --tag=webi-lang-en
php artisan vendor:publish --tag=webi-lang-pl
```

## Testy

### Konfiguracja phpunit.xml

```xml
<testsuites>
  <testsuite name="CarteloRoute">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Route</directory>
  </testsuite>
  <testsuite name="CarteloCart">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Cart</directory>
  </testsuite>
  <testsuite name="CarteloModel">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Model</directory>
  </testsuite>
  <testsuite name="CarteloRes">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Resource</directory>
  </testsuite>
  <testsuite name="Webi">
      <directory suffix="Test.php">./vendor/atomjoy/webi/tests</directory>
  </testsuite>
  <testsuite name="Payu">
    <directory suffix="Test.php">./vendor/atomjoy/payu/tests/Payu</directory>
  </testsuite>
</testsuites>

<php>
  <env name="APP_ENV" value="testing"/>
  <env name="APP_DEBUG" value="false" force="true"/>
</php>
```

## Sprawdzanie ustawień

```sh
php artisan test --stop-on-failure --testsuite=Webi
php artisan test --stop-on-failure --testsuite=Payu
php artisan test --stop-on-failure --testsuite=CarteloModel
php artisan test --stop-on-failure --testsuite=CarteloRoute
php artisan test --stop-on-failure --testsuite=CarteloCart
php artisan test --stop-on-failure --testsuite=CarteloRes
```

## Błędy

```sh
sudo mkdir -p storage/framework/cache/payu
sudo chown -R www-data:www-data storage/framework/cache/payu
sudo chmod -R 770 storage/framework/cache/payu
```

## Route api

routes/web.php
