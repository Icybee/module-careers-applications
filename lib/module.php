<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Careers\Applications;

use ICanBoogie\I18n\FormattedString;

use Icybee\Modules\Views\View;

class Module extends \Icybee\Module
{
	const DIRECTORY_NAME = 'careers-applications';

	protected function lazy_get_views()
	{
		return array
		(
			'apply' => array
			(
				View::TITLE => 'Unsollicited application form',
				View::RENDERS => View::RENDERS_OTHER
			)
		);
	}

	/**
	 * Creates the `careers-applications` folder.
	 */
	public function install(\ICanBoogie\Errors $errors)
	{
		global $core;

		$root = \ICanBoogie\DOCUMENT_ROOT;
		$path = $core->config['repository.files'] . DIRECTORY_SEPARATOR . self::DIRECTORY_NAME . DIRECTORY_SEPARATOR;

		if (!is_dir($root . $path))
		{
			mkdir($root . $path);
		}

		return parent::install($errors);
	}

	/**
	 * Checks if the `careers-applications` folder exists.
	 */
	public function is_installed(\ICanBoogie\Errors $errors)
	{
		global $core;

		$root = \ICanBoogie\DOCUMENT_ROOT;
		$path = $core->config['repository.files'] . DIRECTORY_SEPARATOR . self::DIRECTORY_NAME . DIRECTORY_SEPARATOR;

		if (!is_dir($root . $path))
		{
			$errors[$this->id] = new FormattedString('The %directory directory is missing.', array('%directory' => $path));

			return false;
		}

		return parent::is_installed($errors);
	}
}