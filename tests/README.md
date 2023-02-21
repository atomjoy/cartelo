# Testy

## Wersje językowe walidacji logowania (en, pl)

```sh
# Utwórz katalog lang i zmień katalog en na en-copy
php artisan lang:publish

# Dodaj tłumaczenia z pakietu
php artisan vendor:publish --tag=webi-lang-en
php artisan vendor:publish --tag=webi-lang-pl
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

### Konfiguracja phpunit.xml

```xml
<testsuites>
  <testsuite name="Webi">
      <directory suffix="Test.php">./vendor/atomjoy/webi/tests</directory>
  </testsuite>
  <testsuite name="Payu">
    <directory suffix="Test.php">./vendor/atomjoy/payu/tests/Payu</directory>
  </testsuite>

  <testsuite name="CarteloModel">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Model</directory>
  </testsuite>
  <testsuite name="CarteloRoute">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Route</directory>
  </testsuite>
  <testsuite name="CarteloCart">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Cart</directory>
  </testsuite>
  <testsuite name="CarteloRes">
      <directory suffix="Test.php">./vendor/atomjoy/cartelo/tests/Cartelo/Resource</directory>
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

## Migracje

```sh
php artisan migrate:fresh --seed
php artisan migrate:fresh --seed --seeder=CarteloSeeder
php artisan migrate:fresh --seed --seeder=CarteloSeeder --env=testing
```

## Route api

routes/web.php
