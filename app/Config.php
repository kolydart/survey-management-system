<?php

namespace gateweb\mvc\app;
use \gateweb\mvc\core\Router;
/**
 * Application configuration
 *
 * PHP version 5.4
 */
class Config
{

	/**
	 * Database host
	 * @var string
	 */
	const DB_HOST = 'localhost';

	/**
	 * Database name
	 * @var string
	 */
	const DB_NAME = 'qst';

	/**
	 * Database user
	 * @var string
	 */
	const DB_USER = 'quest-sH84h';

	/**
	 * Database password
	 * @var string
	 */
	const DB_PASSWORD = 'OtorJMeJvk88ahMUHaEMSZWQ';

	/**
	 * Show or hide error messages on screen
	 * @var boolean
	 */
	const SHOW_ERRORS = true;

	/**
	 * root path
	 * put this file in Root folder (outsite /public) and you're done
	 * @var string path
	 */
	const PATH_ROOT = __DIR__;

	/**
	 * url base 
	 * remember to also change .htaccess
	 */
	const URL_BASE = '/qst';

	/**
	 * log dir. remember, it must be writable (and outside public dir)
	 */
	const LOG_DIR = '/logs';

}
