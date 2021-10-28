<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{

    // CREATE Book - POST
    public function createBook(Request $request)
    {
        // validation
        $request->validate([
            "title" => "required",
            "book_cost" => "required",
        ]);

        // create book data
        $book = new Book();

        $book->author_id = auth()->user()->id;
        $book->title = $request->title;
        $book->description = $request->description;
        $book->book_cost = $request->book_cost;

        //save + send response
        $book->save();

        return response()->json([
            "status" => 1,
            "message" => "Book created successfuly",
        ]);
    }

    // LIST - GET - without middleware
    public function listBook()
    {
        $books = Book::get();

        return response()->json([
            "status" => 1,
            "message" => "All blooks",
            "data" => $books,
        ]);
    }

    // Author Book - GET
    public function authorBook()
    {
        $author_id = auth()->user()->id;
        $books = Author::find($author_id)->books;

        return response()->json([
            "status" => 1,
            "message" => "Author blooks",
            "data" => $books,
        ]);
    }

    // SINGLE BOOK - GET
    public function singleBook($book_id)
    {
        $author_id = auth()->user()->id;
        $book = Book::where(["id" => $book_id, "author_id" => $author_id]);

        if (!$book->exists()) {
            return response()->json([
                "status" => false,
                "message" => "Author Book doesn't exists",
            ]);
        }

        return response()->json([
            "status" => 1,
            "message" => "Book data found",
            "data" => $book->first(),
        ]);
    }

    // UPDATE - POST
    public function updateBook(Request $request, $book_id)
    {
        $author_id = auth()->user()->id;
        if (Book::where([
            "author_id" => $author_id,
            "id" => $book_id,
        ])->exists()) {
            $book = Book::find($book_id);

            $book->title = isset($request->title) ? $request->title : $book->title;
            $book->description = isset($request->description) ? $request->description : $book->description;
            $book->book_cost = isset($request->book_cost) ? $request->book_cost : $book->book_cost;

            $book->save();

            return response()->json([
                "status" => 1,
                "message" => "Book data has been updated",
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Author Book doesn't exists",
            ]);
        }
    }

    // DELETE - GET
    public function deleteBook($book_id)
    {
        $author_id = auth()->user()->id;

        if (Book::where([
            "author_id" => $author_id,
            "id" => $book_id,
        ])->exists()) {
            $book = Book::find($book_id);

            $book->delete();
            
            return response()->json([
                "status" => true,
                "message" => "Book has been deleted"
            ]);

        } else {
            return response()->json([
                "status" => false,
                "message" => "Author Book doesn't exists",
            ]);
        }
    }
}
