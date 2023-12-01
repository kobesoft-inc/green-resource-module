<?php

namespace Green\ResourceModule\Facades;

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
