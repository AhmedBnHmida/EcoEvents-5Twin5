<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Partner;
use App\Models\Sponsoring;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PartnerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $fournisseur;
    protected $participant;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users with different roles
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->fournisseur = User::factory()->create(['role' => 'fournisseur']);
        $this->participant = User::factory()->create(['role' => 'participant']);
    }

    /** @test */
    public function test_admin_can_view_partners_list()
    {
        $this->actingAs($this->admin);

        $partners = Partner::factory()->count(3)->create();

        $response = $this->get(route('partenaires.index'));

        $response->assertStatus(200);
        $response->assertViewIs('partenaires.index');
        $response->assertViewHas('partners');
    }

    /** @test */
    public function test_admin_can_create_partner_with_manual_entry()
    {
        $this->actingAs($this->admin);

        $partnerData = [
            'nom' => 'Microsoft France',
            'type' => 'Entreprise',
            'contact' => 'Jean Dupont',
            'email' => 'jean@microsoft.fr',
            'telephone' => '0123456789',
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertRedirect(route('partenaires.index'));
        $this->assertDatabaseHas('partners', [
            'nom' => 'Microsoft France',
            'type' => 'Entreprise',
            'contact' => 'Jean Dupont',
            'email' => 'jean@microsoft.fr',
        ]);
    }

    /** @test */
    public function test_admin_can_create_partner_linked_to_user()
    {
        $this->actingAs($this->admin);

        $partnerData = [
            'user_id' => $this->fournisseur->id,
            'nom' => 'TechCorp',
            'type' => 'Entreprise',
            'telephone' => '0123456789',
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertRedirect(route('partenaires.index'));
        
        $partner = Partner::where('nom', 'TechCorp')->first();
        $this->assertNotNull($partner);
        $this->assertEquals($this->fournisseur->id, $partner->user_id);
    }

    /** @test */
    public function test_partner_creation_requires_contact_and_email_without_user()
    {
        $this->actingAs($this->admin);

        $partnerData = [
            'nom' => 'Test Partner',
            'type' => 'Entreprise',
            'telephone' => '0123456789',
            // Missing contact and email without user_id
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertSessionHasErrors(['contact', 'email']);
    }

    /** @test */
    public function test_admin_can_upload_partner_logo()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->image('logo.jpg', 200, 200);

        $partnerData = [
            'nom' => 'LogoTest Partner',
            'type' => 'Entreprise',
            'contact' => 'Test Contact',
            'email' => 'test@example.com',
            'telephone' => '0123456789',
            'logo' => $file,
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertRedirect(route('partenaires.index'));
        
        $partner = Partner::where('nom', 'LogoTest Partner')->first();
        $this->assertNotNull($partner);
        $this->assertNotNull($partner->logo);
        Storage::disk('public')->assertExists($partner->logo);
    }

    /** @test */
    public function test_admin_can_view_single_partner()
    {
        $this->actingAs($this->admin);

        $partner = Partner::factory()->create();

        $response = $this->get(route('partenaires.show', $partner->id));

        $response->assertStatus(200);
        $response->assertViewIs('partenaires.show');
        $response->assertSee($partner->nom);
    }

    /** @test */
    public function test_admin_can_update_partner()
    {
        $this->actingAs($this->admin);

        $partner = Partner::factory()->create([
            'nom' => 'Old Name',
            'type' => 'Entreprise',
        ]);

        $updateData = [
            'nom' => 'New Name',
            'type' => 'Association',
            'contact' => 'Updated Contact',
            'email' => 'updated@example.com',
            'telephone' => '0987654321',
        ];

        $response = $this->put(route('partenaires.update', $partner->id), $updateData);

        $response->assertRedirect(route('partenaires.index'));
        $this->assertDatabaseHas('partners', [
            'id' => $partner->id,
            'nom' => 'New Name',
            'type' => 'Association',
        ]);
    }

    /** @test */
    public function test_admin_can_delete_partner()
    {
        $this->actingAs($this->admin);

        $partner = Partner::factory()->create();

        $response = $this->delete(route('partenaires.destroy', $partner->id));

        $response->assertRedirect(route('partenaires.index'));
        $this->assertDatabaseMissing('partners', ['id' => $partner->id]);
    }

    /** @test */
    public function test_deleting_partner_deletes_logo_file()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->image('logo.jpg');
        $path = $file->store('partners/logos', 'public');

        $partner = Partner::factory()->create(['logo' => $path]);

        Storage::disk('public')->assertExists($path);

        $this->delete(route('partenaires.destroy', $partner->id));

        Storage::disk('public')->assertMissing($path);
    }

    /** @test */
    public function test_partner_search_functionality()
    {
        $this->actingAs($this->admin);

        Partner::factory()->create(['nom' => 'Microsoft']);
        Partner::factory()->create(['nom' => 'Google']);
        Partner::factory()->create(['nom' => 'Amazon']);

        $response = $this->get(route('partenaires.index', ['search' => 'Google']));

        $response->assertStatus(200);
        $response->assertSee('Google');
        $response->assertDontSee('Microsoft');
        $response->assertDontSee('Amazon');
    }

    /** @test */
    public function test_partner_type_filter()
    {
        $this->actingAs($this->admin);

        Partner::factory()->create(['type' => 'Entreprise', 'nom' => 'Company A']);
        Partner::factory()->create(['type' => 'Association', 'nom' => 'Assoc B']);

        $response = $this->get(route('partenaires.index', ['type' => 'Entreprise']));

        $response->assertStatus(200);
        $response->assertSee('Company A');
        $response->assertDontSee('Assoc B');
    }

    /** @test */
    public function test_participant_cannot_create_partner()
    {
        $this->actingAs($this->participant);

        $partnerData = [
            'nom' => 'Test Partner',
            'type' => 'Entreprise',
            'contact' => 'Test',
            'email' => 'test@test.com',
            'telephone' => '0123456789',
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_participant_cannot_delete_partner()
    {
        $this->actingAs($this->participant);

        $partner = Partner::factory()->create();

        $response = $this->delete(route('partenaires.destroy', $partner->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function test_logo_validation_rejects_non_image_files()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        $partnerData = [
            'nom' => 'Test Partner',
            'type' => 'Entreprise',
            'contact' => 'Test',
            'email' => 'test@test.com',
            'telephone' => '0123456789',
            'logo' => $file,
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertSessionHasErrors(['logo']);
    }

    /** @test */
    public function test_logo_validation_rejects_oversized_files()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        // Create a 3MB file (over the 2MB limit)
        $file = UploadedFile::fake()->image('huge-logo.jpg')->size(3072);

        $partnerData = [
            'nom' => 'Test Partner',
            'type' => 'Entreprise',
            'contact' => 'Test',
            'email' => 'test@test.com',
            'telephone' => '0123456789',
            'logo' => $file,
        ];

        $response = $this->post(route('partenaires.store'), $partnerData);

        $response->assertSessionHasErrors(['logo']);
    }

    /** @test */
    public function test_partner_with_sponsorings_shows_count()
    {
        $this->actingAs($this->admin);

        $partner = Partner::factory()
            ->has(Sponsoring::factory()->count(3))
            ->create();

        $response = $this->get(route('partenaires.show', $partner->id));

        $response->assertStatus(200);
        $response->assertSee('3'); // Should show sponsoring count
    }

    /** @test */
    public function test_partner_contact_name_accessor_uses_user_name_when_linked()
    {
        $partner = Partner::factory()->create([
            'user_id' => $this->fournisseur->id,
            'contact' => 'Manual Contact',
        ]);

        $this->assertEquals($this->fournisseur->name, $partner->contact_name);
    }

    /** @test */
    public function test_partner_contact_name_accessor_uses_manual_contact_when_no_user()
    {
        $partner = Partner::factory()->create([
            'user_id' => null,
            'contact' => 'Manual Contact',
        ]);

        $this->assertEquals('Manual Contact', $partner->contact_name);
    }

    /** @test */
    public function test_partner_logo_url_accessor_returns_storage_path()
    {
        $partner = Partner::factory()->create([
            'logo' => 'partners/logos/test.jpg',
        ]);

        $this->assertStringContainsString('storage/partners/logos/test.jpg', $partner->logo_url);
    }

    /** @test */
    public function test_partner_logo_url_accessor_returns_default_when_no_logo()
    {
        $partner = Partner::factory()->create(['logo' => null]);

        $this->assertStringContainsString('default-partner-logo.png', $partner->logo_url);
    }

    /** @test */
    public function test_updating_partner_replaces_old_logo()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        // Create partner with old logo
        $oldFile = UploadedFile::fake()->image('old-logo.jpg');
        $oldPath = $oldFile->store('partners/logos', 'public');
        $partner = Partner::factory()->create(['logo' => $oldPath]);

        Storage::disk('public')->assertExists($oldPath);

        // Update with new logo
        $newFile = UploadedFile::fake()->image('new-logo.jpg');
        $updateData = [
            'nom' => $partner->nom,
            'type' => $partner->type,
            'contact' => $partner->contact,
            'email' => $partner->email,
            'telephone' => $partner->telephone,
            'logo' => $newFile,
        ];

        $this->put(route('partenaires.update', $partner->id), $updateData);

        // Old logo should be deleted
        Storage::disk('public')->assertMissing($oldPath);
        
        // New logo should exist
        $partner->refresh();
        $this->assertNotNull($partner->logo);
        $this->assertNotEquals($oldPath, $partner->logo);
        Storage::disk('public')->assertExists($partner->logo);
    }
}
