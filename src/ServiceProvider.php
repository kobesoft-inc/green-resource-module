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
         * @mixins \Filament\Forms\Form
         * @method Form addSchema(array $components, ?string $before = null, ?string $after = null)
         */
        Form::macro('addSchema', function (array $components, ?string $before = null, ?string $after = null) {
            return Module::addFormSchema($this, $components, $before, $after);
        });

        /**
         * @mixins \Filament\Tables\Table
         * @method Form addColumns(array $components, ?string $before = null, ?string $after = null)
         */
        Table::macro('addColumns', function (array $components, ?string $before = null, ?string $after = null) {
            return Module::addTableColumns($this, $components, $before, $after);
        });

        /**
         * @mixins \Filament\Tables\Table
         * @method Form addFilters(array $components, ?string $before = null, ?string $after = null)
         */
        Table::macro('addFilters', function (array $components, ?string $before = null, ?string $after = null) {
            return Module::addTableFilters($this, $components, $before, $after);
        });

        /**
         * @mixins \Filament\Tables\Table
         * @method Form addActions(array $components, ?string $before = null, ?string $after = null)
         */
        Table::macro('addActions', function (array $components, ?string $before = null, ?string $after = null) {
            return Module::addTableActions($this, $components, $before, $after);
        });
    }
}
