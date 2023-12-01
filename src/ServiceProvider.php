<?php

namespace Green\ResourceModule;

use Filament\Forms\Form;
use Filament\Tables\Table;
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
        /**
         * @class Form
         * @method Form addSchema(string $before, array $components)
         */
        Form::macro('addSchema', function ($before, $components) {
            return Module::addFormComponents($this, $before, $components);
        });

        /**
         * @class Table
         * @method Form addColumns(string $before, array $components)
         */
        Table::macro('addColumns', function ($before, $components) {
            return Module::addTableColumns($this, $before, $components);
        });

        /**
         * @class Table
         * @method Form addFilters(string $before, array $components)
         */
        Table::macro('addFilters', function ($before, $components) {
            return Module::addTableFilters($this, $before, $components);
        });

        /**
         * @class Table
         * @method Form addActions(string $before, array $components)
         */
        Table::macro('addActions', function ($before, $components) {
            return Module::addTableActions($this, $before, $components);
        });
    }
}
