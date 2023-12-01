<?php

namespace Green\ResourceModule;

use Green\ResourceModule\Services\ModuleRegistry;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * アプリケーションサービスを登録する
     */
    public function register(): void
    {
        $this->app->bind(ModuleRegistry::class);
    }

    /**
     * アプリケーションサービスの起動処理を行う
     */
    public function boot(): void
    {
    }
}
