<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\Facades\Toast;
use ProtoneMedia\Splade\SpladeTable;
use function Termwind\render;

class CategoriesController extends Controller
{
    public function index() {

        return view('categories.index', [
            'categories' => SpladeTable::for(Category::class)
                ->withGlobalSearch('Search through the data...', ['name'])
                ->column('name',canBeHidden: false, sortable: true)
                ->column('slug')
                ->column('created_at', canBeHidden: false, sortable: true)
                ->column('action', canBeHidden: false)
                ->paginate()
        ]);
    }

    public function create() {


        return view('categories.create');
    }

    public function store(CategoryStoreRequest $request)
    {
        Category::create($request->validated());

        Toast::title('New Category created!')
            ->centerTop()
            ->autoDismiss(3);

        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
            return view('categories.edit', compact('category'));
    }
    public function update(CategoryStoreRequest $request, Category $category)
    {
            $category->update($request->validated());
        Toast::title('Category Updated!')
            ->centerTop()
            ->autoDismiss(3);
        return redirect()->route('categories.index');
    }




}
