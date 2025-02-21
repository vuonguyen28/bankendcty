<?php

namespace App\Http\Controllers;

use App\Models\BookAuthor;
use Illuminate\Http\Request;

class BookAuthorController extends Controller
{
    public function index()
    {
        $bookAuthors = BookAuthor::all();
        return response()->json($bookAuthors);
    }
}
