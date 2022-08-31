<?php

namespace Tests\Feature\Data;

use App\Data\SuttaNameData;
use App\Exceptions\Domain\WrongSuttaNameException;
use Tests\TestCase;

class SuttaNameDataTest extends TestCase
{
    public function testCreateSuttaNameDataFromString()
    {
    	$sutta = SuttaNameData::from("MN23.45");
        $this->assertEquals("mn", $sutta->category);
        $this->assertEquals("23", $sutta->order);
        $this->assertEquals("45", $sutta->suborder);
        unset($sutta);

        $sutta = SuttaNameData::from("an1");
        $this->assertEquals("an", $sutta->category);
        $this->assertEquals("1", $sutta->order);
        $this->assertEquals(null, $sutta->suborder);

        $this->expectException(WrongSuttaNameException::class);
        $sutta = SuttaNameData::from("nn10");
        $sutta = SuttaNameData::from("an");

    }
}
