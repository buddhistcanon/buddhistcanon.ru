<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExternalSource
 *
 * @property int $id
 * @property string $url
 * @property string|null $name
 * @property string $externable_type
 * @property int $externable_id
 * @property string|null $content
 * @property string|null $content_hash
 * @property string|null $hashed_at
 * @property string|null $checked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $externable
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExternalSourceLog[] $logs
 * @property-read int|null $logs_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereContentHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereExternableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereExternableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereHashedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSource whereUrl($value)
 * @mixin \Eloquent
 */
class ExternalSource extends Model
{
    protected $table = 'external_sources';

    public function externable()
    {
        return $this->morphTo();
    }

    public function logs()
    {
        return $this->hasMany(ExternalSourceLog::class)->orderBy('id', 'asc');
    }
}
