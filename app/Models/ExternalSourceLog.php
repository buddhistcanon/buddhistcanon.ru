<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ExternalSourceLog
 *
 * @property int $id
 * @property int $external_source_id
 * @property string|null $old_content
 * @property string|null $new_content
 * @property string|null $processed_at
 * @property-read \App\Models\ExternalSource|null $source
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog whereExternalSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog whereNewContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog whereOldContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExternalSourceLog whereProcessedAt($value)
 * @mixin \Eloquent
 */
class ExternalSourceLog extends Model
{
    protected $table = 'external_source_logs';

    public function source()
    {
        return $this->belongsTo(ExternalSource::class, 'external_source_id');
    }
}
