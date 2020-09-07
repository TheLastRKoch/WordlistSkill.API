<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Gist;

class GistModelTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $gist = new Gist;
        $response = $gist->getWordList();
        echo($response);
        $this->assertTrue(true,"All good ");
    }

    public function testExample2()
    {
        $this->assertTrue(true,"All good ");
        echo "Hi";
    }
}
