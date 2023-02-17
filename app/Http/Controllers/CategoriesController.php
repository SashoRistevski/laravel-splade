<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use ProtoneMedia\Splade\SpladeTable;
use function Termwind\render;

class CategoriesController extends Controller
{
    public function index(){

        return view('categories.index', [
            'categories' => SpladeTable::for(Category::class)
                ->column('name')
                ->column('slug')
                ->column('created_at', canBeHidden: false, sortable: true)
                ->paginate(10),
        ]);
    }
}
