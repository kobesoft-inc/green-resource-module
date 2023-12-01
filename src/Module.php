<?php

namespace Green\ResourceModule;

use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
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
     * @param mixed $array コンポーネントの配列
     * @param ?string $before 挿入位置のID(nullなら先頭のコンポーネントを検索)
     * @param array $insert 挿入するコンポーネントの配列
     * @return array 検索結果のコンポーネントの配列
     */
    private static function insertBefore(array $array, ?string $before, array $insert): array
    {
        foreach ($array as $index => $component) {
            if ($component instanceof Component) {
                if ($children = $component->getChildComponents()) {
                    $component->childComponents(self::insertBefore($children, $before, $insert));
                }
            }
            if ($before == null ||
                (method_exists($component, 'getId') && $component->getId() == $before) ||
                (method_exists($component, 'getName') && $component->getName() == $before)) {
                return array_splice($array, $index, $insert);
            }
        }
        return $array;
    }

    /**
     * テーブルのカラムにコンポーネントを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param string|null $before 挿入位置のカラム名
     * @param Column[] $columns 追加するカラムの配列
     * @return Table 追加後のテーブル
     */
    public static function tableColumns(Table $table, ?string $before, array $columns): Table
    {
        return $table->columns(self::insertBefore($table->getColumns(), $before, $columns));
    }

    /**
     * テーブルにフィルターを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param string|null $before 挿入位置のフィルター名
     * @param array $filters 追加するフィルターの配列
     * @return Table 追加後のテーブル
     */
    public static function tableFilters(Table $table, ?string $before, array $filters): Table
    {
        return $table->filters(self::insertBefore($table->getFilters(), $before, $filters));
    }

    /**
     * テーブルにアクションを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param string|null $before 挿入位置のアクション名
     * @param Action[] $actions 追加するアクションの配列
     * @return Table 追加後のテーブル
     */
    public static function tableActions(Table $table, ?string $before, array $actions): Table
    {
        return $table->actions(self::insertBefore($table->getActions(), $before, $actions));
    }

    /**
     * フォームにコンポーネントを追加する
     *
     * @param Form $form 追加する対象のフォーム
     * @param string|null $before 挿入位置のコンポーネント名
     * @param Component[] $components 追加するコンポーネントの配列
     * @return Form 追加後のフォームかテーブル
     */
    public static function formComponents(Form $form, ?string $before, array $components): Form
    {
        return $form->components(self::insertBefore($form->getComponents(), $before, $components));
    }
}
