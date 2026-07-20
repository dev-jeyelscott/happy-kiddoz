<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\RestrictsFileUploadsToSchemaComponents;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use UnitEnum;

class ManageSiteSettings extends Page
{
    use RestrictsFileUploadsToSchemaComponents;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?string $slug = 'settings';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.manage-site-settings';

    protected Width|string|null $maxContentWidth = Width::SevenExtraLarge;

    /**
     * Store the complete form state.
     *
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /**
     * Define the canonical settings and their database groups.
     *
     * Only these keys may be written through the administration page.
     *
     * @var array<string, list<string>>
     */
    private const SETTING_GROUPS = [
        'brand' => [
            'brand_name',
            'brand_tagline',
            'logo_path',
            'favicon_path',
        ],
        'marketplace' => [
            'primary_shop_url',
            'shopee_url',
            'lazada_url',
        ],
        'social' => [
            'facebook_url',
            'instagram_url',
        ],
        'contact' => [
            'contact_email',
            'contact_phone',
            'contact_viber',
            'contact_address',
            'contact_map_url',
        ],
        'seo' => [
            'meta_title',
            'meta_description',
        ],
        'legal' => [
            'privacy_policy_url',
            'terms_url',
            'copyright_text',
        ],
    ];

    /**
     * Load the canonical settings into the form.
     */
    public function mount(): void
    {
        $this->form->fill($this->settingsState());
    }

    /**
     * Build the typed site-settings form.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                $this->brandSection(),
                $this->marketplaceSection(),
                $this->socialSection(),
                $this->contactSection(),
                $this->seoSection(),
                $this->legalSection(),
            ])
            ->statePath('data');
    }

    /**
     * Validate and persist all canonical settings atomically.
     */
    public function save(): void
    {
        $state = $this->form->getState();

        DB::transaction(function () use ($state): void {
            foreach (self::SETTING_GROUPS as $group => $keys) {
                foreach ($keys as $key) {
                    SiteSetting::query()->updateOrCreate(
                        ['key' => $key],
                        [
                            'group' => $group,
                            'value' => $this->normalizeValue(
                                $state[$key] ?? null,
                            ),
                        ],
                    );
                }
            }
        });

        $this->form->fill($this->settingsState());

        Notification::make()
            ->title('Site settings saved')
            ->body('The global website settings have been updated.')
            ->success()
            ->send();
    }

    /**
     * Build branding and image-upload fields.
     */
    private function brandSection(): Section
    {
        return Section::make('Brand')
            ->description(
                'Manage the public brand name, tagline, logo, and browser icon.'
            )
            ->columns(2)
            ->schema([
                TextInput::make('brand_name')
                    ->label('Brand Name')
                    ->required()
                    ->maxLength(120),

                TextInput::make('brand_tagline')
                    ->label('Brand Tagline')
                    ->maxLength(240),

                $this->brandImageUpload(
                    name: 'logo_path',
                    label: 'Brand Logo',
                    maxSize: 2048,
                    helperText: 'Upload a PNG, JPEG, or WebP image up to 2 MB.',
                ),

                $this->brandImageUpload(
                    name: 'favicon_path',
                    label: 'Favicon',
                    maxSize: 512,
                    helperText: 'Upload a square PNG, JPEG, or WebP image up to 512 KB.',
                ),
            ]);
    }

    /**
     * Build marketplace-link fields.
     */
    private function marketplaceSection(): Section
    {
        return Section::make('Marketplace Links')
            ->description(
                'Buttons remain hidden on the public website when their URL is empty.'
            )
            ->columns(2)
            ->schema([
                $this->httpsUrlInput(
                    name: 'primary_shop_url',
                    label: 'Primary Shop URL',
                ),

                $this->httpsUrlInput(
                    name: 'shopee_url',
                    label: 'Shopee URL',
                ),

                $this->httpsUrlInput(
                    name: 'lazada_url',
                    label: 'Lazada URL',
                ),
            ]);
    }

    /**
     * Build social-media fields.
     */
    private function socialSection(): Section
    {
        return Section::make('Social Links')
            ->description(
                'Leave an unavailable platform empty instead of entering a placeholder URL.'
            )
            ->columns(2)
            ->schema([
                $this->httpsUrlInput(
                    name: 'facebook_url',
                    label: 'Facebook URL',
                ),

                $this->httpsUrlInput(
                    name: 'instagram_url',
                    label: 'Instagram URL',
                ),
            ]);
    }

    /**
     * Build contact-information fields.
     */
    private function contactSection(): Section
    {
        return Section::make('Contact Information')
            ->description(
                'These details will be used by the homepage contact section.'
            )
            ->columns(2)
            ->schema([
                TextInput::make('contact_email')
                    ->label('Email Address')
                    ->email()
                    ->maxLength(254),

                TextInput::make('contact_phone')
                    ->label('Phone Number')
                    ->tel()
                    ->maxLength(40),

                TextInput::make('contact_viber')
                    ->label('Viber Number')
                    ->tel()
                    ->maxLength(40),

                $this->httpsUrlInput(
                    name: 'contact_map_url',
                    label: 'Map URL',
                ),

                Textarea::make('contact_address')
                    ->label('Business Address')
                    ->rows(4)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Build search-engine metadata fields.
     */
    private function seoSection(): Section
    {
        return Section::make('SEO')
            ->description(
                'Configure the default title and description used by the public homepage.'
            )
            ->schema([
                TextInput::make('meta_title')
                    ->label('Meta Title')
                    ->required()
                    ->maxLength(120),

                Textarea::make('meta_description')
                    ->label('Meta Description')
                    ->required()
                    ->rows(4)
                    ->maxLength(320),
            ]);
    }

    /**
     * Build legal-link and footer fields.
     */
    private function legalSection(): Section
    {
        return Section::make('Legal and Footer')
            ->columns(2)
            ->schema([
                $this->httpsUrlInput(
                    name: 'privacy_policy_url',
                    label: 'Privacy Policy URL',
                ),

                $this->httpsUrlInput(
                    name: 'terms_url',
                    label: 'Terms and Conditions URL',
                ),

                TextInput::make('copyright_text')
                    ->label('Copyright Text')
                    ->maxLength(180)
                    ->columnSpanFull(),
            ]);
    }

    /**
     * Create a consistently secured brand-image upload field.
     */
    private function brandImageUpload(
        string $name,
        string $label,
        int $maxSize,
        string $helperText,
    ): FileUpload {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory('site/brand')
            ->visibility('public')
            ->image()
            ->acceptedFileTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
            ])
            ->maxSize($maxSize)
            ->preventFilePathTampering(
                allowFilePathUsing: fn (string $file): bool => Str::startsWith(
                    $file,
                    'site/brand/',
                ),
            )
            ->helperText($helperText);
    }

    /**
     * Create an optional HTTPS-only external URL field.
     */
    private function httpsUrlInput(
        string $name,
        string $label,
    ): TextInput {
        return TextInput::make($name)
            ->label($label)
            ->url()
            ->rule('url:https')
            ->maxLength(2048)
            ->helperText('Enter the complete HTTPS URL.');
    }

    /**
     * Load only canonical setting keys into the Livewire form state.
     *
     * @return array<string, string|null>
     */
    private function settingsState(): array
    {
        $storedSettings = SiteSetting::keyValueMap();
        $state = [];

        foreach (self::SETTING_GROUPS as $keys) {
            foreach ($keys as $key) {
                $state[$key] = $storedSettings[$key] ?? null;
            }
        }

        return $state;
    }

    /**
     * Convert blank strings to null before persistence.
     */
    private function normalizeValue(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
