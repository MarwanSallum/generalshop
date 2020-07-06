<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

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
}
