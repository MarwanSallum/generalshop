<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::paginate(env( 'PAGINATION_COUNT' ));
        return view('admin.categories.categories')->with([
            'categories' => $categories,
            'showLinks' => true,
        ]);
    }

    private function categoryNameExists( $categoryName ){
        $category = Category::where(
            'name', '=', $categoryName
        )->get();
        if( count( $category ) > 0 ){
            return true;
        }
        return false;
    }

    public function store( Request $request){
        $request -> validate([
            'category_name' => 'required'
        ]);
        $categoryName = $request -> input( 'category_name' );
        if( $this->categoryNameExists( $categoryName ) ){
            Session::flash( 'message', 'Category name already exists' );
            return back();
        }
        $category = new Category();
        $category -> name = $categoryName;
        $category -> save();
        Session::flash( 'message' , 'Category hass been added' );
        return back();

    }

    public function update(  Request $request){
        $request -> validate([
            'category_id'  => 'required',
            'category_name'  => 'required'
        ]);
        $categoryName = $request -> input( 'category_name' );
        $categoryId = $request -> input( 'category_id' );
        if( $this -> categoryNameExists( $categoryName ) ){
            Session::flash( 'message', 'Category name already exists' );
            return back();
        }
        $category = Category::find( $categoryId );
        $category->name = $categoryName;
        $category->save();
        Session::flash( 'message' , 'Category has been updated' );
        return back();
    }

    public function delete( Request $request ){
        $request ->validate([
            'category_id' => 'required'
        ]);
        $categoryID = $request -> input('category_id');
        Category::destroy( $categoryID );
        Session::flash( 'message', 'Category has been deleted' );
        return back();
    }

    public function search( Request $request ){
        $request ->validate([
            'category_search' => 'required'
        ]);
        $searchTerm = $request -> input( 'category_search' );

        $categories = Category::where(
            'name', 'LIKE', '%' . $searchTerm . '%'
        )->get();
        if( count( $categories ) > 0 ){
            return view( 'admin.categories.categories' )->with([
                'categories' => $categories,
                'showLinks' => false,
            ]);
        }
        Session::flash ( 'message', 'Nothing Found!!!' );
        return redirect()->back();
    }
}
