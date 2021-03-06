<?php

namespace App\Http\Controllers;

use App\Category;
use App\Image;
use App\Product;
use App\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with(['category', 'images'])->paginate( env( 'PAGINATION_COUNT' ) );
        $currenctCode = env('CURRENCY_CODE', 'SAR');
        return view('admin.products.products')->with([
            'products' => $products,
            'currency_code' => $currenctCode,
        ]);
    }

    public function newProduct( $id = null){

        $product = null;
        if( !is_null( $id ) ){
            $product = Product::with([
                'hasUnit',
                'category'
            ])->find( $id );
        }
        $units = Unit::all();
        $categories = Category::all();
        return view( 'admin.products.new-product' )->with([
            'product' => $product,
            'units' => $units,
            'categories' => $categories,
        ]);
    }

    public function delete( $id ){

    }

    public function update( Request $request ){

    }

    public function store(  Request $request ){
        $request -> validate([
            'product_title' => 'required',
            'product_description' => 'required',
            'product_unit' => 'required',
            'product_price' => 'required',
            'product_discount' => 'required',
            'product_total' => 'required',
            'product_category' => 'required'
        ]);

        $product = new Product();
        $product -> title = $request -> input('product_title');
        $product ->description = $request -> input('product_description');
        $product -> unit = intval($request -> input('product_unit'));
        $product -> price = doubleval($request -> input('product_price'));
        $product -> total = doubleval($request -> input('product_total'));
        $product -> category_id = intval($request -> input('product_category'));
        $product -> discount = doubleval($request -> input('product_discount'));

        if( $request -> has('options') ){
            $optionArray = [];
            $options = array_unique( $request -> input( 'options' ) );
            foreach ( $options as $option ){
                $actualOptions = $request -> input( $option );
                $optionArray[$option] = [];
                foreach ( $actualOptions as $actualOption ){
                    array_push( $optionArray[ $option ], $actualOption );
                }
            }

            $product -> options = json_encode( $optionArray );
        }

        $product -> save();
        if( $request -> hasFile('product_images') ){
            $images = $request -> file('product_images');
            foreach ($images as $image){
                $path = $image -> store('public');
                $image = new Image();
                $image -> url = $path;
                $image -> product_id = $product -> id;
                $image -> save();
            }
        }

        Session::flash('message', 'Product has been added');
        return redirect(route('products'));
    }




}
