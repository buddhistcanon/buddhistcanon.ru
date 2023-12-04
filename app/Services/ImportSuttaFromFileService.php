<?php

namespace App\Services;

use App\Data\SuttaData;

class ImportSuttaFromFileService
{
    private string $filename;

    private array $arrayContent;

    public function __construct(string $filename, string $fileContent)
    {
        $this->filename = $filename;
        $this->arrayContent = json_decode($fileContent, true);
    }

    public function getFullCategory(): ?string
    {
        //preg_match("/n\/(.*?)_root/", $this->filename, $match);
        preg_match('/^(.*?)_/m', $this->filename, $match);

        return $match[1] ?? null;
    }

    public function determineCategory(): ?string
    {
        $category = $this->getFullCategory();
        preg_match("/([^\d]*)\d*/", $category, $match);

        return $match[1] ?? null;
    }

    public function determineOrder(): ?string
    {
        $category = $this->getFullCategory();
        preg_match("/[^\d]*(\d*)/", $category, $match);

        return $match[1] ?? null;
    }

    public function determineSuborder(): ?string
    {
        $category = $this->getFullCategory();
        if (! str_contains($category, '.')) {
            return null;
        }
        $array = explode('.', $category);

        return $array[1] ?? null;
    }

    public function determineMark($key): ?string
    {
        preg_match("/:(.*)\./", $key, $match);

        return $match[1] ?? null;
    }

    public function determineTitle(): string
    {
        return trim(
            collect($this->arrayContent)->filter(function ($value, $key) {
                preg_match("/.*:0\.2/", $key, $match);
                if ($match) {
                    return $value;
                }
            })->first()
        );
    }

    public function determineSubtitle(): string
    {
        return trim(
            collect($this->arrayContent)->filter(function ($value, $key) {
                preg_match("/.*:0\.(.*)/", $key, $match);
                if (isset($match[1]) and $match[1] != 1 and $match[1] != 2) {
                    return $value;
                }
            })->reduce(function ($acc, $item) {
                return $acc.$item."\n";
            })
        );
    }

    public function getDto(): SuttaData
    {
        $text = '';
        $mark = '';
        $contentWithMarks = [];
        if (count($this->arrayContent) > 0) {
            $currentMark = 0;
            $prevSubKey = '';
            foreach ($this->arrayContent as $key => $value) {
                preg_match("/.*\./", $key, $match);
                $subKey = $match[0];
                if ($subKey !== $prevSubKey) {
                    $currentMark++;
                    $prevSubKey = $subKey;
                }
                if ($currentMark === '0') {
                    continue;
                }
                if (! $mark) {
                    $mark = $currentMark;
                }
                if ($mark === $currentMark) {
                    $text .= trim($value)."\n";
                } else {
                    $contentWithMarks['p'.$mark] = trim($text);
                    $text = trim($value)."\n";
                    $mark = $currentMark;
                }
            }
            $contentWithMarks['p'.$mark] = trim($text);
        }

        $suttaData = new SuttaData(
            $this->determineCategory(),
            $this->determineOrder(),
            $this->determineSuborder(),
            $this->determineTitle(),
            $contentWithMarks
        );
        $suttaData->subtitle = $this->determineSubtitle();

        return $suttaData;
    }
}
