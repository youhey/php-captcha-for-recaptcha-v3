# Google reCAPTCHA v3 for PHP scripts

Use Google reCAPTCHA (v3) to protect your sites from fraudulent activities, spam, and abuse.

> [Google reCAPTCHA SDK](https://github.com/google/recaptcha)

## Usage

for Laravel 12.x app

config/captcha.php

```php
<?php
return [
    'site_key' => 'SITE_KEY',
    'secret' => 'SECRET',
];
```

app/Providers/AppServiceProvider.php

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;
use PiCaptcha\Captcha;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Captcha::class, static fn (Application $app): Captcha => new Captcha($app['config']['captcha']['secret']));
    }

    public function boot(): void
    {
        Blade::directive('captchaScript', static fn (string $expression): Htmlable => new HtmlString(Captcha::js($expression)));
    }
}
```

resources/views/layouts/sample.blade.php

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @captchaScript(config('captcha.site_key'))
 
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('head')
    </head>
    <body>
        <div>
            {{ $slot }}
        </div>
    </body>
</html>
```

app/Http/Controllers/Captcha/VerifyTokenController.php

```php
<?php

namespace App\Http\Controllers\Captcha;

use PiCaptcha\Captcha;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyTokenController
{
    private Captcha $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $action = $request->input('action');
        $token = $request->input('token');
        $hostname = $request->getHost();
        $clientIp = $request->getClientIp();
 
        $result = $this->captcha->verify(token: $token, clientIp: $clientIp, action: $action, score: 0.1, hostname: $hostname)

        return new JsonResponse((object) ['success' => $result->isSuccess()]);
    }
}
```

routes/web.php

```php
<?

use Illuminate\Support\Facades\Route;

Route::get('/captcha/verify', App\Http\Controllers\Captcha\VerifyTokenController::class)->name('captcha.verify');
```

(Client Side)

resources/views/captcha/sample.blade.php

```php
<x-sample-layout>
    <script>
        grecaptcha.ready(() => {
            grecaptcha.execute(`{{ config('captcha.site_key') }}`, { action: 'examples/recaptcha' }).then((token) => {
                fetch(`{{ route('captcha.verify') }}?action=examples/recaptcha&token=${token}`).then((response) => {
                    response.json().then((data) => {
                        if (data.success) {
                            // Add your logic to submit to your backend server here.
                        }
                    });
                });
            });
        });
    </script>
    <div>
        <from>
        <!-- ... -->
        </form>
    </div>
</x-sample-layout>
```

(for Backend Server)

resources/views/captcha/sample.blade.php

```php
<x-sample-layout>
    <div>
        <from>
          <input type="hidden" name="captcha_token" id="input-captcha-token">
          <!-- ... -->
        </form>
    </div>
    <script>
      grecaptcha.ready(() => {
        grecaptcha.execute(`{{ config('captcha.site_key') }}`, { action: 'examples/recaptcha' }).then((token) => {
          const input = document.getElementById('input-captcha-token');
          input.value = token;
        });
      });
    </script>
</x-sample-layout>
```
