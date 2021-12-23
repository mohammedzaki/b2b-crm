<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('checkmenu', function ($expression) {
            return "<?php echo 'hidden'; ?>";
        });

        Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value > $min_value;
        });

        Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters, $validator) {
            //$validator->get
            return str_replace(':field', $validator->getDisplayableAttribute($parameters[0]), $message);
        });

        Validator::extend('greater_than', function ($attribute, $value, $otherValue) {
            return intval($value) > intval($otherValue[0]);
        });

        Validator::replacer('greater_than', function($message, $attribute, $rule, $parameters) {
            return str_replace(':value', $parameters[0], $message);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
