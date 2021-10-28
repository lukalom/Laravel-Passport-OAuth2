<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens; // Add this line
use Illuminate\Auth\Authenticatable; 
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;
use App\Models\Book;

class Author extends Model implements AuthenticateContract
{
    use HasApiTokens, HasFactory, Authenticatable;

    public $timestamps = false;

    protected $fillable = [
        "name",
        "email",
        "password",
        "phone_no"
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
