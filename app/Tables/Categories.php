<?php

namespace App\Tables;

use App\Models\Category;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Table\LaravelExcelException;


class Categories extends AbstractTable
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the user is authorized to perform bulk actions and exports.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Category::query();
    }

    /**
     * Configure the given SpladeTable.
     *
     * @param SpladeTable $table
     * @return void
     * @throws LaravelExcelException
     */
    public function configure(SpladeTable $table): void
    {
        $table
            ->withGlobalSearch(columns: ['name'])
            ->column('id', sortable: true)
            ->column('name', canBeHidden: false, sortable: true)
            ->column('slug')
            ->column('created_at', canBeHidden: false, sortable: true)
            ->column('updated_at', canBeHidden: false, sortable: true)
            ->column('action', canBeHidden: false, exportAs: false)
            ->bulkAction(
                label: 'Touch timestamp',
                each: fn(Category $category) => $category->touch(),
                before: fn() => info('Touching the selected projects'),
                after: fn() => Toast::info('Timestamps updated!')
            )
            ->bulkAction(
                label: 'Delete category',
                each: fn(Category $category) => $category->delete(),
                after: fn() => Toast::info('Categories Deleted')
            )
            ->export('Categories Excel')
            ->paginate(5);

        // ->searchInput()
        // ->selectFilter()
        // ->withGlobalSearch()

        // ->bulkAction()
        // ->export()
    }
}
