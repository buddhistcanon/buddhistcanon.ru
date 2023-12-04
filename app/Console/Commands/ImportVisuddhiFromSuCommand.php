<?php

namespace App\Console\Commands;

use DiDom\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Str;
use Symfony\Component\DomCrawler\Crawler;

class ImportVisuddhiFromSuCommand extends Command
{
    protected $signature = 'lb:import_visuddhi {--delete}';

    protected $description = 'Import Visuddhimagga from theravada.su';

    public $content;

    public function handle()
    {
        //ray()->showHttpClientRequests();
        // "https://tipitaka.theravada.su/toc/translations/32879";
        $urlsBookOne = [
            //            "https://tipitaka.theravada.su/node/table/32881",
            'https://tipitaka.theravada.su/node/table/32882',
            'https://tipitaka.theravada.su/node/table/32894',
            'https://tipitaka.theravada.su/node/table/32909',
            'https://tipitaka.theravada.su/node/table/32917',
            'https://tipitaka.theravada.su/node/table/32935',
            'https://tipitaka.theravada.su/node/table/32946',
            'https://tipitaka.theravada.su/node/table/32951',
            'https://tipitaka.theravada.su/node/table/32959',
            'https://tipitaka.theravada.su/node/table/32965',
            'https://tipitaka.theravada.su/node/table/32971',
            'https://tipitaka.theravada.su/node/table/32977',
        ];
        $chunksPali = [];
        $chunksEnglish = [];
        $chunksRussian = [];
        foreach ($urlsBookOne as $url) {
            $response = Http::get($url);
            $this->content = $response->body();

            //            $this->cutContent( "<p class=\"subsubhead\">", true);
            //            dump(Str::substr($this->content, 0, 20));

            //$crawler = new Crawler($this->content);
            $document = new Document($this->content);

            $title = $this->trimHeader($document->first('p.chapter')->text());
            if ($title) {
                $chunksPali[] = '';
                $chunksEnglish[] = '';
                $chunksRussian[] = "## $title";
            }

            $trs = $document->first('table.bordercell')->find('tr');
            $trHead = $trs[0]->find('td');
            $numColumns = count($trHead);

            $trs = $document->first('table.bordercell')->find('tr');
            ray('Num tr rows:', count($trs));
            foreach ($trs as $i => $tr) {
                //$trs->each(function (Crawler $tr, $i)use($numColumns, $chunksPali, $chunksEnglish, $chunksRussian) {

                if ($i == 0) {
                    continue;
                }

                //ray("Count td:", count($tr->find("td")));
                if (count($tr->find('td')) == 1) {
                    // Subtitle
                    $subtitle = $this->trimHeader($tr->first('td')->first('p')->text());
                    $chunkPali = '';
                    $chunkEnglish = '';
                    $chunkRussian = '### '.$subtitle;
                } elseif ($numColumns == 3) {
                    //                    $chunkPali = $tr->filterXPath("/td[1]")->text();
                    //                    $chunkEnglish = $tr->filterXPath("/td[2]")->text();
                    //                    $chunkRussian = "";
                    //                    if(trim($tr->filterXPath("/td[3]")->text()) != "") {
                    //                        $chunkEnglish .= "[^*]\n\n[^*]:".$tr->filterXPath("/td[3]")->text();
                    //                    }
                } elseif ($numColumns == 4) {
                    //ray($tr->text());
                    $chunkPali = $this->trim($tr->find('td')[0]->text());
                    $chunkEnglish = $this->trim($tr->find('td')[1]->text());
                    $chunkRussian = $this->trim($tr->find('td')[2]->text());
                    if ($this->trim($tr->find('td')[3]->text()) != '') {
                        //ray($tr->find("td")[3]->text());
                        $chunkEnglish .= "[^*]\n\n[^*]:".$this->trim($tr->find('td')[3]->text());
                        $chunkRussian .= "[^*]\n\n[^*]:".$this->trim($tr->find('td')[3]->text());
                    }
                }

                $chunksPali[] = $chunkPali;
                $chunksEnglish[] = $chunkEnglish;
                $chunksRussian[] = $chunkRussian;
                //ray($chunkPali, $chunkEnglish, $chunkRussian);
            }
            //});

            ray($chunksRussian);
            exit();
        }
    }

    public function cutContent($needle, $include)
    {
        if ($include) {
            $this->content = mb_substr($this->content, mb_strpos($this->content, $needle));
        } else {
            $this->content = mb_substr($this->content, mb_strpos($this->content, $needle) + mb_strlen($needle));
        }
    }

    public function trim($value)
    {
        $value = str_replace(["\n", "\r", "\t"], '', $value);
        $value = strip_tags($value);
        $value = trim($value);

        return $value;
    }

    public function trimHeader($value)
    {
        $value = str_replace(['Таблица', 'Палийский оригинал'], '', $this->trim($value));

        return $value;
    }
}
