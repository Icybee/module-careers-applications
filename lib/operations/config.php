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

class ConfigOperation extends \Icybee\ConfigOperation
{
	protected function lazy_get_properties()
	{
		$properties = parent::lazy_get_properties();

		$flat_id = $this->module->flat_id;

		$properties['local']["$flat_id.is_cv_required"] = !empty($properties['local']["$flat_id.is_cv_required"]);
		$properties['local']["$flat_id.is_notify"] = !empty($properties['local']["$flat_id.is_notify"]);

		return $properties;
	}
}