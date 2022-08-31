<?php namespace App\Services;

use App\Events\TextAddedEvent;
use App\Models\Content;
use App\Models\ContentChunk;
use App\Models\ExternalSource;
use Illuminate\Support\Str;

class ContentService
{
    /**
     * @var Content
     */
    private $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        $this->refresh();
        return $this->content;
    }

    public function refresh()
    {
        $this->content->refresh()->load("chunks");
    }

    public function addChunk($text, $order = null, $mark = null)
    {
        if(!$this->content->id) $this->content->save();
        if(!$order){
            $lastOrder = optional($this->content->chunks()->orderBy("order", "desc")->first())->order;
            if(!$lastOrder) $lastOrder = 0;
            $order = $lastOrder + 10;
        }
        if(!$mark){
            $mark = Str::random(5);
        }
        $chunk = new ContentChunk();
        $chunk->content_id = $this->content->id;
        $chunk->chunkable_type = $this->content->contentable_type;
        $chunk->chunkable_id = $this->content->contentable_id;
        $chunk->order = $order;
        $chunk->text = $text;
        $chunk->mark = $mark;
        $chunk->save();

        TextAddedEvent::dispatch($text);
    }

    public function addExternalSource($url): bool
    {
        if(!isset($this->content->id)){
            throw new \DomainException("Cannot add external source to unsaved content.");
        }
        if($this->content->external_sources->filter(fn(ExternalSource $source)=> $source->url === $url)->count()>0) return false;
        $source = new ExternalSource();
        $source->url = $url;
        $source->externable_type = Content::class;
        $source->externable_id = $this->content->id;
        $source->save();
        return true;
    }

    public function setMain()
    {
        if($this->content->contentable->contents->filter(fn(Content $content)=> $content->id === $this->content->id )->count() === 0){
            throw new \DomainException("Content id {$this->content->id} not in parent {$this->content->contentable->id}");
        }
        $this->content->contentable->contents()->update(['is_main'=>0]);
        $this->content->contentable->contents()->where("id", $this->content->id)->update(['is_main'=>1]);
        $this->refresh();
    }

}
