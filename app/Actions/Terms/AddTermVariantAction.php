<?php

namespace App\Actions\Terms;

use App\Models\Term;
use App\Models\TermVariant;
use Lorisleiva\Actions\Concerns\AsAction;

class AddTermVariantAction
{
    use AsAction;

    /**
     * Добавить синоним к термину
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function handle(Term $term, string $text): TermVariant
    {
        $termVariant = TermVariant::query()->where('title', $text)->first();
        if (! $termVariant) {
            $termVariant = new TermVariant();
            $termVariant->term_id = $term->id;
            $termVariant->title = $text;
            $termVariant->is_main = 0;
            $termVariant->save();
        }

        return $termVariant;
    }
}
