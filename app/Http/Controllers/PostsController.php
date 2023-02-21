<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Category;
use App\Models\Post;
use App\Tables\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PostsController extends Controller
{
    public function index()
    {
        return view('posts.index', ['posts' => Posts::class]);
    }

    public function create()
    {
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
