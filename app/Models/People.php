<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\People
 *
 * @property int $id
 * @property string|null $fullname_ru
 * @property string|null $monkname_ru
 * @property string|null $fullname_en
 * @property string|null $monkname_en
 * @property string|null $nickname
 * @property string $slug
 * @property int $is_monk
 * @property int $priority
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Book[] $books
 * @property-read int|null $books_count
 * @method static Builder|People monk()
 * @method static Builder|People newModelQuery()
 * @method static Builder|People newQuery()
 * @method static Builder|People query()
 * @method static Builder|People whereCreatedAt($value)
 * @method static Builder|People whereDescription($value)
 * @method static Builder|People whereFullnameEn($value)
 * @method static Builder|People whereFullnameRu($value)
 * @method static Builder|People whereId($value)
 * @method static Builder|People whereIsMonk($value)
 * @method static Builder|People whereMonknameEn($value)
 * @method static Builder|People whereMonknameRu($value)
 * @method static Builder|People whereNickname($value)
 * @method static Builder|People wherePriority($value)
 * @method static Builder|People whereSlug($value)
 * @method static Builder|People whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class People extends Model
{
    protected $table = "peoples";

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, "author_id");
    }

    // ------

    public function scopeMonk(Builder $query): void
    {
        $query->where("is_monk", 1);
    }

    public function displayNameRu(): String
    {
    	if($this->is_monk){
            return $this->monkname_ru;
        }

        return $this->fullname_ru. ($this->nickname ? " (".$this->nickname.")" : "");
    }
}
