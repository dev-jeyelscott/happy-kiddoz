<?php

namespace App\Filament\Resources\HomepageSections\Schemas;

use App\Enums\HomepageAdvantageIcon;
use App\Models\HomepageSection;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HomepageSectionForm
{
    /**
     * Build the complete homepage section editing schema.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                self::settingsSection(),
                self::heroSection(),
                self::categoriesSection(),
                self::aboutSection(),
                self::advantagesSection(),
                self::reviewsSection(),
                self::faqSection(),
                self::contactSection(),
            ]);
    }

    /**
     * Display immutable section metadata and editable visibility.
     */
    private static function settingsSection(): Section
    {
        return Section::make('Section Settings')
            ->description(
                'Section identifiers and ordering are controlled by the approved homepage design.'
            )
            ->columns(3)
            ->schema([
                TextInput::make('key')
                    ->label('Section')
                    ->formatStateUsing(
                        fn (mixed $state): string => Str::of(
                            $state instanceof BackedEnum
                                ? (string) $state->value
                                : (string) $state
                        )->headline()->toString()
                    )
                    ->disabled()
                    ->dehydrated(false),

                TextInput::make('sort_order')
                    ->label('Display Order')
                    ->numeric()
                    ->disabled()
                    ->dehydrated(false),

                Toggle::make('is_visible')
                    ->label('Visible on Homepage')
                    ->helperText(
                        'Hidden sections will not appear in the public homepage or navigation.'
                    ),
            ]);
    }

    /**
     * Build the hero-section fields using the canonical hero content contract.
     */
    private static function heroSection(): Section
    {
        return Section::make('Hero Content')
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'hero'
            )
            ->columns(2)
            ->schema([
                TextInput::make('content.eyebrow')
                    ->label('Eyebrow Badge')
                    ->required()
                    ->maxLength(120)
                    ->columnSpanFull(),

                TextInput::make('content.headline')
                    ->label('Headline')
                    ->required()
                    ->maxLength(180)
                    ->columnSpanFull(),

                Textarea::make('content.description')
                    ->label('Description')
                    ->required()
                    ->rows(4)
                    ->maxLength(600)
                    ->columnSpanFull(),

                self::imageUpload(
                    name: 'content.image_path',
                    label: 'Hero Image',
                    directory: 'homepage/hero',
                    minWidth: 900,
                    minHeight: 900,
                ),

                TextInput::make('content.image_alt')
                    ->label('Hero Image Alt Text')
                    ->required()
                    ->maxLength(180),

                TextInput::make('content.rating_value')
                    ->label('Rating')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(5)
                    ->step(0.1)
                    ->required(),

                TextInput::make('content.rating_statement')
                    ->label('Rating Statement')
                    ->required()
                    ->maxLength(180),
            ]);
    }

    /**
     * Build the fixed eight-category editing fields.
     */
    private static function categoriesSection(): Section
    {
        return Section::make('Shop by Category')
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'categories'
            )
            ->schema([
                TextInput::make('content.eyebrow')
                    ->label('Eyebrow')
                    ->required()
                    ->maxLength(80),

                TextInput::make('content.heading')
                    ->label('Section Heading')
                    ->required()
                    ->maxLength(180),

                Repeater::make('content.items')
                    ->label('Categories')
                    ->minItems(8)
                    ->maxItems(8)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->collapsible()
                    ->itemLabel(
                        fn (array $state): ?string => $state['title'] ?? null
                    )
                    ->columns(2)
                    ->schema([
                        /*
                        * Preserve the canonical fixed ordering without exposing
                        * it as an editable administrator field.
                        */
                        Hidden::make('sort_order')
                            ->required(),

                        TextInput::make('title')
                            ->label('Category Title')
                            ->required()
                            ->maxLength(100),

                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->maxLength(240)
                            ->columnSpanFull(),

                        self::imageUpload(
                            name: 'image_path',
                            label: 'Category Image',
                            directory: 'homepage/categories',
                            minWidth: 800,
                            minHeight: 600,
                        ),

                        TextInput::make('image_alt')
                            ->label('Image Alt Text')
                            ->required()
                            ->maxLength(180)
                            ->helperText(
                                'Describe the image itself instead of repeating the category title.'
                            ),

                        TextInput::make('external_url')
                            ->label('External Category URL')
                            ->url()
                            ->rule('url:https')
                            ->maxLength(2048)
                            ->columnSpanFull()
                            ->helperText(
                                'Leave empty until the client supplies an approved HTTPS destination.'
                            ),
                    ]),
            ]);
    }

    /**
     * Build the About section fields.
     */
    private static function aboutSection(): Section
    {
        return Section::make('About Happy Kiddoz')
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'about'
            )
            ->columns(2)
            ->schema([
                TextInput::make('content.eyebrow')
                    ->label('Eyebrow')
                    ->required()
                    ->maxLength(80),

                TextInput::make('content.heading')
                    ->label('Heading')
                    ->required()
                    ->maxLength(180),

                Repeater::make('content.paragraphs')
                    ->label('Story Paragraphs')
                    ->simple(
                        Textarea::make('paragraph')
                            ->required()
                            ->rows(4)
                            ->maxLength(1200)
                    )
                    ->minItems(1)
                    ->maxItems(5)
                    ->columnSpanFull(),

                self::imageUpload(
                    name: 'content.image_path',
                    label: 'About Image',
                    directory: 'homepage/about',
                    minWidth: 900,
                    minHeight: 900,
                ),

                TextInput::make('content.image_alt')
                    ->label('About Image Alt Text')
                    ->required()
                    ->maxLength(180),
            ]);
    }

    /**
     * Build the fixed five-advantage editing fields.
     */
    private static function advantagesSection(): Section
    {
        return Section::make('Happy Kiddoz Advantages')
            ->description(
                'The approved homepage design contains exactly five advantage cards.'
            )
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'advantages'
            )
            ->schema([
                TextInput::make('content.eyebrow')
                    ->label('Eyebrow')
                    ->required()
                    ->maxLength(120),

                TextInput::make('content.heading')
                    ->label('Section Heading')
                    ->required()
                    ->maxLength(180),

                Repeater::make('content.items')
                    ->label('Advantages')
                    ->minItems(5)
                    ->maxItems(5)
                    ->addable(false)
                    ->deletable(false)
                    ->reorderable(false)
                    ->collapsible()
                    ->itemLabel(
                        fn (array $state): ?string => $state['title'] ?? null
                    )
                    ->columns(2)
                    ->schema([
                        /*
                        * Preserve the fixed design order without exposing the
                        * ordering value to the administrator.
                        */
                        Hidden::make('sort_order')
                            ->required(),

                        Select::make('icon')
                            ->label('Icon')
                            ->required()
                            ->native(false)
                            ->selectablePlaceholder(false)
                            ->options(HomepageAdvantageIcon::options())
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(120),

                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->maxLength(400)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Build the customer-review fields.
     */
    private static function reviewsSection(): Section
    {
        return Section::make('Customer Reviews')
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'reviews'
            )
            ->schema([
                TextInput::make('content.heading')
                    ->label('Section Heading')
                    ->required()
                    ->maxLength(180),

                Repeater::make('content.items')
                    ->label('Reviews')
                    ->minItems(2)
                    ->maxItems(6)
                    ->collapsible()
                    ->itemLabel(
                        fn (array $state): ?string => $state['customer_name'] ?? null
                    )
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(120),

                        TextInput::make('customer_handle')
                            ->label('Customer Username or Handle')
                            ->maxLength(120),

                        TextInput::make('rating')
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->maxValue(5)
                            ->required(),

                        TextInput::make('avatar_initial')
                            ->label('Avatar Initial')
                            ->maxLength(2),

                        Textarea::make('quote')
                            ->label('Review')
                            ->required()
                            ->rows(5)
                            ->maxLength(700)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    /**
     * Build the optional FAQ fields.
     */
    private static function faqSection(): Section
    {
        return Section::make('Frequently Asked Questions')
            ->description(
                'Keep this section hidden until the client approves its content.'
            )
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'faq'
            )
            ->schema([
                TextInput::make('content.heading')
                    ->label('Section Heading')
                    ->required()
                    ->maxLength(180),

                Repeater::make('content.items')
                    ->label('Questions and Answers')
                    ->maxItems(12)
                    ->collapsible()
                    ->itemLabel(
                        fn (array $state): ?string => $state['question'] ?? null
                    )
                    ->schema([
                        TextInput::make('question')
                            ->required()
                            ->maxLength(240),

                        Textarea::make('answer')
                            ->required()
                            ->rows(5)
                            ->maxLength(1600),
                    ]),
            ]);
    }

    /**
     * Build the contact CTA fields.
     *
     * Contact details and external links remain global site settings.
     */
    private static function contactSection(): Section
    {
        return Section::make('Contact Call to Action')
            ->visible(
                fn (?HomepageSection $record): bool => self::sectionKey($record) === 'contact'
            )
            ->schema([
                TextInput::make('content.heading')
                    ->label('Heading')
                    ->required()
                    ->maxLength(180),

                Textarea::make('content.description')
                    ->label('Supporting Text')
                    ->required()
                    ->rows(5)
                    ->maxLength(800),
            ]);
    }

    /**
     * Create a consistently secured homepage image upload.
     */
    private static function imageUpload(
        string $name,
        string $label,
        string $directory,
        int $minWidth,
        int $minHeight,
    ): FileUpload {
        return FileUpload::make($name)
            ->label($label)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->acceptedFileTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
            ])
            ->maxSize(4096)
            ->rule(
                Rule::dimensions()
                    ->minWidth($minWidth)
                    ->minHeight($minHeight)
            )
            ->preventFilePathTampering()
            ->required();
    }

    /**
     * Normalize an enum-backed or string section key.
     */
    private static function sectionKey(
        ?HomepageSection $record
    ): ?string {
        $key = $record?->key;

        if ($key instanceof BackedEnum) {
            return (string) $key->value;
        }

        return $key !== null ? (string) $key : null;
    }
}
