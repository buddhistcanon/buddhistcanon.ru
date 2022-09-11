<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TermProposal
 *
 * @property int $id
 * @property string $title
 * @property int|null $content_chunk_id
 * @property int|null $term_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal whereContentChunkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal whereTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TermProposal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TermProposal extends Model
{
    protected $table = 'term_proposals';
}
