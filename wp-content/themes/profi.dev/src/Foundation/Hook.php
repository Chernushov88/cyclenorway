<?php

namespace ProfiDev\Foundation;

use ProfiDev\Contracts\Hook\HookActivation;

class Hook {
	/**
	 * @param string $fileName
	 * @param string $label
	 * @param HookActivation $defaultValue enable, disable, enable-dev
	 * @param bool $showInCustomizer
	 */
	public function __construct(public string $fileName, public string $label, public HookActivation $defaultValue = HookActivation::ENABLED, public bool $showInCustomizer = false) {}

	/**
	 * Check if hook active
	 *
	 * @return bool
	 */
	public function isActive(): bool {
		$activation_status = HookActivation::tryFrom(get_theme_mod($this->fileName, $this->defaultValue->value)) ?? HookActivation::DISABLED;

		return match ($activation_status) {
			HookActivation::ENABLED => true,
			HookActivation::ENABLED_DEV => Application::getInstance()->env('SITE_ENV', 'production') !== 'production',
			default => false,
		};
	}
}