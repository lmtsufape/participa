<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Validator::extend('telefone', '\App\Utils\TelefoneValidation@validate', 'Celular inválido');
        Validator::extend('time', '\App\Utils\TimeValidation@validate', 'Hora inválida');

        $locale = Session::get('locale', config('app.fallback_locale'));
        App::setLocale($locale);

        Blade::directive('sortlink', function (string $expression) {
            // Divide em 2 partes: campo e rótulo (com aspas preservadas)
            [$field, $label] = array_pad(
                preg_split('/\s*,\s*/', $expression, 2),
                2,
                "''"
            );

            return <<<PHP
    <?php
        // Valores já compilados (ex.: 'titulo', 'Título')
        \$__field = {$field};
        \$__label = {$label};

        \$__current = request('sort');                     // 'campo' ou '-campo'
        \$__next    = (\$__current === \$__field) ? '-' . \$__field : \$__field;

        \$__icon = '';
        if (\$__current === \$__field) {
            \$__icon = '<i class="bi bi-arrow-up ms-1"></i>';
        } elseif (\$__current === '-' . \$__field) {
            \$__icon = '<i class="bi bi-arrow-down ms-1"></i>';
        }

        \$__url = request()->fullUrlWithQuery(['sort' => \$__next, 'page' => null]);
    ?>
    <a href="<?= e(\$__url) ?>" class="text-decoration-none text-dark fw-semibold">
        <?= e(\$__label) ?> <?= \$__icon ?>
    </a>
    PHP;
        });
    }
}
