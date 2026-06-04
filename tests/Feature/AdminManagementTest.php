<?php

use App\Models\Service;
use App\Models\Promotion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->adminUser = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->staffUser = User::factory()->create([
        'role' => 'staff',
    ]);
});

it('denies staff access to services and promotions management', function () {
    $this->actingAs($this->staffUser)
        ->get(route('services.index'))
        ->assertStatus(403);

    $this->actingAs($this->staffUser)
        ->get(route('promotions.index'))
        ->assertStatus(403);
});

it('allows admin to manage services', function () {
    // List
    $this->actingAs($this->adminUser)
        ->get(route('services.index'))
        ->assertStatus(200);

    // Create
    $serviceData = [
        'name' => 'Cuci Premium Baru',
        'price' => 150000.00,
        'duration_estimate' => 120,
        'description' => 'Super high luxury detailing.',
        'is_active' => 'on',
    ];

    $this->actingAs($this->adminUser)
        ->post(route('services.store'), $serviceData)
        ->assertRedirect(route('services.index'));

    $this->assertDatabaseHas('services', [
        'name' => 'Cuci Premium Baru',
        'price' => 150000.00,
    ]);

    $service = Service::where('name', 'Cuci Premium Baru')->first();

    // Edit view
    $this->actingAs($this->adminUser)
        ->get(route('services.edit', $service))
        ->assertStatus(200);

    // Update
    $this->actingAs($this->adminUser)
        ->put(route('services.update', $service), array_merge($serviceData, ['name' => 'Cuci Premium Diupdate']))
        ->assertRedirect(route('services.index'));

    expect($service->fresh()->name)->toBe('Cuci Premium Diupdate');

    // Delete
    $this->actingAs($this->adminUser)
        ->delete(route('services.destroy', $service))
        ->assertRedirect(route('services.index'));

    $this->assertModelMissing($service);
});

it('allows admin to manage promotions', function () {
    // List
    $this->actingAs($this->adminUser)
        ->get(route('promotions.index'))
        ->assertStatus(200);

    // Create
    $promotionData = [
        'title' => 'Diskon Lebaran',
        'description' => 'Diskon mudik suci.',
        'discount_percentage' => 20,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(5)->toDateString(),
        'is_active' => 'on',
    ];

    $this->actingAs($this->adminUser)
        ->post(route('promotions.store'), $promotionData)
        ->assertRedirect(route('promotions.index'));

    $this->assertDatabaseHas('promotions', [
        'title' => 'Diskon Lebaran',
        'discount_percentage' => 20,
    ]);

    $promotion = Promotion::where('title', 'Diskon Lebaran')->first();

    // Edit view
    $this->actingAs($this->adminUser)
        ->get(route('promotions.edit', $promotion))
        ->assertStatus(200);

    // Update
    $this->actingAs($this->adminUser)
        ->put(route('promotions.update', $promotion), array_merge($promotionData, ['title' => 'Diskon Lebaran Update']))
        ->assertRedirect(route('promotions.index'));

    expect($promotion->fresh()->title)->toBe('Diskon Lebaran Update');

    // Delete
    $this->actingAs($this->adminUser)
        ->delete(route('promotions.destroy', $promotion))
        ->assertRedirect(route('promotions.index'));

    $this->assertModelMissing($promotion);
});
