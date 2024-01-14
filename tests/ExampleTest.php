<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function is_true(): void
    {
        $this->assertTrue(true);
    }
}