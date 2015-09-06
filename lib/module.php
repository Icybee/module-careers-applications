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

use ICanBoogie\Errors;

class Module extends \Icybee\Module
{
	const DIRECTORY_NAME = 'careers-applications';

	/**
	 * Creates the `careers-applications` folder.
	 */
	public function install(Errors $errors)
	{
		$directory = \ICanBoogie\REPOSITORY . self::DIRECTORY_NAME;

		if (!is_dir($directory))
		{
			mkdir($directory);
		}

		return parent::install($errors);
	}

	/**
	 * Checks if the `careers-applications` folder exists.
	 */
	public function is_installed(Errors $errors)
	{
		$directory = \ICanBoogie\REPOSITORY . self::DIRECTORY_NAME;

		if (!is_dir($directory))
		{
			$errors[$this->id]->add('The directory %directory is missing.', [

				'%directory' => $directory

			]);

			return false;
		}

		return parent::is_installed($errors);
	}
}
