<?php

namespace App\Http\Controllers\Canon;

use App\Data\SuttaNameData;
use App\Http\Controllers\Controller;
use App\Models\People;
use App\Models\Sutta;
use App\Services\SuttaService;
use App\TextParser\TextParser;

class SuttaController extends Controller
{
    public function index()
    {
        $suttaName = request()->route()->sutta;
        $lang = request()->route()->lang;
        $translatorSlug = request()->route()->translator;

        $suttaNameData = SuttaNameData::from($suttaName);
        $sutta = Sutta::query()
            ->bySuttaName($suttaNameData)
            ->first();

        // Урл вида /mn123 - берём первый русский перевод, если нет - берём палийский, и редиректим на него
        if (! $lang and ! $translatorSlug) {
            $existsRuContent = $sutta->contents()->where('lang', 'ru')->orderBy('created_at', 'asc')->first();
            if ($existsRuContent) {
                return redirect()->route('sutta', [
                    'sutta' => $suttaName,
                    'lang' => 'ru',
                    'translator' => $existsRuContent->translator->slug,
                ]);
            }

            return redirect()->route('sutta', [
                'sutta' => $suttaName,
                'lang' => 'pali',
            ]);
        }
        // Урл вида /mn123/en - берём первый перевод данного языка и редиректим на него
        if ($lang and $lang !== 'pali' and ! $translatorSlug) {
            $existsLangContent = $sutta->contents()->where('lang', $lang)->orderBy('created_at', 'asc')->first();
            if ($existsLangContent) {
                return redirect()->route('sutta', [
                    'sutta' => $suttaName,
                    'lang' => $lang,
                    'translator' => $existsLangContent->translator->slug,
                ]);
            }
        }

        if ($lang == 'pali') {
            $content = $sutta->contents()->where('lang', $lang)->first();
        } else {
            $translator = People::query()
                ->where('slug', $translatorSlug)
                ->first();
            if (! $translator) {
                abort(404);
            }
            $content = $sutta->contents()->where('lang', $lang)->where('translator_id', $translator->id)->first();
        }

        if (! $content) {
            abort(404);
        }

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
                return [
                    'mark' => $chunk->mark,
                    'html' => $textParser->parse($chunk->text),
                ];
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

        $suttaService = new SuttaService($sutta);

        return inertia('Canon/SuttaPage', [
            'sutta' => $sutta,
            'contents' => $sutta->contents,
            'selectedContentId' => $selectedContentId,
            'chunksByContentId' => $chunksByContentId,
            'nikayaTitle' => $nikayaTitle,
            'lang' => $lang,
            'suttaSlug' => $sutta->displaySlug(),
            'prevSuttaSlug' => $suttaService->findPrevSutta()?->displaySlug(),
            'nextSuttaSlug' => $suttaService->findNextSutta()?->displaySlug(),
            'breadcrumbs' => $breadcrumbs,

        ]);
    }

    //    private function findSelectedContent(string $lang, ?string $translatorSlug, Sutta $sutta)
    //    {
    //        $suttaContents = $sutta->contents;
    //
    //        $langContents = $suttaContents->filter(fn ($content) => $content->lang == $lang);
    //
    //        $translator = People::query()->where('slug', $translatorSlug)->first();
    //        if (! empty($translator)) {
    //            $translatedContents = $langContents->filter(fn ($content) => $content->translator?->id == $translator->id);
    //            $content = $translatedContents->first();
    //            if (! empty($content)) {
    //                return $content;
    //            }
    //        }
    //
    //        $content = $langContents->first();
    //        if (! empty($content)) {
    //            return $content;
    //        }
    //
    //        return $suttaContents->first();
    //    }
}
