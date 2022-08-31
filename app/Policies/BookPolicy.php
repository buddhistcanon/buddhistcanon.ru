<?php

namespace App\Policies;

use App\Models\Book;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any books.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the book.
     *
     * @param User $user
     * @param Book $book
     * @return bool
     */
    public function view(?User $user, Book $book)
    {
        if($book->is_copyrighted === 0) return true;

        $access = $user->user_book_access()->where("book_id", $book->id)->first();
        if($access AND $access->is_allow === 1) return true;

        return false;
    }

    /**
     * Determine whether the user can create books.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the book.
     *
     * @param User $user
     * @param Book $book
     * @return bool
     */
    public function update(User $user, Book $book)
    {
        //
    }

    /**
     * Determine whether the user can delete the book.
     *
     * @param User $user
     * @param Book $book
     * @return bool
     */
    public function delete(User $user, Book $book)
    {
        //
    }

    /**
     * Determine whether the user can restore the book.
     *
     * @param User $user
     * @param Book $book
     * @return bool
     */
    public function restore(User $user, Book $book)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the book.
     *
     * @param User $user
     * @param Book $book
     * @return bool
     */
    public function forceDelete(User $user, Book $book)
    {
        //
    }
}
