<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Book
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $slug
 * @property string $short_description
 * @property string|null $description
 * @property int $is_short
 * @property string|null $original_title
 * @property string|null $original_url
 * @property string|null $year
 * @property string|null $copyright_info
 * @property string|null $link_url
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property int $is_copyrighted
 * @property string|null $buy_urls
 * @property string|null $image
 * @property int $part
 * @property int $total_parts
 * @property int|null $firstpart_book_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\People|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $contents
 * @property-read int|null $contents_count
 * @method static \Database\Factories\BookFactory factory(...$parameters)
 * @method static Builder|Book newModelQuery()
 * @method static Builder|Book newQuery()
 * @method static Builder|Book published()
 * @method static Builder|Book query()
 * @method static Builder|Book whereAuthorId($value)
 * @method static Builder|Book whereBuyUrls($value)
 * @method static Builder|Book whereCopyrightInfo($value)
 * @method static Builder|Book whereCreatedAt($value)
 * @method static Builder|Book whereDescription($value)
 * @method static Builder|Book whereFirstpartBookId($value)
 * @method static Builder|Book whereId($value)
 * @method static Builder|Book whereImage($value)
 * @method static Builder|Book whereIsCopyrighted($value)
 * @method static Builder|Book whereIsShort($value)
 * @method static Builder|Book whereLinkUrl($value)
 * @method static Builder|Book whereOriginalTitle($value)
 * @method static Builder|Book whereOriginalUrl($value)
 * @method static Builder|Book wherePart($value)
 * @method static Builder|Book wherePublishedAt($value)
 * @method static Builder|Book whereShortDescription($value)
 * @method static Builder|Book whereSlug($value)
 * @method static Builder|Book whereTitle($value)
 * @method static Builder|Book whereTotalParts($value)
 * @method static Builder|Book whereUpdatedAt($value)
 * @method static Builder|Book whereYear($value)
 * @mixin \Eloquent
 */
class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $dates = ['published_at'];

    public function contents()
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    public function author()
    {
        return $this->belongsTo(People::class, 'author_id');
    }

    // -----

    public function scopePublished(Builder $query)
    {
        $query->whereNotNull('published_at');
    }

    // -----

    public function getTranslatorsNames()
    {
        return $this->contents->filter(function (Content $content) {
            if ($content->translator_name) {
                return true;
            }
        })->pluck('translator_name');
    }
}
