<?php

namespace Green\ResourceModule\Facades;

use Filament\Forms\Form;
use Filament\Tables\Table;

/**
 * リソースモジュールの管理ファサード
 *
 * @method static register(string[] $array): void
 * @method static apply(string $resource, Form|Table $parent): Form|Table
 */
class ModuleRegistry extends \Illuminate\Support\Facades\Facade
{
    /**
     * コンポーネントの登録名を取得
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Green\ResourceModule\Services\ModuleRegistry::class;
    }
}
