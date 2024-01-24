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
            ->with('contents.chunks')
            ->first();

        // определение selectedContentId
        $content = $sutta->contents;
        if ($lang) {
            $content = $content->filter(fn ($content) => $content->lang == $lang);
        }
        if ($translatorSlug) {
            $translator = People::where('slug', $translatorSlug)->firstOrFail();
            //            $content = $content->where('translator_id', $translator->id);
            $content = $content->filter(fn ($content) => $content->translator_id == $translator->id);
        }
        $content = $content->first();
        if ($content->translator_name or $content->translator_id) {
            $content->display_translator_name = $content->translator_name ?? $content->translator->displayNameRu();
        } else {
            $content->display_translator_name = null;
        }

        if (! $content) {
            return redirect()->route('sutta', [
                'sutta' => $suttaName,
                'lang' => 'en',
                'translator' => 'sujato',
            ]);
        }

        $selectedContentId = $content->id;

        // определение названия сутты вместе с названием никаи
        $nikayaTitle = displayNikayaTitleByCategory($sutta->category).' '.$sutta->name;

        // контент в чанках
        $textParser = new TextParser();
        $chunksByContentId = [];
        foreach ($sutta->contents as &$content) {
            $contentChunks = $content->chunks->map(function ($chunk) use ($textParser) {
                return $textParser->parse($chunk->text);
            })->toArray();
            $chunksByContentId[$content->id] = $contentChunks;
            unset($content->chunks);
        }

        $breadcrumbs = [
            ['title' => 'Палийский канон', 'url' => '/palicanon'],
            ['title' => displayNikayaTitleByCategory($sutta->category), 'url' => '/'.$sutta->category],
        ];
        if ($sutta->category === 'an') {
            $breadcrumbs[] = ['title' => 'Раздел '.strtoupper($sutta->category.$sutta->order), 'url' => '/'.$sutta->category.'/'.$sutta->order];
        }
        if ($sutta->category === 'sn') {
            $breadcrumbs[] = ['title' => 'Раздел '.strtoupper($sutta->category.$sutta->order), 'url' => '/'.$sutta->category.'/'.$sutta->order];
        }

        return inertia('Canon/SuttaPage', [
            'sutta' => $sutta,
            'contents' => $sutta->contents,
            'selectedContentId' => $selectedContentId,
            'chunksByContentId' => $chunksByContentId,
            'nikayaTitle' => $nikayaTitle,
            'lang' => $lang,
            'suttaSlug' => $sutta->displaySlug(),
            'breadcrumbs' => $breadcrumbs,

        ]);
    }
}
