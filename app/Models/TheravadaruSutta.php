<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TheravadaruSutta
 *
 * @property int $id
 * @property string $category_name
 * @property string $order
 * @property string|null $suborder
 * @property string $name
 * @property string $url
 * @property string|null $copyright
 * @property string $content
 * @property string $original_html
 * @property int $need_attention
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta byIndexName($indexName)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta query()
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereCategoryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereCopyright($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereNeedAttention($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereOriginalHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereSuborder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TheravadaruSutta whereUrl($value)
 * @mixin \Eloquent
 */
class TheravadaruSutta extends Model
{
    protected $table = 'theravadaru_suttas';

    public function scopeByIndexName($query, $indexName)
    {
        $array = explode('.', $indexName);
        $builder = $query->where('category_name', $array[0])->where('order', $array[1]);
        if (isset($array[2])) {
            $builder = $builder->where('suborder', $array[2]);
        }

        return $builder;
    }

    public function displayIndexName()
    {
        $name = $this->category_name.'.'.$this->order;
        if (! is_null($this->suborder)) {
            $name .= '.'.$this->suborder;
        }

        return $name;
    }

    public function displayShortName()
    {
        $name = $this->category_name.' '.$this->order;
        if (! is_null($this->suborder)) {
            $name .= '.'.$this->suborder;
        }

        return $name;
    }

    public function displayContentForEdit()
    {
        $content = str_replace("\n", '<br>', $this->content);

        return $content;
    }

    public function displayOriginalContent()
    {
        $content = str_replace('60%', '100%', $this->original_html);

        return $content;
    }
}
