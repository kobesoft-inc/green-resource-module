<?php

namespace Green\ResourceModule;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Tables\Table;

class Module
{
    static ?string $resource = null;
    static int $order = 0;

    /**
     * リソースモジュールのフォームに対する処理を定義する
     *
     * @param Form $form 適用前のフォーム
     * @return Form 適用後のフォーム
     */
    public static function form(Form $form): Form
    {
        return $form;
    }

    /**
     * リソースモジュールのテーブル対する処理を定義する
     *
     * @param Table $table 適用前のテーブル
     * @return Table 適用後のテーブル
     */
    public static function table(Table $table): Table
    {
        return $table;
    }

    /**
     * コンポーネントの配列から、カラム名が一致するコンポーネントを検索する
     *
     * @param Component[] $components コンポーネントの配列
     * @param string $column カラム名
     * @param Closure $closure コンポーネントの検索処理
     * @return array 検索結果のコンポーネントの配列
     */
    public static function applyComponent(array $components, string $column, Closure $closure): array
    {
        foreach ($components as $index => $component) {
            if ($children = $component->getChildComponents()) {
                $component->childComponents(self::applyComponent($children, $column, $closure));
            }
            if ($component->getId() == $column) {
                return $closure($components, $index);
            }
        }
        return $components;
    }

    /**
     * 指定したカラムの後ろにコンポーネントを追加する
     *
     * @param Form|Table $parent 追加する対象のフォームかテーブル
     * @param string $column カラム名
     * @param mixed $components コンポーネントまたはコンポーネントの配列
     * @return Form|Table 追加後のフォームかテーブル
     */
    public static function columnAfter(Form|Table $parent, string $column, mixed $components): Form|Table
    {
        return self::columnBefore($parent, $column, $components, 1);
    }

    /**
     * 指定したカラムの前にコンポーネントを追加する
     *
     * @param Form|Table $parent 追加する対象のフォームかテーブル
     * @param string $column カラム名
     * @param mixed $components コンポーネントまたはコンポーネントの配列
     * @param integer $offset カラムの位置のオフセット
     * @return Form|Table 追加後のフォームかテーブル
     */
    public static function columnBefore(Form|Table $parent, string $column, mixed $components, int $offset = 0): Form|Table
    {
        if (!is_array($components)) {
            $components = [$components];
        }
        $parent->components(
            self::applyComponent(
                $parent->getComponents(),
                $column,
                fn($array, $index) => array_splice($array, $index + $offset, 0, $components)
            )
        );
        return $parent;
    }
}
