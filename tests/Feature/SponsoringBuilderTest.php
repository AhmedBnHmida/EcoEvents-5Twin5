<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Partner;
use App\Models\Event;
use App\Models\Category;
use App\Models\Sponsoring;
use App\TypeSponsoring;

class SponsoringBuilderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $partner;
    protected $event;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com'
        ]);

        // Create category
        $this->category = Category::factory()->create([
            'name' => 'Tech Conference'
        ]);

        // Create partner
        $this->partner = Partner::factory()->create([
            'nom' => 'Microsoft',
            'contact' => 'John Doe',
            'email' => 'john@microsoft.com'
        ]);

        // Create event
        $this->event = Event::factory()->create([
            'title' => 'Tech Summit 2024',
            'categorie_id' => $this->category->id
        ]);
    }

    /** @test */
    public function test_admin_can_access_sponsoring_builder()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('sponsoring-builder.index'));

        $response->assertStatus(200);
        $response->assertViewIs('sponsoring-builder.index');
        $response->assertViewHas('events');
        $response->assertViewHas('partners');
    }

    /** @test */
    public function test_guest_cannot_access_sponsoring_builder()
    {
        $response = $this->get(route('sponsoring-builder.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_non_admin_cannot_access_sponsoring_builder()
    {
        $user = User::factory()->create(['role' => 'participant']);
        $this->actingAs($user);

        $response = $this->get(route('sponsoring-builder.index'));

        $response->assertStatus(403); // Access denied for non-admins
    }

    /** @test */
    public function test_budget_optimization_requires_valid_data()
    {
        $this->actingAs($this->admin);

        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 50000,
            'event_ids' => [$this->event->id],
            'preferences' => 'Focus on tech companies'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'optimization' => [
                'allocations',
                'total_allocated',
                'roi_estimate',
                'strategy_summary'
            ]
        ]);
    }

    /** @test */
    public function test_budget_optimization_validates_required_fields()
    {
        $this->actingAs($this->admin);

        // Test missing total_budget
        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'event_ids' => [$this->event->id]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['total_budget']);

        // Test missing event_ids
        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 50000
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['event_ids']);

        // Test empty event_ids
        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 50000,
            'event_ids' => []
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['event_ids']);
    }

    /** @test */
    public function test_budget_optimization_validates_budget_amount()
    {
        $this->actingAs($this->admin);

        // Test budget too low
        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 500,
            'event_ids' => [$this->event->id]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['total_budget']);

        // Test valid budget
        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 10000,
            'event_ids' => [$this->event->id]
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_budget_optimization_validates_event_existence()
    {
        $this->actingAs($this->admin);

        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 50000,
            'event_ids' => [99999] // Non-existent event
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['event_ids.0']);
    }

    /** @test */
    public function test_proposal_generation_requires_valid_allocations()
    {
        $this->actingAs($this->admin);

        $allocations = [
            [
                'partner_id' => $this->partner->id,
                'event_id' => $this->event->id,
                'amount' => 10000,
                'type' => 'argent'
            ]
        ];

        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => $allocations
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure([
            'success',
            'proposals' => [
                '*' => [
                    'partner',
                    'event',
                    'proposal' => [
                        'subject',
                        'greeting',
                        'introduction',
                        'proposal_details',
                        'benefits',
                        'call_to_action',
                        'closing',
                        'signature'
                    ],
                    'allocation'
                ]
            ]
        ]);
    }

    /** @test */
    public function test_proposal_generation_validates_allocations()
    {
        $this->actingAs($this->admin);

        // Test missing allocations
        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['allocations']);

        // Test empty allocations
        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => []
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['allocations']);

        // Test invalid partner_id
        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => [
                [
                    'partner_id' => 99999,
                    'event_id' => $this->event->id,
                    'amount' => 10000,
                    'type' => 'argent'
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['allocations.0.partner_id']);

        // Test invalid event_id
        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => [
                [
                    'partner_id' => $this->partner->id,
                    'event_id' => 99999,
                    'amount' => 10000,
                    'type' => 'argent'
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['allocations.0.event_id']);

        // Test invalid amount
        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => [
                [
                    'partner_id' => $this->partner->id,
                    'event_id' => $this->event->id,
                    'amount' => 50, // Too low
                    'type' => 'argent'
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['allocations.0.amount']);

        // Test invalid type
        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => [
                [
                    'partner_id' => $this->partner->id,
                    'event_id' => $this->event->id,
                    'amount' => 10000,
                    'type' => 'invalid_type'
                ]
            ]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['allocations.0.type']);
    }

    /** @test */
    public function test_proposal_generation_accepts_valid_sponsoring_types()
    {
        $this->actingAs($this->admin);

        $validTypes = ['argent', 'materiel', 'logistique', 'autre'];

        foreach ($validTypes as $type) {
            $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
                'allocations' => [
                    [
                        'partner_id' => $this->partner->id,
                        'event_id' => $this->event->id,
                        'amount' => 10000,
                        'type' => $type
                    ]
                ]
            ]);

            $response->assertStatus(200);
            $response->assertJson(['success' => true]);
        }
    }

    /** @test */
    public function test_export_proposals_requires_session_data()
    {
        $this->actingAs($this->admin);

        // Test without session data
        $response = $this->get(route('sponsoring-builder.export'));

        $response->assertRedirect(route('sponsoring-builder.results'));
        $response->assertSessionHas('error', 'Aucune proposition à exporter. Veuillez d\'abord générer des propositions.');
    }

    /** @test */
    public function test_results_page_requires_session_data()
    {
        $this->actingAs($this->admin);

        // Test without session data
        $response = $this->get(route('sponsoring-builder.results'));

        $response->assertRedirect(route('sponsoring-builder.index'));
        $response->assertSessionHas('error', 'Aucun résultat d\'optimisation trouvé. Veuillez d\'abord optimiser un budget.');
    }

    /** @test */
    public function test_sponsoring_builder_displays_events_and_partners()
    {
        $this->actingAs($this->admin);

        // Create additional test data
        $event2 = Event::factory()->create([
            'title' => 'Marketing Summit',
            'categorie_id' => $this->category->id
        ]);

        $partner2 = Partner::factory()->create([
            'nom' => 'Google',
            'contact' => 'Jane Smith',
            'email' => 'jane@google.com'
        ]);

        $response = $this->get(route('sponsoring-builder.index'));

        $response->assertStatus(200);
        $response->assertViewHas('events');
        $response->assertViewHas('partners');

        $events = $response->viewData('events');
        $partners = $response->viewData('partners');

        $this->assertCount(2, $events);
        $this->assertCount(2, $partners);
        $this->assertTrue($events->contains('title', 'Tech Summit 2024'));
        $this->assertTrue($events->contains('title', 'Marketing Summit'));
        $this->assertTrue($partners->contains('nom', 'Microsoft'));
        $this->assertTrue($partners->contains('nom', 'Google'));
    }

    /** @test */
    public function test_budget_optimization_handles_api_errors_gracefully()
    {
        $this->actingAs($this->admin);

        // Mock API failure by using invalid configuration
        config(['services.openrouter.api_key' => null]);

        $response = $this->postJson(route('sponsoring-builder.optimize'), [
            'total_budget' => 50000,
            'event_ids' => [$this->event->id],
            'preferences' => 'Test preferences'
        ]);

        // Should still return success with fallback data
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $optimization = $response->json('optimization');
        $this->assertArrayHasKey('allocations', $optimization);
        $this->assertArrayHasKey('total_allocated', $optimization);
        $this->assertArrayHasKey('roi_estimate', $optimization);
        $this->assertArrayHasKey('strategy_summary', $optimization);
    }

    /** @test */
    public function test_proposal_generation_handles_api_errors_gracefully()
    {
        $this->actingAs($this->admin);

        // Mock API failure
        config(['services.openrouter.api_key' => null]);

        $response = $this->postJson(route('sponsoring-builder.generate-proposals'), [
            'allocations' => [
                [
                    'partner_id' => $this->partner->id,
                    'event_id' => $this->event->id,
                    'amount' => 10000,
                    'type' => 'argent'
                ]
            ]
        ]);

        // Should still return success with fallback data
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $proposals = $response->json('proposals');
        $this->assertCount(1, $proposals);
        
        $proposal = $proposals[0]['proposal'];
        $this->assertArrayHasKey('subject', $proposal);
        $this->assertArrayHasKey('greeting', $proposal);
        $this->assertArrayHasKey('introduction', $proposal);
        $this->assertArrayHasKey('proposal_details', $proposal);
        $this->assertArrayHasKey('benefits', $proposal);
        $this->assertArrayHasKey('call_to_action', $proposal);
        $this->assertArrayHasKey('closing', $proposal);
        $this->assertArrayHasKey('signature', $proposal);
    }
}