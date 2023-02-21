<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostsController extends Controller
{
    public function index() {

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
            ->allowedSorts(['title', 'slug'])
            ->allowedFilters(['title', 'slug','category_id', $globalSearch]);

            $categories = Category::pluck('name','id')->toArray();

        return view('posts.index',[
            'posts' => SpladeTable::for($posts)
                ->withGlobalSearch('Search through the data...', ['title'])
                ->column('title',canBeHidden: false, sortable: true)
                ->column('slug', sortable: true)
                ->column('description', canBeHidden: true)
                ->column('action', canBeHidden: false)
                ->selectFilter('category_id', $categories)
                ->paginate()
        ]);
    }

    public function create(){
        $categories = Category::pluck('name', 'id')->toArray();
        return view('posts.create', compact('categories'));
    }

    public function store(PostStoreRequest $request)
    {
        Post::create($request->validated());
        Toast::title('New Post created!')
            ->centerTop()
            ->autoDismiss(3);

        return to_route('posts.index');
    }

    public function edit(Post $post)
    {
        $categories = Category::pluck('name', 'id')->toArray();

        return view('posts.edit', compact('categories', 'post'));
    }
    public function update(PostStoreRequest $request, Post $post)
    {

        $post->update($request->validated());
        Toast::title('Post Updated!')
            ->centerTop()
            ->autoDismiss(3);
        return to_route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        Toast::title('Post Deleted!')
            ->centerTop()
            ->autoDismiss(3);
        return redirect()->back();
    }
}
