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

class Application extends \ICanBoogie\ActiveRecord
{
	/**
	 * Defaults the `$model` param to `careers.applications`.
	 *
	 * @param string $model
	 */
	public function __construct($model='careers.applications')
	{
		parent::__construct($model);
	}

	/**
	 * Returns the offer associated with the application.
	 *
	 * @return \Icybee\Modules\Careers\Offers\Offer
	 */
	protected function get_offer()
	{
		global $core;

		return $this->offer_id ? $core->models['careers.offers'][$this->offer_id] : null;
	}

	protected function get_cv()
	{
		return '/path/to/cs';
	}

	/**
	 * Returns the absolute URL to the CV file.
	 *
	 * @return string
	 */
	protected function get_absolute_cv_url()
	{
		global $core;

		return $core->site->url . $this->cv;
	}
}