<?php

namespace Tests\Feature\Services;

use App\Services\ImportSuttaFromTheravadaRuService;
use Tests\TestCase;
use function Symfony\Component\Translation\t;

class ImportTheravadaRuTest extends TestCase
{
    public function test_normalize_spaces()
    {
        $service = new ImportSuttaFromTheravadaRuService(false);
        $this->assertEquals("Two spaces", $service->normalize_spaces("Two  spaces"));
        $this->assertEquals("Three spaces", $service->normalize_spaces("Three   spaces"));
        $this->assertEquals("Two spaces several", $service->normalize_spaces("Two  spaces  several"));
        $this->assertEquals("Some spaces several in phrase", $service->normalize_spaces("Some  spaces   several       in                                                phrase"));
        $this->assertEquals("Some spaces several in phrase with lead-trail spaces", $service->normalize_spaces(" Some  spaces   several       in                                                phrase with   lead-trail spaces "));
    }

    public function test_strip_tags()
    {
        $service = new ImportSuttaFromTheravadaRuService(false);
        $this->assertEquals("One two\nthree\n\n", $service->strip_tags("<b>One</b> two<br>three<br /><br/>"));
    }

    public function test_normalize_first_letter(){
        $service = new ImportSuttaFromTheravadaRuService(false);
        $this->assertEquals("Так я слышал. Однажды достопочтенный Баккула проживал в Раджагахе, в Бамбуковой роще, в Беличьем Святилище.
И тогда Ачела Кассапа, бывший приятель достопочтенного Баккулы в мирской жизни, отправился к достопочтенному Баккуле и обменялся с ним приветствиями.
",      $service->normalize_first_letter("Т

ак я слышал. Однажды достопочтенный Баккула проживал в Раджагахе, в Бамбуковой роще, в Беличьем Святилище.
И тогда Ачела Кассапа, бывший приятель достопочтенного Баккулы в мирской жизни, отправился к достопочтенному Баккуле и обменялся с ним приветствиями.
"));
    }


}
