<?php

use App\Filament\Resources\HomepageSections\HomepageSectionResource;
use App\Filament\Resources\HomepageSections\Pages\EditHomepageSection;
use App\Models\HomepageSection;
use App\Models\User;
use Database\Seeders\HomepageSectionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function (): void {
    /*
     * Configure the only email authorized to access the admin panel.
     */
    config()->set(
        'admin.seed_user.email',
        'admin@happy-kiddoz.test',
    );

    /*
     * HomepageSectionSeeder publishes homepage images.
     * Keep all generated files inside the test filesystem.
     */
    Storage::fake('public');

    $this->seed(HomepageSectionSeeder::class);
});

it('redirects guests away from homepage section administration', function (): void {
    $this->get(
        HomepageSectionResource::getUrl(
            'index',
            panel: 'admin',
        ),
    )->assertRedirect();
});

it('forbids authenticated users who are not the configured administrator', function (): void {
    $user = User::factory()->create([
        'email' => 'regular-user@happy-kiddoz.test',
    ]);

    $this->actingAs($user)
        ->get(
            HomepageSectionResource::getUrl(
                'index',
                panel: 'admin',
            ),
        )
        ->assertForbidden();
});

it('allows the configured administrator to access homepage sections', function (): void {
    $admin = User::factory()->create([
        'email' => 'admin@happy-kiddoz.test',
    ]);

    $this->actingAs($admin)
        ->get(
            HomepageSectionResource::getUrl(
                'index',
                panel: 'admin',
            ),
        )
        ->assertOk();
});

it('rejects non-image uploads for homepage section images', function (): void {
    $admin = User::factory()->create([
        'email' => 'admin@happy-kiddoz.test',
    ]);

    $this->actingAs($admin);

    $hero = HomepageSection::query()
        ->where('key', 'hero')
        ->firstOrFail();

    $invalidUpload = UploadedFile::fake()->create(
        name: 'payload.txt',
        kilobytes: 10,
        mimeType: 'text/plain',
    );

    Livewire::test(EditHomepageSection::class, [
        'record' => $hero->getRouteKey(),
    ])
        ->fillForm([
            'content.image_path' => $invalidUpload,
        ])
        ->call('save')
        ->assertHasFormErrors([
            'content.image_path',
        ]);

    $hero->refresh();

    expect($hero->content['image_path'])
        ->not->toBe('payload.txt');
});
