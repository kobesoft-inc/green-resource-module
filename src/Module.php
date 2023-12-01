<?php

namespace Green\ResourceModule;

use Filament\Actions\ActionGroup;
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
     * @param ?string $before 挿入位置のIDまたは名前
     * @param array $insert 挿入するコンポーネントの配列
     * @return array 結果のコンポーネントの配列
     */
    private static function insertComponents(array $array, array $insert, ?string $before, ?string $after): array
    {
        // 挿入位置の指定をチェックする
        if (($before !== null) == ($after !== null)) {
            throw new RuntimeException('Either before or after must be specified.');
        }

        // 配列のインデックスを振り直す
        $array = array_values($array);

        // beforeの指定が*の場合は配列の先頭に追加する
        if ($before == '*') {
            return array_merge($insert, $array);
        }

        // afterの指定が*の場合は配列の最後に追加する
        if ($after == '*') {
            return array_merge($array, $insert);
        }

        $name = $before ?? $after;
        $offset = $before !== null ? 0 : 1;
        foreach ($array as $i => $component) {
            // ActionGroupの場合の再帰的な処理
            if ($component instanceof ActionGroup) {
                if ($children = $component->getActions()) {
                    $component->actions(self::insertComponents($children, $insert, $before, $after));
                }
            }

            // Componentの場合の再帰的な処理
            if ($component instanceof Component) {
                if ($children = $component->getChildComponents()) {
                    $component->childComponents(self::insertComponents($children, $insert, $before, $after));
                }
            }

            // 挿入位置のコンポーネントの前に挿入する
            if (method_exists($component, 'getName') && $component->getName() == $name) {
                array_splice($array, $i + $offset, 0, $insert);
                return $array;
            }
        }
        return $array;
    }

    /**
     * テーブルのカラムにコンポーネントを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param Column[] $columns 追加するカラムの配列
     * @param string|null $before 挿入位置のカラム名
     * @param string|null $after
     * @return Table 追加後のテーブル
     */
    public static function addTableColumns(Table $table, array $columns, string $before = null, string $after = null): Table
    {
        return $table->columns(self::insertComponents($table->getColumns(), $columns, $before, $after));
    }

    /**
     * テーブルにフィルターを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param array $filters 追加するフィルターの配列
     * @param string|null $before 挿入位置のフィルター名
     * @param string|null $after
     * @return Table 追加後のテーブル
     */
    public static function addTableFilters(Table $table, array $filters, string $before = null, string $after = null): Table
    {
        return $table->filters(self::insertComponents($table->getFilters(), $filters, $before, $after));
    }

    /**
     * テーブルにアクションを追加する
     *
     * @param Table $table 追加する対象のテーブル
     * @param Action[] $actions 追加するアクションの配列
     * @param string|null $before 挿入位置のアクション名
     * @param string|null $after
     * @return Table 追加後のテーブル
     */
    public static function addTableActions(Table $table, array $actions, string $before = null, string $after = null): Table
    {
        return $table->actions(self::insertComponents($table->getActions(), $actions, $before, $after));
    }

    /**
     * フォームにコンポーネントを追加する
     *
     * @param Form $form 追加する対象のフォーム
     * @param Component[] $components 追加するコンポーネントの配列
     * @param string|null $before 挿入位置のコンポーネント名
     * @param string|null $after
     * @return Form 追加後のフォームかテーブル
     */
    public static function addFormSchema(Form $form, array $components, string $before = null, string $after = null): Form
    {
        return $form->components(self::insertComponents($form->getComponents(true), $components, $before, $after));
    }
}
