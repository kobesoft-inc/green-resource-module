# green-resource-module

Copyright &copy; Kobesoft, Inc. All rights reserved.

## 概要

FilamentPHPのResource定義を、別のファイルに分離するためのライブラリです。
プラグインでResource定義をして、アプリケーションで拡張をする用途を想定しています。
下記の機能を実装しています。

- テーブルとフォームの拡張
- テーブルとフォームを拡張する際のメソッド

## 導入方法

composerでインストール

```shell
composer install kobesoft/greem-resource-module
```

## Resourceへの組み込み

利用する側のResourceで、ResourceRegistryに記録されたモジュールの処理を呼び出す。

```php
class ExampleResource extends Resource
{
    public function form(Form $form): Form
    {
        $form = $form->schema([
            :
            :
        ]);
        return \Green\ResourceModule\Facades\ModuleRegistry::form($form);
    }
    
    public function table(Table $table): Table
    {
        $table = v->schema([
            :
            :
        ]);
        return \Green\ResourceModule\Facades\ModuleRegistry::table($table);
    }
}
```

## モジュールの定義

下記のようにモジュールを定義します。

```php
class ExtensionModule extends \Green\ResourceModule\Module
{
    public function form(Form $form): Form
    {
        return $form
            ->addSchema([
                :
                :
            ], before: 'name');
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->addColumns([
                :
                :
            ], before: 'name');
    }
}
```

## モジュールの登録

モジュールを登録するには、下記のようにします。

```php
\Green\ResourceModule\ModuleRegistry::register([
    ExtensionModule::class
]);
```

## スキーマの編集に役立つMarcoableなメソッド

```php
// フォームにコンポーネントを追加する。
// 引数beforeかafterで指定したカラムの前後に追加される。
$form
    ->addSchema([
        :
        :
    ], before: 'name')

// テーブルにカラムを追加する。
// 引数beforeかafterで指定したカラムの前後に追加される。
$table
    ->addColumns([
        :
        :
    ], after: 'name')

// テーブルにアクションを追加する。
// 引数beforeかafterで指定したアクションの前後に追加される。
$table
    ->addActions([
        :
        :
    ], after: 'name')

// テーブルにフィルタを追加する。
// 引数beforeかafterで指定したフィルタの前後に追加される。
$table
    ->addFilters([
        :
        :
    ], after: 'name')
```


