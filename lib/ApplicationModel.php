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

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\ActiveRecord\Query;
use ICanBoogie\DateTime;
use ICanBoogie\HTTP\File;

class ApplicationModel extends Model
{
	public function save(array $properties, $key=null, array $options=[])
	{
		if (!$key && empty($properties['created_at']))
		{
			$properties['created_at'] = DateTime::now();
		}

		return parent::save($properties, $key, $options);
	}

	/*
	 * TODO-20140123: because we are now using file hashes, we can't remove a file associated with
	 * an application, we need to check which files are no longer used and remove these.
	 *
	public function delete($key)
	{
		$root = \ICanBoogie\DOCUMENT_ROOT;
		$path = $this->select('cv')->where('{primary} = ?', $key)->rc;

		if ($path && file_exists($root . $path))
		{
			unlink($root . $path);
		}

		return parent::delete($key);
	}
	*/

	/**
	 * Alerts the query to match records visible on the current website.
	 *
	 * @param Query $query
	 *
	 * @param int|null $site_id Identifier of the website. I `null` the identifier of the current
	 * website is used instead.
	 *
	 * @return Query
	 */
	protected function scope_visible(Query $query, $site_id=null)
	{
		if ($site_id === null)
		{
			$site_id = $this->app->site_id;
		}

		return $query->and('site_id = 0 OR site_id = ?', $site_id);
	}
}
