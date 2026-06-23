<?php

namespace ProfiDev\Contracts\Block;

interface Block {
	public function __construct(string $path);

	public function register(array $metadata = []): void;
}