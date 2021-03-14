<?php

namespace V17Development\FlarumBadges\BadgeCategory\Command;

use Flarum\Foundation\ValidationException;
use Flarum\Settings\SettingsRepositoryInterface;
use Symfony\Component\Translation\TranslatorInterface;
use V17Development\FlarumBadges\BadgeCategory\BadgeCategory;
use V17Development\FlarumBadges\BadgeCategory\BadgeCategoryValidator;
use Illuminate\Support\Arr;

class CreateBadgeCategoryHandler
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var BadgeCategoryValidator
     */
    protected $validator;

    /**
     * @param TranslatorInterface $translator
     * @param SettingsRepositoryInterface $settings
     */
    public function __construct(
        TranslatorInterface $translator, 
        SettingsRepositoryInterface $settings,
        BadgeCategoryValidator $validator
    ) {
        $this->translator = $translator;
        $this->settings = $settings;
        $this->validator = $validator;
    }

    /**
     * @param CreateBadgeCategory $command
     */
    public function handle(CreateBadgeCategory $command)
    {
        $command->actor->assertAdmin();

        // Build category
        $badgeCategory = BadgeCategory::build(
            Arr::get($command->data, "attributes.name", null),
            Arr::get($command->data, "attributes.description", null),
            Arr::get($command->data, "attributes.isEnabled", true),
        );

        // Validate
        $this->validator->assertValid($badgeCategory->getDirty());

        // Create category
        $badgeCategory->save();

        return $badgeCategory;
    }
}