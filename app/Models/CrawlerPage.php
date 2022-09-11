<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\CrawlerPage
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $project_id
 * @property string $url
 * @property string $status
 * @property string|null $content
 * @property string|null $content_hash
 * @property \Illuminate\Support\Carbon|null $hashed_at
 * @property string|null $old_content
 * @property int $session
 * @property \Illuminate\Support\Carbon|null $checked_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage newQuery()
 * @method static \Illuminate\Database\Query\Builder|CrawlerPage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereContentHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereHashedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereOldContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerPage whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|CrawlerPage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|CrawlerPage withoutTrashed()
 * @mixin \Eloquent
 */
class CrawlerPage extends Model
{
    use SoftDeletes;

    protected $table = 'crawler_pages';

    protected $fillable = ['url'];

    protected $dates = ['hashed_at', 'checked_at'];
}
