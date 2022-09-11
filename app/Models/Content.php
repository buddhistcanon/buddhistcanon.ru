<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Content
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $subtitle
 * @property string $lang
 * @property string|null $translator_name
 * @property int|null $translator_id
 * @property string|null $link_url
 * @property string|null $description
 * @property string|null $table_of_contents
 * @property string $contentable_type
 * @property int $contentable_id
 * @property int $is_main
 * @property int $is_original
 * @property string $is_synced
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ContentChunk[] $chunks
 * @property-read int|null $chunks_count
 * @property-read Model|\Eloquent $contentable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExternalSource[] $external_sources
 * @property-read int|null $external_sources_count
 * @property-read \App\Models\People|null $translator
 *
 * @method static \Database\Factories\ContentFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Content newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Content query()
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereContentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereContentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereIsOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereIsSynced($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereLang($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereLinkUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereTableOfContents($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereTranslatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereTranslatorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Content whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Content extends Model
{
    use HasFactory;

    protected $table = 'contents';

    protected $fillable = ['lang', 'title'];

    public function contentable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function chunks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ContentChunk::class);
    }

    public function translator(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(People::class, 'translator_id');
    }

    public function external_sources()
    {
        return $this->morphMany(ExternalSource::class, 'externable');
    }

    public function displaySlug()
    {
        $slug = '/'.$this->lang;
        if (! $this->translator_id) {
            return $slug;
        }
        $slug .= '/'.$this->translator->slug;

        return $slug;
    }

    public function displayTranslatorName()
    {
        if (! $this->translator_id) {
            return '';
        }

        return $this->translator->displayNameRu();
    }
}
