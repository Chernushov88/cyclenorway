<?php

namespace ProfiDev\Contracts\Hook;

enum HookActivation: string {
	case ENABLED = 'enabled';
	case DISABLED = 'disabled';
	case ENABLED_DEV = 'enabled-dev';

	public static function options(): array {
		return [
			HookActivation::ENABLED->value => __('Enabled', 'profidev-theme'),
			HookActivation::DISABLED->value => __('Disabled', 'profidev-theme'),
			HookActivation::ENABLED_DEV->value => __('Enabled Dev', 'profidev-theme'),
		];
	}
}