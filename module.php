<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Modules\Careers\Applications;

class Module extends \Icybee\Module
{
	const DIRECTORY_NAME = 'careers-applications';

	protected function get_views()
	{
		return array
		(
			'apply' => array
			(
				'title' => 'Unsollicited application form',
				'renders' => \Icybee\Views\View::RENDERS_OTHER
			)
		);
	}

	/**
	 * Creates the `careers-applications` folder.
	 *
	 * @see ICanBoogie.Module::install()
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
	 *
	 * @see ICanBoogie.Module::is_installed()
	 */
	public function is_installed(\ICanBoogie\Errors $errors)
	{
		global $core;

		$root = \ICanBoogie\DOCUMENT_ROOT;
		$path = $core->config['repository.files'] . DIRECTORY_SEPARATOR . self::DIRECTORY_NAME . DIRECTORY_SEPARATOR;

		if (!is_dir($root . $path))
		{
			$errors[$this->id] = t('The %directory directory is missing.', array('%directory' => $path));

			return false;
		}

		return parent::is_installed($errors);
	}
}