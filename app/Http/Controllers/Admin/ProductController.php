<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Models\Category;
use App\Services\ProductService;
use App\Models\Role;

// use App\Models\Role;

class ProductController extends Controller
{

    protected Category $category;
    protected ProductService $productService;

    public function __construct(Category $category, ProductService $productService)
    {
        $this->category = $category;
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productService->getWithPaginate();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category->get(['id', 'name']);

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $this->productService->store($request);
        return redirect()->route('products.index')->with(['message' => 'create product success']);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productService->findOrFail($id)->load(['details', 'categories']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $product = $this->productService->findOrFail($id)->load(['details', 'categories']);

        $categories = $this->category->get(['id', 'name']);

        return view('admin.products.edit', compact('categories', 'product'));
    }   // public function edit($id)
    // {
    //     // Tìm kiếm sản phẩm dựa trên ID và tải các chi tiết và danh mục của sản phẩm
    //     $product = $this->productService->findOrFail($id)->load(['details', 'categories']);

    //     // Lấy danh sách các danh mục sản phẩm
    //     $categories = $this->category->get(['id', 'name']);

    //     // Trả về view 'admin.products.edit' kèm theo các biến compact categories và product
    //     return view('admin.products.edit', compact('categories', 'product'));
    // }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $this->productService->update($request, $id);
        return redirect()->route('products.index')->with(['message' => 'Update product success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->productService->delete($id);
        return redirect()->route('products.index')->with(['message' => 'Delete product success']);

    }
}
