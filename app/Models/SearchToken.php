<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SearchToken
 *
 * @property int $id
 * @property string $token
 * @property int $is_active
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SearchToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SearchToken extends Model
{
    protected $table = 'search_tokens';
}
