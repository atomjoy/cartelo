# Tests

## Wersja cartelo z webi 9.0.*

### Dodaj w app/Exceptions/Handler.php

Translations for "Unauthorized." and "Not Found." auth error

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
