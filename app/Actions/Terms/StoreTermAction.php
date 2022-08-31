<?php namespace App\Actions\Terms;
use App\Data\TermData;
use App\Models\Term;
use App\Models\TermVariant;
use DB;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTermAction
{
    use AsAction;

    public function __construct(){

    }

    public function handle(TermData $termData): Term
    {
        $term = new Term();
        if($termData->id){
            $term = Term::query()
                ->where("id", $termData->id)
                ->firstOrFail();
            // TODO $newTermData и логирование изменений
        }

        $term->title = $termData->title;
        $term->slug = $termData->slug;
        $term->short_text = $termData->short_text;
        $term->parts_text = $termData->parts_text;
        $term->text = $termData->text;
        $term->save();

        // Синонимы
        $existsVariants = TermVariant::query()
            ->where("term_id", $term->id)
            ->get();

        // Добавление новых
        foreach($termData->variants as $stringVariant){
            if($existsVariants->doesntContain(fn($item)=> $item->title===$stringVariant )){
                AddTermVariantAction::run($term, $stringVariant);
            }
        }
        // Удаление удалённых
        foreach($existsVariants as $existsVariant){
            if(collect($termData->variants)->doesntContain(fn($item)=> $existsVariant->title === $item)){
                $existsVariant->delete();
            }
        }
        // Установка is_main для синонима, если нужно
        $isMainExist = TermVariant::query()
            ->where("term_id", $term->id)
            ->where("is_main", 1)
            ->count();
        if( !$isMainExist){
            $variant = TermVariant::query()
                ->where("term_id", $term->id)
                ->where("is_main", 0)
                ->orderBy("id","asc")
                ->first();
            if($variant){
                $variant->is_main = 1;
                $variant->save();
            }
        }

        return $term;
    }
}
