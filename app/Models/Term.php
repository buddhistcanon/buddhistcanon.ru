<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Scout\Searchable;
use Str;

/**
 * App\Models\Term
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $short_text
 * @property string|null $parts_text
 * @property int|null $parent_term_id
 * @property string|null $text
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TermVariant|null $term
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TermVariant> $variants
 * @property-read int|null $variants_count
 *
 * @method static \Database\Factories\TermFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Term newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Term newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Term query()
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereParentTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term wherePartsText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereShortText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereUpdatedBy($value)
 *
 * @mixin \Eloquent
 */
class Term extends Model
{
    use HasFactory;

    // -----
    use Searchable;

    protected $table = 'terms';

    public function variants(): HasMany
    {
        return $this->hasMany(TermVariant::class);
    }

    public function term(): HasOne
    {
        return $this->hasOne(TermVariant::class, 'term_id', 'term_id')->where('is_main', 1);
    }

    // -----

    public function setTitleAndAutoSlug($title)
    {
        $this->title = $title;
        $this->slug = Str::slug($title);
    }

    public function toSearchableArray()
    {
        return [
            'content_id' => $this->content_id,
            'text' => $this->text,
        ];
    }
}
