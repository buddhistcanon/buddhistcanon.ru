<?php

namespace App\Models;

use App\Data\SuttaNameData;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sutta
 *
 * @property int $id
 * @property string $name
 * @property string $category
 * @property int $order
 * @property string|null $suborder
 * @property string|null $title_pali
 * @property string|null $title_transcribe_ru
 * @property string|null $title_translate_ru
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $contents
 * @property-read int|null $contents_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta byIndexName($indexName)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta bySuttaName(\App\Data\SuttaNameData $suttaNameData)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereSuborder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereTitlePali($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereTitleTranscribeRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereTitleTranslateRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sutta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Sutta extends Model
{
    protected $table = 'suttas';
//    protected $appends = ['name'];

    public function contents()
    {
        return $this->morphMany(Content::class, 'contentable');
    }

    public function pali_content()
    {
        return $this->contents->first(function (Content $content) {
            return $content->lang === 'pali';
        });
    }

    public function scopeBySuttaName($query, SuttaNameData $suttaNameData)
    {
        $builder = $query
            ->where('category', $suttaNameData->category)
            ->where('order', $suttaNameData->order);
        if ($suttaNameData->suborder) {
            $builder = $builder->where('suborder', $suttaNameData->suborder);
        }

        return $builder;
    }

    public function displayIndexName(): string
    {
        $name = $this->category.'.'.$this->order;
        if (! is_null($this->suborder)) {
            $name .= '.'.$this->suborder;
        }

        return $name;
    }

    public function displayName(): string
    {
        $name = strtoupper($this->category).$this->order;
        if (! is_null($this->suborder)) {
            $name .= '.'.$this->suborder;
        }

        return $name;
    }

    public function displaySlug(): string
    {
        $slug = strtolower($this->category).$this->order;
        if (! is_null($this->suborder)) {
            $slug .= '.'.$this->suborder;
        }

        return '/'.$slug;
    }

    public function displayPaliTitle()
    {
        return $this->pali_content()->title;
    }

//    public function getNameAttribute()
//    {
//        return $this->displayName();
//    }
}
