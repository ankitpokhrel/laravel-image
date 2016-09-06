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

        copy($this->sample, $this->testImage);
    }
}
