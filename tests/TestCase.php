<?php

namespace AnkitPokhrel\LaravelImage\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected $sample = __DIR__ . '/img/ankit_sample.png';

    protected $testImage = __DIR__ . '/img/ankit.png';

    public function setUp()
    {
        parent::setUp();

        if ( ! file_exists($this->testImage)) {
            copy($this->sample, $this->testImage);
        }
    }

    public function tearDown()
    {
        parent::tearDown();

        if (file_exists($this->testImage)) {
            unlink($this->testImage);
        }
    }
}
