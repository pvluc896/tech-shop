<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_listing_renders_with_bootstrap_pagination(): void
    {
        $this->get('/products')->assertOk();
    }
}
