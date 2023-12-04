<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Invite
 *
 * @property int $id
 * @property string $invite_symbols
 * @property string|null $note
 * @property int|null $registered_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereInviteSymbols($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereRegisteredUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invite whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Invite extends Model
{
    protected $table = 'invites';

    public function user()
    {
        return $this->belongsTo(User::class, 'registered_user_id');
    }
}
