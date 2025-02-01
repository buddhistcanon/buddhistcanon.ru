<?php

namespace Tests\Feature\Services;

use App\Models\Sutta;
use App\Services\SuttaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuttaServiceTest extends TestCase
{
    private Sutta $sutta1;
    private Sutta $sutta2;
    private Sutta $sutta3;

    protected function setUp(): void
    {
        parent::setUp();

        (new Sutta())->forceFill([
            'name' => 'Sutta 1',
            'category' => 'A',
            'order' => 1,
            'suborder' => 1
        ])->save();

        (new Sutta())->forceFill([
            'name' => 'Sutta 2',
            'category' => 'A',
            'order' => 1,
            'suborder' => 2
        ])->save();

        (new Sutta())->forceFill([
            'name' => 'Sutta 3',
            'category' => 'A',
            'order' => 2
        ])->save();

        $this->sutta1 = Sutta::where('name', 'Sutta 1')->first();
        $this->sutta2 = Sutta::where('name', 'Sutta 2')->first();
        $this->sutta3 = Sutta::where('name', 'Sutta 3')->first();
    }

    protected function tearDown(): void
    {
        $this->sutta1->delete();
        $this->sutta2->delete();
        $this->sutta3->delete();

        parent::tearDown();
    }

    public function testFindNextSutta()
    {
        $service = new SuttaService($this->sutta1);
        $this->assertEquals($this->sutta2->id, $service->findNextSutta()?->id);

        $service = new SuttaService($this->sutta2);
        $this->assertEquals($this->sutta3->id, $service->findNextSutta()?->id);

        $service = new SuttaService($this->sutta3);
        $this->assertNull($service->findNextSutta());
    }

    public function testFindPrevSutta()
    {
        $service = new SuttaService($this->sutta3);
        $this->assertEquals($this->sutta2->id, $service->findPrevSutta()?->id);

        $service = new SuttaService($this->sutta2);
        $this->assertEquals($this->sutta1->id, $service->findPrevSutta()?->id);

        $service = new SuttaService($this->sutta1);
        $this->assertNull($service->findPrevSutta());
    }
}
