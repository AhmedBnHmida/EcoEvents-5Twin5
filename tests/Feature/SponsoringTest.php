<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Partner;
use App\Models\Sponsoring;
use App\Models\Event;
use App\Models\Category;
use App\TypeSponsoring;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Barryvdh\DomPDF\Facade\Pdf;

class SponsoringTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $organisateur;
    protected $participant;
    protected $partner;
    protected $event;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->organisateur = User::factory()->create(['role' => 'organisateur']);
        $this->participant = User::factory()->create(['role' => 'participant']);

        // Create test partner and event
        $this->partner = Partner::factory()->create();
        
        $category = Category::factory()->create();
        $this->event = Event::factory()->create([
            'categorie_id' => $category->id,
        ]);
    }


    /** @test */
    public function test_admin_can_create_sponsoring()
    {
        $this->actingAs($this->admin);

        $sponsoringData = [
            'montant' => 5000.00,
            'type_sponsoring' => 'argent',
            'date' => now()->format('Y-m-d'),
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ];

        $response = $this->post(route('sponsoring.store'), $sponsoringData);

        $response->assertRedirect(route('sponsoring.index'));
        $this->assertDatabaseHas('sponsorings', [
            'montant' => 5000.00,
            'type_sponsoring' => 'argent',
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ]);
    }

    /** @test */
    public function test_organisateur_can_create_sponsoring()
    {
        $this->actingAs($this->organisateur);

        $sponsoringData = [
            'montant' => 3000.00,
            'type_sponsoring' => 'materiel',
            'date' => now()->format('Y-m-d'),
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ];

        $response = $this->post(route('sponsoring.store'), $sponsoringData);

        $response->assertRedirect(route('sponsoring.index'));
        $this->assertDatabaseHas('sponsorings', [
            'montant' => 3000.00,
            'type_sponsoring' => 'materiel',
        ]);
    }

    /** @test */
    public function test_participant_cannot_create_sponsoring()
    {
        $this->actingAs($this->participant);

        $sponsoringData = [
            'montant' => 1000.00,
            'type_sponsoring' => 'argent',
            'date' => now()->format('Y-m-d'),
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ];

        $response = $this->post(route('sponsoring.store'), $sponsoringData);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_sponsoring_validation_requires_all_fields()
    {
        $this->actingAs($this->admin);

        $response = $this->post(route('sponsoring.store'), []);

        $response->assertSessionHasErrors([
            'montant',
            'type_sponsoring',
            'date',
            'partenaire_id',
            'evenement_id',
        ]);
    }

    /** @test */
    public function test_sponsoring_validation_requires_positive_amount()
    {
        $this->actingAs($this->admin);

        $sponsoringData = [
            'montant' => -100,
            'type_sponsoring' => 'argent',
            'date' => now()->format('Y-m-d'),
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ];

        $response = $this->post(route('sponsoring.store'), $sponsoringData);

        $response->assertSessionHasErrors(['montant']);
    }

    /** @test */
    public function test_sponsoring_validation_requires_valid_type()
    {
        $this->actingAs($this->admin);

        $sponsoringData = [
            'montant' => 1000,
            'type_sponsoring' => 'invalid_type',
            'date' => now()->format('Y-m-d'),
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ];

        $response = $this->post(route('sponsoring.store'), $sponsoringData);

        $response->assertSessionHasErrors(['type_sponsoring']);
    }


    /** @test */
    public function test_admin_can_update_sponsoring()
    {
        $this->actingAs($this->admin);

        $sponsoring = Sponsoring::factory()->create([
            'montant' => 1000,
            'type_sponsoring' => TypeSponsoring::ARGENT,
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ]);

        $updateData = [
            'montant' => 2000,
            'type_sponsoring' => 'materiel',
            'date' => now()->format('Y-m-d'),
            'partenaire_id' => $this->partner->id,
            'evenement_id' => $this->event->id,
        ];

        $response = $this->put(route('sponsoring.update', $sponsoring->id), $updateData);

        $response->assertRedirect(route('sponsoring.index'));
        $this->assertDatabaseHas('sponsorings', [
            'id' => $sponsoring->id,
            'montant' => 2000,
            'type_sponsoring' => 'materiel',
        ]);
    }

    /** @test */
    public function test_admin_can_delete_sponsoring()
    {
        $this->actingAs($this->admin);

        $sponsoring = Sponsoring::factory()->create();

        $response = $this->delete(route('sponsoring.destroy', $sponsoring->id));

        $response->assertRedirect(route('sponsoring.index'));
        $this->assertDatabaseMissing('sponsorings', ['id' => $sponsoring->id]);
    }

    /** @test */
    public function test_participant_cannot_delete_sponsoring()
    {
        $this->actingAs($this->participant);

        $sponsoring = Sponsoring::factory()->create();

        $response = $this->delete(route('sponsoring.destroy', $sponsoring->id));

        $response->assertStatus(403);
    }



    /** @test */
    public function test_sponsoring_relationship_with_partner()
    {
        $sponsoring = Sponsoring::factory()->create([
            'partenaire_id' => $this->partner->id,
        ]);

        $this->assertInstanceOf(Partner::class, $sponsoring->partner);
        $this->assertEquals($this->partner->id, $sponsoring->partner->id);
    }

    /** @test */
    public function test_sponsoring_relationship_with_event()
    {
        $sponsoring = Sponsoring::factory()->create([
            'evenement_id' => $this->event->id,
        ]);

        $this->assertInstanceOf(Event::class, $sponsoring->event);
        $this->assertEquals($this->event->id, $sponsoring->event->id);
    }

    /** @test */
    public function test_partner_has_many_sponsorings_relationship()
    {
        $sponsorings = Sponsoring::factory()->count(3)->create([
            'partenaire_id' => $this->partner->id,
        ]);

        $this->assertEquals(3, $this->partner->sponsorings()->count());
    }

    /** @test */
    public function test_type_sponsoring_enum_casting()
    {
        $sponsoring = Sponsoring::factory()->create([
            'type_sponsoring' => TypeSponsoring::ARGENT,
        ]);

        $sponsoring->refresh();

        $this->assertInstanceOf(TypeSponsoring::class, $sponsoring->type_sponsoring);
        $this->assertEquals('argent', $sponsoring->type_sponsoring->value);
    }

    /** @test */
    public function test_type_sponsoring_enum_has_label_method()
    {
        $this->assertEquals('Argent', TypeSponsoring::ARGENT->label());
        $this->assertEquals('Matériel', TypeSponsoring::MATERIEL->label());
        $this->assertEquals('Logistique', TypeSponsoring::LOGISTIQUE->label());
        $this->assertEquals('Autre', TypeSponsoring::AUTRE->label());
    }


}
