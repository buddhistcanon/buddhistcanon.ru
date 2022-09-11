<?php

namespace App\Http\Controllers\Canon;

use App\Data\SuttaNameData;
use App\Http\Controllers\Controller;
use App\Models\People;
use App\Models\Sutta;
use App\TextParser\TextParser;

class SuttaController extends Controller
{
    public function index()
    {
        $suttaName = request()->route()->sutta;
        $lang = request()->route()->lang;
        $translatorSlug = request()->route()->translator;

        if (! $lang and ! $translatorSlug) {
            return redirect()->route('sutta', [
                'sutta' => $suttaName,
                'lang' => 'ru',
                'translator' => 'sv',
            ]);
        }

        $suttaNameData = SuttaNameData::from($suttaName);

        $sutta = Sutta::query()
            ->bySuttaName($suttaNameData)
            ->first();

        $content = $sutta->contents();
        if ($lang) {
            $content = $content->where('lang', $lang);
        }
        if ($translatorSlug) {
            $translator = People::where('slug', $translatorSlug)->firstOrFail();
            $content = $content->where('translator_id', $translator->id);
        }
        $content = $content->with('chunks')->first();

        $nikayaTitle = displayNikayaTitleByCategory($sutta->category).' '.$sutta->name;
        //  if($sutta->suborder) $nikayaTitle .= ".".$sutta->suborder;

        $textParser = new TextParser();
        $contentChunks = $content->chunks->map(function ($chunk) use ($textParser) {
            return $textParser->parse($chunk->text);
        })->toArray();

        return inertia('Canon/SuttaPage', [
            'sutta' => $sutta,
            'content' => $content,
            'nikayaTitle' => $nikayaTitle,
            'lang' => $lang,
            'contentChunks' => $contentChunks,
            'suttaSlug' => $sutta->displaySlug(),
        ]);
    }
}
