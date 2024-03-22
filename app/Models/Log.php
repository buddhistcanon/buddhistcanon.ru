<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $session
 * @property int|null $user_id
 * @property string|null $ip
 * @property string $action
 * @property int|null $sutta_id
 * @property int|null $term_id
 * @property int|null $content_id
 * @property int|null $chunk_id
 * @property string|null $storage
 * @property string|null $before
 * @property string|null $after
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereChunkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereStorage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereSuttaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Log whereUserId($value)
 *
 * @mixin \Eloquent
 */
class Log extends Model
{
    protected $table = 'logs';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sutta()
    {
        return $this->belongsTo(Sutta::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}
