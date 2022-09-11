<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Author
 *
 * @property int $id
 * @property string $short_name
 * @property string $full_name
 * @property string $nickname
 * @property string $slug
 * @property string|null $description
 * @property int $priority
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read int|null $books_count
 *
 * @method static \Database\Factories\AuthorFactory factory(...$parameters)
 * @method static Builder|Author monk()
 * @method static Builder|Author newModelQuery()
 * @method static Builder|Author newQuery()
 * @method static Builder|Author query()
 * @method static Builder|Author whereCreatedAt($value)
 * @method static Builder|Author whereDescription($value)
 * @method static Builder|Author whereFullName($value)
 * @method static Builder|Author whereId($value)
 * @method static Builder|Author whereNickname($value)
 * @method static Builder|Author wherePriority($value)
 * @method static Builder|Author whereShortName($value)
 * @method static Builder|Author whereSlug($value)
 * @method static Builder|Author whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Author extends Model
{
    use HasFactory;

    protected $table = 'authors';

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // ------

    public function scopeMonk(Builder $query)
    {
        $query->where('is_monk', 1);
    }
}
