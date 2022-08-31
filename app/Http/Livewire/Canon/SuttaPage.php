<?php

namespace App\Http\Livewire\Canon;

use App\Data\SuttaData;
use App\Data\SuttaNameData;
use App\Models\Book;
use App\Models\Content;
use App\Models\Sutta;
use App\TextParser\TextParser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Livewire\Component;

class SuttaPage extends Component
{
    public int $suttaId;
    public string $suttaTitle;

    /**
     * @var array|null
     */
    public $contentMenu = null; // [ contentId => [is_show, lang, title] ]

    /**
     * @var array|null
     */
    public $contentChunks = null; // [ [contentChunk, contentChunk], ... ]

    public $numContents = null; // сколько контентов у данной сутты

    public $clickedTermTitle = "Поиск термина..";
    public $clickedTermContent = null;
    public $clickedTermId = null;

    //public $livewireId;

    private $contents;

    public function mount(Request $request)
    {
        //$this->livewireId = $this->id;
        $suttaName = $request->route()->sutta;
        $lang = $request->route()->lang;
        $translator = $request->route()->translator;

        $suttaNameData = SuttaNameData::from($suttaName);

        $sutta = Sutta::query()
            ->bySuttaName($suttaNameData)
            ->first();
        $this->suttaTitle = $sutta->displayName(). " ".$sutta->displayPaliTitle();
        $this->suttaId = $sutta->id;

        $this->contents = $sutta->contents()->orderBy("id", "asc")->get();
        $this->numContents = $this->contents->count();

        $this->contentMenu = [];
        $this->contentChunks = [];

        $this->contentMenu = $this->_buildMenu($this->contents);
        $this->_getContents();
    }
    private function _buildMenu(Collection $contents)
    {
        $contentMenu = [];
        $contents->each(function(Content $content)use(&$contentMenu){
            $data = [];
            $data['is_show'] = false;
            $data['lang'] = $content->lang;
            $data['description'] = $content->description;
            $data['link_url'] = $content->link_url;
            if($content->lang === "pali"){
                $data['title'] = "Оригинал на пали";
            }else{
                $language = "неизвестен";
                if($content->lang === "en") $language = "английский";
                if($content->lang === "ru") $language = "русский";
                $data['title'] = "Язык: $language. Перевод: ".$content->translator->displayNameRu();
            }

            $contentMenu[$content->id] = $data;
        });
        $mainContentId = optional($contents->filter(function($item){ if($item->is_main) return $item; })->first())->id;
        if($mainContentId){
            $contentMenu[$mainContentId]['is_show'] = true;
        }else{
            $contentMenu = collect($contentMenu)->map(function($data){ $data['is_show'] = true; return $data; })->toArray();
        }
        return $contentMenu;
    }

    private function _getContents()
    {
        $contentById = collect();
        collect($this->contentMenu)->each(function($menu, $contentId)use($contentById){
            if($menu['is_show']){
                $contentById->put($contentId, Content::query()
                    ->where("id", $contentId)
                    ->with(["chunks"=>function($query){ $query->orderBy('order', 'asc'); }])
                    ->first());
            }
        });
        $this->contentChunks = [];
        if($contentById->count() > 0){
            $indexChunk = 0;
            $textParser = new TextParser();
            foreach($contentById->first()->chunks as $someChunk)
            {
                $row = [];
                foreach($this->contentMenu as $contentId => $menu){
                    if(isset($contentById[$contentId]->chunks[$indexChunk])){
                        $chunk = $contentById[$contentId]->chunks[$indexChunk];
                        if($chunk){
//                            $html = $textParser->parse($chunk->text, $this->id);
                            $row[] = [
                                'id'=>$chunk->id,
                                'content_id'=>$contentId,
                                'mark'=>$chunk->mark,
                                'text'=>$chunk->text,
//                                'html'=>$html
                            ];
                        }
                    }
                }
                $this->contentChunks[] = $row;
                $indexChunk++;
            }
        }
        //dump($this->contentChunks);
    }

    public function toggleShowSuttaContent(int $contentId)
    {
        $toHide = optional($this->contentMenu[$contentId])['is_show'];
        $toShow = !$toHide;

        if($toShow){
            // Check to existence of content
            $content = Content::query()
                ->where("id", $contentId)
                ->first();
            if(!$content OR $content->contentable_id !== $this->suttaId){
                return abort(404, "Контент $contentId не найден");
            }

            $this->contentMenu = collect($this->contentMenu)->map(function($menu, $index)use($contentId){
                if($index === $contentId) $menu['is_show'] = true;
                return $menu;
            })->toArray();
        }

        if($toHide){
            $contentMenu = collect($this->contentMenu)->map(function($menu, $index)use($contentId){
                if($index === $contentId) $menu['is_show'] = false;
                return $menu;
            });
            // Если все контенты сделали false, то включить pali
//            if($contentMenu->every(function($item){ return $item['is_show'] == false; })){
//                $contentMenu = $contentMenu->map(function($item){
//                    if($item['lang'] === 'pali') $item['is_show'] = true;
//                    return $item;
//                });
//            }
            $this->contentMenu = $contentMenu->toArray();
        }

        $this->_getContents();
    }

    public function render()
    {
        $textParser = new TextParser();
        return view('livewire.canon.sutta-page', compact("textParser"))
            ->layout("layouts.wide");
    }
}
