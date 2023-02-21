<?php

namespace App\Tables;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\AbstractTable;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use ProtoneMedia\Splade\Table\LaravelExcelException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class Posts extends AbstractTable
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
     * Configure the given SpladeTable.
     *
     * @param SpladeTable $table
     * @return Application|Factory|View
     * @throws LaravelExcelException
     */
    public function configure(SpladeTable $table)
    {
        $globalSearch = AllowedFilter::callback('global', function ($query, $value) {
            $query->where(function ($query) use ($value) {
                Collection::wrap($value)->each(function ($value) use ($query) {
                    $query
                        ->orWhere('title', 'LIKE', "%{$value}%")
                        ->orWhere('slug', 'LIKE', "%{$value}%");
                });
            });
        });
        $posts = QueryBuilder::for(Post::class)
            ->defaultSort('title')
            ->allowedSorts(['title', 'slug',])
            ->allowedFilters(['title', 'slug', 'category_id', $globalSearch]);

        $categories = Category::pluck('name', 'id')->toArray();
        $table
            ->column('id')
            ->withGlobalSearch(columns: ['title'])
            ->column('title', canBeHidden: false, sortable: true)
            ->column('slug', sortable: true)
            ->column('description', canBeHidden: true)
            ->column('created_at', canBeHidden: false, sortable: true)
            ->column('updated_at', canBeHidden: false, sortable: true)
            ->column('action', canBeHidden: false, exportAs: false)
            ->selectFilter('category_id', $categories)
            ->bulkAction(
                label: 'Touch timestamp',
                each: fn(Post $post) => $post->touch(),
                after: fn() => Toast::info('Timestamps updated!')
            )
            ->bulkAction(
                label: 'Delete Post',
                each: fn(Post $post) => $post->delete(),
                after: fn() => Toast::info('Posts Deleted')
            )
            ->export('Posts Excel')
            ->paginate();
    }

    /**
     * The resource or query builder.
     *
     * @return mixed
     */
    public function for()
    {
        return Post::query();
    }
}
