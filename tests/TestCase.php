<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Support\CreatesTestSchema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use CreatesTestSchema;

    protected function setUp(): void
    {
        parent::setUp();

        if ($this->app->environment('testing')) {
            $this->createLegacyTestSchema();
        }
    }
}
