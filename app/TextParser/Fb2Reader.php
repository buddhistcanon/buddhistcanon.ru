<?php

namespace App\TextParser;

/**
 * @property string|string[] content
 */
class Fb2Reader
{
    private $content;

    private $crawler;

    public function __construct($content)
    {
        $this->content = $content;
        $this->content = str_replace("l:href'", 'src', $this->content); // SimpleXML unable to work with `l:href`
        $this->crawler = new \SimpleXMLElement($this->content);
        dd($this->crawler);
    }
}
