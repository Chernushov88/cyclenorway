<?php declare(strict_types=1);

if (!defined('ABSPATH')) {
	exit;
}
add_action('wp_body_open', function () {
	echo <<<EOL

<script>
(() => {
	"use strict"

	let cls=0
	new PerformanceObserver((entryList) => {
		for (const entry of entryList.getEntries()) {
			if (!entry.hadRecentInput) {
				cls += entry.value
				console.log("Current CLS value:", cls, entry)
			}
		}
	}).observe({ type: "layout-shift", buffered: true })
})()
</script>
EOL;
});
