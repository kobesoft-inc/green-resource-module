<?php

namespace Green\ResourceModule;

use Filament\Forms\Components\Component;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use RuntimeException;

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
     * コンポーネントを挿入する
     *
     * @param mixed $array 対象のコンポーネントの配列
     * @param ?string $before 挿入位置のIDまたは名前(nullなら最後に追加)
     * @param array $insert 挿入するコンポーネントの配列
     * @return array 結果のコンポーネントの配列
     */
    private static function insertComponents(array $array, ?string $before, array $insert): array
    {
        if ($before == null) {
            return $array + $insert;
        }
        foreach ($array as $index => $component) {
            if ($component instanceof Component) {
                if ($children = $component->getChildComponents()) {
                    $component->childComponents(self::insertComponents($children, $before, $insert));
                }
            }
            if ($component->getName() == $before) {
                array_splice($array, $index, $insert);
                return $array;
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
    public static function addTableColumns(Table $table, ?string $before, array $columns): Table
    {
        return $table->columns(self::insertComponents($table->getColumns(), $before, $columns));
    }

    /**
     * テーブルにフィルターを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param string|null $before 挿入位置のフィルター名
     * @param array $filters 追加するフィルターの配列
     * @return Table 追加後のテーブル
     */
    public static function addTableFilters(Table $table, ?string $before, array $filters): Table
    {
        return $table->filters(self::insertComponents($table->getFilters(), $before, $filters));
    }

    /**
     * テーブルにアクションを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param string|null $before 挿入位置のアクション名
     * @param Action[] $actions 追加するアクションの配列
     * @return Table 追加後のテーブル
     */
    public static function addTableActions(Table $table, ?string $before, array $actions): Table
    {
        return $table->actions(self::insertComponents($table->getActions(), $before, $actions));
    }

    /**
     * フォームにコンポーネントを追加する
     *
     * @param Form $form 追加する対象のフォーム
     * @param string|null $before 挿入位置のコンポーネント名
     * @param Component[] $components 追加するコンポーネントの配列
     * @return Form 追加後のフォームかテーブル
     */
    public static function addFormComponents(Form $form, ?string $before, array $components): Form
    {
        return $form->components(self::insertComponents($form->getComponents(true), $before, $components));
    }
}
