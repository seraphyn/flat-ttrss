<?php
/* Requires php-imap
	Put the following options in config.php:

	define('IMAP_AUTH_SERVER', 'your.imap.server:port');
	define('IMAP_AUTH_OPTIONS', '/tls/novalidate-cert/norsh');
	// More about options: http://php.net/manual/ru/function.imap-open.php

*/
class Auth_Imap extends Plugin implements IAuthModule {

	private $host;
	private $base;

	function about() {
		return array(1.0,
			"Authenticates against an IMAP server (configured in config.php)",
			"fox",
			true);
	}

	function init($host) {
		$this->host = $host;
		$this->base = new Auth_Base();

		$host->add_hook($host::HOOK_AUTH_USER, $this);
	}

	function authenticate($login, $password) {

		if ($login && $password) {
			$imap = imap_open(
				"{".IMAP_AUTH_SERVER.IMAP_AUTH_OPTIONS."}INBOX",
				$login,
				$password);

			if ($imap) {
				imap_close($imap);

				return $this->base->auto_create_user($login);
			}
		}

		return false;
	}

	function api_version() {
		return 2;
	}

}

?>
