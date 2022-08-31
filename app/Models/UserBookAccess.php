<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserBookAccess
 *
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property int $is_allow
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess whereIsAllow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserBookAccess whereUserId($value)
 * @mixin \Eloquent
 */
class UserBookAccess extends Model
{
    protected $table = "user_book_access";
}
