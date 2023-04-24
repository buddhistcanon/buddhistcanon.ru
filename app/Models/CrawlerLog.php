<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CrawlerLog
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $project_id
 * @property string $url
 * @property string|null $old_content
 * @property string $new_content
 * @property string|null $processed_at
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereNewContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereOldContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerLog whereUrl($value)
 * @mixin \Eloquent
 */
class CrawlerLog extends Model
{
    protected $table = 'crawler_logs';

    protected $fillable = ['project_id', 'old_content', 'new_content', 'url'];
}
