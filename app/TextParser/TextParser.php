<?php

namespace App\TextParser;

use App\Models\Term;
use App\Models\TermVariant;
use App\TextParser\Parsedown\ParsedownExtra;

class TextParser
{

    private ParsedownExtra $parsedownExtra;

    public function __construct($commonMarkSettings = null)
    {
        $this->parsedownExtra = new ParsedownExtra();
        $this->parsedownExtra->setBreaksEnabled(true);
    }

    public function parse($text, $livewireId = null)
    {
        //$text = $this->converter->convertToHtml($text);
        //$text = $this->processTerms($text);

        $text = $this->parsedownExtra->text($text);
        $text = $this->processTerms($text, $livewireId);

        return $text;
    }

    public function processNotes($text)
    {
    	preg_replace("//");
    }

    public function processTerms($text, $livewireId)
    {
        preg_match_all('/\[\[(.*?)\](.*?)\]/m', $text, $matches, PREG_SET_ORDER, 0);
        if(! $matches) return $text;
        foreach($matches as $key=>$match){
            $tag = $match[0];
            $title = trim($match[1]);
            $termId = $match[2];
            if($termId !== "" AND $termId == 0){
                $text = str_replace($tag, $title, $text);
                continue;
            }
            if($termId != ""){
                $term = Term::query()->where("id", $termId)->first();
                if(!$term){
                    // Термин по id не найден
                    $text = str_replace($tag, "<a href='".route("term.error", [$termId])."' class='text-red-500'>$title</a>", $text);
                    continue;
                }
            }

            // Поиск термина в базе
            $termVariant = TermVariant::query()->where("title", $title)->first();
            if( ! $termVariant){
                // Термин по title не найден
                $text = str_replace($tag,
                    "<a href='".route("term.error", [$title])."' x-data @click.prevent=\"@this.call('clickTerm', '".$title."')\" class='text-red-500'>$title</a>", $text);
                continue;
            }

            $slug = $termVariant->term->slug;
            $livewire = "window.livewire.find('$livewireId')";
            // Термин найден
            $text = str_replace(
                $tag,
                "<a href='".route("term", [$slug])."' x-data @click.prevent=\"\$dispatch('show-panel'); $livewire.call('clickTerm', '".$title."')\" class='link'>$title</a>",
                $text);
            $text = str_replace($match[0],"<b>$title</b>", $text);
        }
        //dd($text);
        return $text;
    }

    public static function findTerms($text): array
    {
        $array = [];
        preg_match_all('/\[\[(.*?)\](.*?)\]/m', $text, $matches, PREG_SET_ORDER, 0);
        if(! $matches) return [];
        foreach($matches as $match){
            $string = trim($match[1]);
            if(self::isSuttaName($string)) continue;
            $array[] = $string;
        }
        return $array;
    }

    public static function isSuttaName(String $string): bool
    {
    	$string = strtolower($string);
        preg_match("/^(an|mn|dn|sn)[\d\.]*$/m", $string, $match);
        if($match) return true;
        return false;
    }
}
