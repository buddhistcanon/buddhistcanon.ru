<?php

namespace Tests\Feature\Actions\Terms;

use App\Actions\Terms\StoreTermAction;
use App\Data\TermData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTermActionTest extends TestCase
{
    use RefreshDatabase;

    public function testJustStore()
    {
        $termData = TermData::from([
            'title' => 'Term1',
            'short_text' => 'Short term 1',
            'parts_text' => "[[Term22]]\n[[Term33]]",
            'text' => 'Some text term 1',
        ]);
        $termData->variants = ['synonym1', 'synonym2', 'synonym3'];
        StoreTermAction::run($termData);

        $this->assertDatabaseHas('terms', [
            'title' => 'Term1',
            'slug' => 'term1',
            'parts_text' => "[[Term22]]\n[[Term33]]",
            'text' => 'Some text term 1',
        ]);

        $this->assertDatabaseHas('term_variants', [
            'title' => 'synonym1',
            'is_main' => 1,
        ]);
        $this->assertDatabaseHas('term_variants', [
            'title' => 'synonym2',
            'is_main' => 0,
        ]);
        $this->assertDatabaseHas('term_variants', [
            'title' => 'synonym3',
            'is_main' => 0,
        ]);
    }

    public function testStoreAndAdd()
    {
        $termData = TermData::from([
            'title' => 'Term1', 'short_text' => 'Short term 1', 'text' => 'Some text term 1',
        ]);
        $termData->variants = ['synonym1', 'synonym2', 'synonym3'];
        $term = StoreTermAction::run($termData);

        $termData = TermData::from($term);
        $termData->variants = ['synonym1', 'synonym2', 'synonym3', 'synonym4'];
        $term = StoreTermAction::run($termData);

        $this->assertDatabaseHas('term_variants', [
            'title' => 'synonym4',
        ]);
    }

    public function testStoreAndDelete()
    {
        $termData = TermData::from([
            'title' => 'Term1',
            'short_text' => 'Short term 1',
            'text' => 'Some text term 1',
        ]);
        $termData->variants = ['synonym1', 'synonym2', 'synonym3'];
        $term = StoreTermAction::run($termData);

        $termData = TermData::from($term);
        $termData->variants = ['synonym1', 'synonym3', 'synonym4'];
        $term = StoreTermAction::run($termData);

        $this->assertDatabaseMissing('term_variants', [
            'title' => 'synonym2',
        ]);
        $this->assertDatabaseHas('term_variants', [
            'title' => 'synonym4',
        ]);

        $termData = TermData::from($term);
        $termData->variants = ['synonym3', 'synonym4'];
        StoreTermAction::run($termData);
        $this->assertDatabaseMissing('term_variants', [
            'title' => 'synonym1',
        ]);
        $this->assertDatabaseHas('term_variants', [
            'title' => 'synonym3',
            'is_main' => 1,
        ]);
    }
}
