<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Models\ContentChunk
 *
 * @property int $id
 * @property int $content_id
 * @property string $chunkable_type
 * @property int $chunkable_id
 * @property int $order
 * @property string|null $text
 * @property string|null $mark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Content|null $content
 *
 * @method static \Database\Factories\ContentChunkFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk query()
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereChunkableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereChunkableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereContentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ContentChunk whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ContentChunk extends Model
{
    use HasFactory;

    // настройка поиска
    use Searchable;

    protected $table = 'content_chunks';

    protected $fillable = ['order', 'mark', 'text'];

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'content_id' => $this->content_id,
            'chunkable_type' => $this->chunkable_type,
            'chunkable_id' => $this->chunkable_id,
            'text' => $this->text,
            'mark' => $this->mark,
        ];
    }
}
