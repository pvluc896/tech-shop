<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoucherPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_voucher_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('vouchers.index'))
            ->assertOk();
    }
}
