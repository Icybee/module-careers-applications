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

use ICanBoogie\ActiveRecord\CreatedAtProperty;

class Application extends \ICanBoogie\ActiveRecord
{
	const MODEL_ID = 'careers.applications';

	public $application_id;
	public $offer_id;
	public $site_id;
	public $firstname;
	public $lastname;
	public $email;
	public $study_level;
	public $experience;
	public $cover_letter;
	public $cv_hash;

	use CreatedAtProperty;

	/**
	 * Returns the offer associated with the application.
	 *
	 * @return \Icybee\Modules\Careers\Offers\Offer
	 */
	protected function get_offer()
	{
		return $this->offer_id ? $this->app->models['careers.offers'][$this->offer_id] : null;
	}

	protected function get_cv_url()
	{
		$hash = $this->cv_hash;
		$repository = \ICanBoogie\REPOSITORY . Module::DIRECTORY_NAME . DIRECTORY_SEPARATOR;

		$matches = glob($repository . $hash . ".*");
		$pathname = current($matches);

		return \ICanBoogie\strip_root($pathname);
	}

	/**
	 * Returns the absolute URL to the CV file.
	 *
	 * @return string
	 */
	protected function get_absolute_cv_url()
	{
		return $this->app->site->url . $this->cv_url;
	}
}
