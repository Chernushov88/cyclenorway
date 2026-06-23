<?php declare(strict_types=1);

use ProfiDev\Foundation\Application;

acf_add_options_page(array(
	'page_title'    => 'SMTP Settings',
	'menu_title'    => 'SMTP Settings',
	'parent_slug'   => 'options-general.php',
	'menu_slug'     => 'smtp-settings',
	'capability'    => 'manage_options',
	'redirect'      => false
));

add_action(
	'wp_mail_failed',
	function ($wp_error) {
		file_put_contents(WP_CONTENT_DIR . '/mail.log', $wp_error->get_error_message() . "\n", FILE_APPEND);
	},
	10,
	1
);

add_filter(
	'wp_mail_content_type',
	function () {
		return 'text/html';
	}
);

add_action(
	'phpmailer_init',
	function ($phpmailer) {
		$params = [
			'SMTP_HOST' => getenv('SMTP_HOST'),
			'SMTP_AUTH' => getenv('SMTP_AUTH'),
			'SMTP_PORT' => getenv('SMTP_PORT'),
			'SMTP_USERNAME' => getenv('SMTP_USERNAME'),
			'SMTP_PASSWORD' => getenv('SMTP_PASSWORD'),
			'SMTP_SECURE' => getenv('SMTP_SECURE'),
			'SMTP_FROM' => getenv('SMTP_FROM'),
			'SMTP_FROMNAME' => getenv('SMTP_FROMNAME'),
		];

		if ( Application::getInstance()->env("SITE_ENV", "production") === "production") {
			$params = [
				'SMTP_HOST' => get_field('SMTP_HOST', 'options'),
				'SMTP_AUTH' => get_field('SMTP_AUTH', 'options'),
				'SMTP_PORT' => get_field('SMTP_PORT', 'options'),
				'SMTP_USERNAME' => get_field('SMTP_USERNAME', 'options'),
				'SMTP_PASSWORD' => get_field('SMTP_PASSWORD', 'options'),
				'SMTP_SECURE' => get_field('SMTP_SECURE', 'options'),
				'SMTP_FROM' => get_field('SMTP_FROM', 'options'),
				'SMTP_FROMNAME' => get_field('SMTP_FROMNAME', 'options'),
			];
		}

		$phpmailer->isSMTP();
		$phpmailer->Host = $params['SMTP_HOST'];
		$phpmailer->SMTPAuth = $params['SMTP_AUTH'];
		$phpmailer->Port = $params['SMTP_PORT'];
		$phpmailer->Username = $params['SMTP_USERNAME'];
		$phpmailer->Password = $params['SMTP_PASSWORD'];
		$phpmailer->SMTPSecure = $params['SMTP_SECURE'];
		$phpmailer->From = $params['SMTP_FROM'];
		$phpmailer->FromName = $params['SMTP_FROMNAME'];
	}
);

add_filter(
	'wp_mail_from',
	function () {
		return get_bloginfo('admin_email');
	}
);
