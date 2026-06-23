<?php

namespace ProfiDev\Container;

use Exception;

class Container
{
	protected array $bindings = [];
	protected array $instances = [];
	protected array $aliases = [];

	/**
	 * Bind a service to the container
	 */
	public function bind(string $name, callable $resolver, bool $singleton = false): void
	{
		$this->bindings[$name] = [
			'resolver' => $resolver,
			'singleton' => $singleton,
		];
	}

	/**
	 * Bind a singleton (always same instance)
	 */
	public function singleton(string $name, callable $resolver): void
	{
		$this->bind($name, $resolver, true);
	}

	/**
	 * Create an alias for a service
	 */
	public function alias(string $alias, string $target): void
	{
		$this->aliases[$alias] = $target;
	}

	/**
	 * Resolve a service or alias
	 *
	 * @throws Exception
	 */
	public function make(string $name)
	{
		// Resolve alias → actual binding name
		if (isset($this->aliases[$name])) {
			$name = $this->aliases[$name];
		}

		// Return existing singleton instance
		if (isset($this->instances[$name])) {
			return $this->instances[$name];
		}

		if (!isset($this->bindings[$name])) {
			throw new Exception(sprintf(__("Service '%s' not found in container.", 'profidev-theme'), $name));
		}

		$binding = $this->bindings[$name];
		$object = $binding['resolver']($this);

		// Store singletons
		if ($binding['singleton']) {
			$this->instances[$name] = $object;
		}

		return $object;
	}
}