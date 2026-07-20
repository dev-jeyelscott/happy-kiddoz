<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;

it('loads the homepage with one settings query and one sections query', function (): void {
    /*
     * Create every canonical homepage section so the request exercises the
     * complete section collection and header navigation data flow.
     */
    foreach (HomepageSectionKey::cases() as $sectionKey) {
        HomepageSection::factory()
            ->forKey($sectionKey)
            ->visible()
            ->create();
    }

    /*
     * Create one setting so the settings query returns realistic data.
     * Additional settings do not increase the query count.
     */
    SiteSetting::query()->create([
        'key' => 'brand_name',
        'value' => 'Happy Kiddos',
        'group' => 'branding',
    ]);

    /*
     * Start counting only after the test fixtures have been created.
     *
     * The request must execute:
     * 1. One query for all site settings.
     * 2. One query for all visible homepage sections.
     */
    $this->expectsDatabaseQueryCount(2);

    $this->get(route('home'))
        ->assertOk();
});
