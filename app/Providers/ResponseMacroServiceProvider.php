<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response as ResponseClass;

class ResponseMacroServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        Response::macro('jsonException', function (\Exception $exception) {
            return Response::json([
                        'code' => $exception->getCode(),
                        'message' => $exception->getMessage()
                            ], ResponseClass::HTTP_INTERNAL_SERVER_ERROR);
        });
    }

}
