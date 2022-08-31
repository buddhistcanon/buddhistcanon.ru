<?php

namespace App\Listeners;

use App\Events\TextAddedEvent;
use App\Models\TermProposal;
use App\Models\TermVariant;
use App\TextParser\TextParser;

class FindTermProposalListener
{
    public function __construct()
    {
        //
    }

    public function handle(TextAddedEvent $event)
    {
        $terms = TextParser::findTerms($event->text);
        foreach($terms as $termTitle){
            $termVariant = TermVariant::query()
                ->where("title", $termTitle)
                ->first();
            if(!$termVariant){
                $termProposal = TermProposal::query()
                    ->where("title", $termTitle)
                    ->first();
                if(!$termProposal){
                    $termProposal = new TermProposal();
                    $termProposal->title = $termTitle;
                    $termProposal->save();
                }
            }
        }
    }
}
