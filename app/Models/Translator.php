<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Translator
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $name
 * @property string|null $nickname
 * @property string $slug
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Translator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Translator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Translator whereUserId($value)
 * @mixin \Eloquent
 */
class Translator extends Model
{
    protected $table = "translators";
}
