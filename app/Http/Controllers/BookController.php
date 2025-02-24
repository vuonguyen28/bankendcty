<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['category', 'publisher', 'authors'])->get();
        return response()->json($books);
    }
    public function showById($id)
    {
        $book = Book::with(['category', 'publisher', 'authors'])->find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404);
        }

        return response()->json($book);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'published_year' => 'required|integer|min:1900|max:' . date('Y'),
            'page_count' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'publisher_id' => 'required|exists:publishers,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        } else {
            $uploadedFileUrl = null;
        }

        // Tạo sách mới
        $book = Book::create([
            'title' => $request->title,
            'price' => $request->price,
            'published_year' => $request->published_year,
            'page_count' => $request->page_count,
            'category_id' => $request->category_id,
            'publisher_id' => $request->publisher_id,
            'description' => $request->description,
            'image_url' => $uploadedFileUrl,
        ]);

        return response()->json([
            'message' => 'Sách đã được thêm thành công!',
            'book' => $book
        ], 201);
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Book::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
    
        $product->update([
            'title' => $request->title,
            'price' => $request->price,
        ]);
    
        return response()->json([
            'message' => 'Sản phẩm đã được cập nhật thành công!',
            'product' => $product
        ], 200);
    }
    

   

 }