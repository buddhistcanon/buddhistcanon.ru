<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CrawlerProject
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $root_url
 * @property string $start_url
 * @property array|null $exclude_patterns
 * @property int $session
 * @property int $deep
 * @property string|null $crawled_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CrawlerPage[] $pages
 * @property-read int|null $pages_count
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereCrawledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereDeep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereExcludePatterns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereRootUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereStartUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CrawlerProject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CrawlerProject extends Model
{
    protected $table = "crawler_projects";

    protected $casts = [
        'start_pages' => 'array',
        'exclude_patterns' => 'array',
    ];

    public function pages()
    {
    	return $this->hasMany(CrawlerPage::class, "project_id");
    }
}
