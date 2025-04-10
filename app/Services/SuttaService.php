<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Sutta;

class SuttaService
{
    private Sutta $sutta;

    public function __construct(Sutta $sutta)
    {
        $this->sutta = $sutta;
    }

    public function refresh()
    {
        $this->sutta->refresh()->load('contents');
    }

    public function setMainContentId(int $contentId)
    {
        if ($this->sutta->contents->filter(fn (Content $content) => $content->id === $contentId)->count() === 0) {
            throw new \DomainException("Content id $contentId not in sutta {$this->sutta->id}");
        }
        $this->sutta->contents()->update(['is_main' => 0]);
        $this->sutta->contents()->where('id', $contentId)->update(['is_main' => 1]);
        $this->refresh();
    }

    public function findPrevSutta(): ?Sutta
    {
        $prevSutta = null;
        if (! empty($this->sutta->suborder)) {
            $prevSutta = Sutta::query()
                ->where('category', $this->sutta->category)
                ->where('order', $this->sutta->order)
                ->where('suborder', '<', $this->sutta->suborder)
                ->orderByDesc('suborder')
                ->first();
        }

        if (! empty($prevSutta)) {
            return $prevSutta;
        }

        $prevSutta = Sutta::query()
            ->where('category', $this->sutta->category)
            ->where('order', '<', $this->sutta->order)
            ->orderByDesc('order')
            ->first();

        return $prevSutta;
    }

    public function findNextSutta(): ?Sutta
    {
        $nextSutta = null;
        if (! empty($this->sutta->suborder)) {
            $nextSutta = Sutta::query()
                ->where('category', $this->sutta->category)
                ->where('order', $this->sutta->order)
                ->where('suborder', '>', $this->sutta->suborder)
                ->orderBy('suborder')
                ->first();
        }

        if (! empty($nextSutta)) {
            return $nextSutta;
        }

        $nextSutta = Sutta::query()
            ->where('category', $this->sutta->category)
            ->where('order', '>', $this->sutta->order)
            ->orderBy('order')
            ->first();

        return $nextSutta;
    }
}
