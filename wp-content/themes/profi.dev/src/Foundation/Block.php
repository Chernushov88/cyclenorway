<?php

namespace ProfiDev\Foundation;

use ProfiDev\Contracts\Block\Block as BlockContract;

class Block implements BlockContract {
	public function __construct( public string $path ) {

	}

	public function register( array $metadata = [] ): void {
		$metadata['render_callback'] = [$this, 'renderCallback'];

		register_block_type( $metadata['name'], $metadata );
	}

	public function renderCallback($attributes, $content, $block) {
		// TODO: Render callback
	}
}