<?php

namespace App\Services;

use DiDom\Document;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ImportSuttaFromTheravadaRuService
{
    private $document;

    private $isDebug;

    private $isNeedAttention = false;

    public function __construct($isDebug = false)
    {
        $this->isDebug = $isDebug;
    }

    public function setHtml($html)
    {
        $this->document = new Document($html);
    }

    public function fetchHtml($url)
    {
        $html = Http::get($url);
        if ($this->isDebug) {
            $this->dump('downloaded content size: '.mb_strlen($html));
        }
        $html = iconv('windows-1251', 'UTF-8', $html);
        $html = str_replace("\r", '', $html);
        $this->document = new Document($html);
    }

    public function category_name()
    {
        $arr = explode(' ', $this->document->find('font[size=3]')[0]->text());

        return $arr[0];
    }

    public function order()
    {
        $arr = explode(' ', $this->document->find('font[size=3]')[0]->text());
        $arr2 = explode('.', $arr[1]);

        return $arr2[0];
    }

    public function suborder()
    {
        $arr = explode(' ', $this->document->find('font[size=3]')[0]->text());
        $arr2 = explode('.', $arr[1]);
        if (isset($arr2[1])) {
            return $arr2[1];
        } else {
            return null;
        }
    }

    public function name()
    {
        $title = $this->document->first('font[size=5]')->firstChild()->text();
        $title = str_replace("\n", '', $title);
        $title = $this->normalize_spaces($title);

        return $title;
        //return trim(explode("\n", $this->document->find("font[size=5]")[0]->text())[1]);
    }

    public function copyright()
    {
        $node = $this->document->find('div[align=right]')[1]->first('span');
        if (! $node) {
            $node = $this->document->find('div[align=right]')[0]->first('span');
        }
        if ($node) {
            $text = str_replace("\n", '', $node->innerHtml());
            $text = $this->normalize_spaces($text);
            $text = $this->strip_tags($text);

            return $text;
        } else {
            return '';
        }
    }

    public function original_html()
    {
        $content = $this->document->find('table[width=60%]')[0]->html();
        $notes = $this->document->find('table[width=55%]');
        if (count($notes) > 0) {
            foreach ($notes as $note) {
                $content .= $note->html();
            }
        }
        if (str_contains($content, 'страница 1')) {
            $this->isNeedAttention = true;
        }

        return $content;
    }

    public function content()
    {
        $content = '';
        $contentAreas = $this->document->find('table[width=60%]')[0]->find('td');
        $contentArea = 'error';
        if (isset($contentAreas[0]) and strlen($contentAreas[0]->text()) > 200) {
            $contentArea = $contentAreas[0];
        }
        if (isset($contentAreas[1]) and strlen($contentAreas[1]->text()) > 200) {
            $contentArea = $contentAreas[1];
        }
        if (isset($contentAreas[2]) and strlen($contentAreas[2]->text()) > 200) {
            $contentArea = $contentAreas[2];
        }
        if (isset($contentAreas[3]) and strlen($contentAreas[3]->text()) > 200) {
            $contentArea = $contentAreas[3];
        }
        if (isset($contentAreas[4]) and strlen($contentAreas[4]->text()) > 200) {
            $contentArea = $contentAreas[4];
        }

        $arrayNotes = $this->array_notes();
        $paragraphs = $contentArea->find('p|div|ul|font');
        $this->dump('Paragraphs found: '.count($paragraphs));
        $this->dump('Notes found: '.count($arrayNotes));
        $this->dump($arrayNotes);

        if (count($paragraphs) == 0) {
            $paragraphs[0] = $paragraphs;
        }

        // обрабатываем выложенные дивы с параграфами
        $mergedParagraphs = [];
        foreach ($paragraphs as $i => $area) {
            if ($area->has('div|ul') and count($area->find('div|ul')) > 2) {
                $subParagraphs = $area->find('div|ul');
                $this->dump("$i Sub-paragraphs found: ".count($subParagraphs));
                foreach ($subParagraphs as $subParagraph) {
                    $mergedParagraphs[] = $subParagraph;
                    //$this->dump($subParagraph->html());
                }
            } else {
                $mergedParagraphs[] = $area;
            }
        }

        foreach ($mergedParagraphs as $i => $paragraph) {
            $this->dump('PARAGRAPH '.($i + 1));
            $addNoteIndexes = [];
            $innerHtml = $this->normalize_spaces($paragraph->innerHtml());
            //$this->dump($innerHtml);
            if ($paragraph->has('sup')) {
                preg_match_all("/<sup>.*<a href=\".*#link\d*\">(\d*)<\/a>.*<\/sup>/m", $innerHtml, $matches);
                //$this->dump($matches);
                if (isset($matches[1])) {
                    foreach ($matches[1] as $j => $m) {
                        $match = [$matches[0][$j], $matches[1][$j]];
                        $addNote = $match[1];
                        $replaceString = $match[0];
                        $addNoteIndexes[] = $addNote;
                        $this->dump('NOTE FOUND: '.$addNote);
                        $innerHtml = str_replace($replaceString, '[^'.$addNote.']', $innerHtml); // [^1]
                    }
                }
            }
            $textParagraph = $this->strip_tags($innerHtml);
            //$this->dump($textParagraph);
            $textParagraph = $this->normalize_spaces($textParagraph);
            $this->dump($textParagraph);
            $content .= $textParagraph;
            if (count($addNoteIndexes) > 0) {
                foreach ($addNoteIndexes as $addNote) {
                    if (! isset($arrayNotes[$addNote])) {
                        $this->dump(" !!! note $addNote not found in Notes !!!");
                        $this->isNeedAttention = true;
                    } else {
                        $content .= "\n[^$addNote]: ".$arrayNotes[$addNote];
                    }
                }
            }
            $content .= "\n\n";
        }

        $content = $this->normalize_first_letter(trim($content));

        return $content;
    }

    //    public function find_note($html)
    //    {
    //        preg_match("/<sup>.*<a href=\".*#link\d*\">(\d*)<\/a><\/sup>/m", $html, $match);
    //        //$this->dump($match);
    //        $addNote = $match[1];
    //        $replaceString = $match[0];
    //        $this->dump("Note found: ".$addNote);
    //        //$html = preg_replace("/<sup>.*<a href=\".*#link(\d*)\">\d*<\/a><\/sup>/", " [примечание$1]", $paragraph->innerHtml());
    //        $html = str_replace($replaceString, "[примечание".$addNote."]", $html);
    //        return
    //    }

    public function array_notes()
    {
        $array = [];
        $notes = $this->document->find('table[width=55%]');
        if (count($notes) > 0) {
            foreach ($notes as $i => $note) {
                $trs = $note->find('tr');
                foreach ($trs as $area) {
                    $td = $area->find('td');
                    $index = $td[1]->text();
                    $text = $td[2]->first('font[color=#999966]')->innerHtml();
                    $text = $this->strip_tags($text);
                    $text = str_replace("\n", '', $text);
                    $array[$index] = $this->normalize_spaces($text);
                    //$array[$i + 1] = $text;
                }
            }

            return $array;
        } else {
            return [];
        }
    }

    public function to_one_string($string)
    {
        $string = str_replace("\n", '', $string);

        return $string;
    }

    public function strip_tags($string)
    {
        $string = str_replace("\n", ' ', $string);
        $brTags = ['<br>', '<br/>', '<br />'];
        $string = strip_tags($string, allowed_tags: $brTags);
        $string = str_replace($brTags, "\n", $string);

        return $string;
    }

    public function normalize_spaces($string)
    {
        $string = preg_replace('/ {2,}/', ' ', $string);
        $string = trim($string);

        return $string;
    }

    public function normalize_first_letter($string)
    {
        $string = preg_replace("/^(.)\n\n/mu", '$1', $string);

        return $string;
    }

    public function dump($value)
    {
        if ($this->isDebug) {
            dump($value);
        }
    }

    public function all_page()
    {
        return $this->document->html();
    }

    public function inner_urls()
    {
        $links = $this->document->find('a');
        $urls = new Collection();
        foreach ($links as $link) {
            $url = $link->href;
            if (! \Str::startsWith($url, ['http', '//', '#', '..'])) {
                //    	        if( ! starts_with($url, "/")) {
                //    	            $url = "/".$url;
                //                }
                $urls->push($url);
            }
        }

        return $urls;
    }

    public function is_need_attention()
    {
        if ($this->isNeedAttention) {
            return 1;
        } else {
            return 0;
        }
    }
}
