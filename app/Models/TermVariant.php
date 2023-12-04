<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TermVariant
 *
 * @property int $id
 * @property int $term_id
 * @property string $title
 * @property int $is_main
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Term|null $description
 * @property-read \App\Models\Term|null $term
 *
 * @method static \Database\Factories\TermVariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant whereTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermVariant whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class TermVariant extends Model
{
    use HasFactory;

    protected $table = 'term_variants';

    public function description()
    {
        return $this->belongsTo(Term::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
