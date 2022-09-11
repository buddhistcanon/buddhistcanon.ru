<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EmailSubscribe
 *
 * @property int $id
 * @property string $email
 * @property string|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe query()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscribe whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EmailSubscribe extends Model
{
    protected $table = 'email_subscribes';
}
