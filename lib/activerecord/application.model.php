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

use ICanBoogie\ActiveRecord\Query;
use ICanBoogie\DateTime;
use ICanBoogie\Uploaded;

class Model extends \ICanBoogie\ActiveRecord\Model
{
	public function save(array $properties, $key=null, array $options=array())
	{
		global $core;

		if (!$key && empty($properties['created_at']))
		{
			$properties['created_at'] = DateTime::now();
		}

		$root = \ICanBoogie\DOCUMENT_ROOT;
		$repository = $core->config['repository.files'] . DIRECTORY_SEPARATOR . \Icybee\Modules\Careers\Applications\Module::DIRECTORY_NAME . DIRECTORY_SEPARATOR;

		if (!is_dir($root . $repository))
		{
			throw new \Exception(\ICanBoogie\format('The repository %repository does not exists', array('%repository' => $repository)));
		}

		$info = null;

		if (!empty($properties['cv']) && $properties['cv'] instanceof Uploaded)
		{
			$file = $properties['cv'];
			$path = $repository . date('Ymd-His-') . \ICanBoogie\normalize($properties['lastname'] . '-' . $properties['firstname']) . $file->extension;

			$info = array($file, $path);
			$properties['cv'] = $path;
		}

		$rc = parent::save($properties, $key, $options);

		if ($rc && $info)
		{
			list($file, $path) = $info;

			$file->move($root . $path);
		}

		return $rc;
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
	 * @return Query
	 */
	protected function scope_visible(Query $query, $site_id=null)
	{
		global $core;

		return $query->where('site_id = 0 OR site_id = ?', $site_id ? $site_id : $core->site_id);
	}
}