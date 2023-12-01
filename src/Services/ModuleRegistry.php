<?php

namespace Green\ResourceModule\Services;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Throwable;

class ModuleRegistry
{
    protected ?Collection $modules = null;

    /**
     * リソースモジュールを登録する
     *
     * @param string[] $modules
     * @return void
     * @throws Throwable
     */
    public function register(array $modules): void
    {
        foreach ($modules as $module) {
            if (!is_subclass_of($module, \Green\ResourceModule\Module::class)) {
                throw new \RuntimeException('Resource module must be subclass of ' . \Green\ResourceModule\Module::class . '.');
            }
            if ($module::$resource == null) {
                throw new \RuntimeException('Resource module must have static property $resource.');
            }
        }
        $this->modules = ($this->modules ?? collect())->concat($modules);
    }

    /**
     * フォームとテーブルに、リソースモジュールの処理を適用する
     *
     * @param string $resource リソースのクラス名
     * @param Form|Table $parent 適用前のフォームまたはテーブル
     * @return Form|Table 適用後のフォームまたはテーブル
     */
    public function apply(string $resource, Form|Table $parent): Form|Table
    {
        if ($this->modules == null) {
            return $parent;
        }
        $method = $parent instanceof Form ? 'form' : 'table';
        return $this->modules
            ->filter(fn($module) => $module::resource == $resource)
            ->sortBy(fn($module) => $module::order)
            ->reduce(fn($parent, $module) => $module::$method($parent), $parent);
    }
}
