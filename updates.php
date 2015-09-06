<?php

namespace Icybee\Modules\Careers\Applications;

use ICanBoogie\Updater\AssertionFailed;
use ICanBoogie\Updater\Update;

/**
 * - Rename table `jobs_applications` as `careers_applications`.
 * - Rename column `cover` as `cover_letter`.
 * - Update the column `cv` as `cv_hash` and move the files accordingly.
 *
 * @module careers.applications
 */
class Update20120101 extends Update
{
	public function update_table_sites()
	{
		$db = $this->app->db;

		if (!$db->table_exists('jobs_applications'))
		{
			throw new AssertionFailed('assert_table_exists', 'jobs_applications');
		}

		$db("RENAME TABLE `{prefix}jobs_applications` TO `{prefix}careers_applications`");
	}

	public function update_registry_keys()
	{
		$db = $this->app->db;
		$db("UPDATE {prefix}registry__site SET name = REPLACE(name, 'jobs_applications.', 'careers_applications.') WHERE name LIKE 'jobs_applications.%'");
		$db("UPDATE {prefix}registry__site SET name = REPLACE(name, 'views.targets.jobs_applications/', 'views.targets.careers_applications/') WHERE name LIKE 'views.targets.jobs_applications/%'");
	}

	public function update_column_cover()
	{
		$this->module->model
		->assert_has_column('cover')
		->rename_column('cover', 'cover_letter');
	}

	public function update_column_cv()
	{
		$model = $this->module->model;
		$model->assert_has_column('cv');

		if (!$model->has_column('cv_hash'))
		{
			$model->create_column('cv_hash');
		}

		$files = $model->select('cv, NULL')->where('cv != ""')->pairs;
		$update = $model->prepare('UPDATE `{self}` SET cv_hash = ? WHERE cv = ?');
		$root = getcwd();
		$destination_root = \ICanBoogie\REPOSITORY . 'careers-applications' . DIRECTORY_SEPARATOR;

		foreach ($files as $pathname => &$hash)
		{
			$current_pathname = $root . $pathname;

			if (!file_exists($current_pathname))
			{
				continue;
			}

			$hash = sha1_file($current_pathname);
			$update($hash, $pathname);
			$destination = $destination_root . $hash . '.' . pathinfo($pathname, PATHINFO_EXTENSION);

			if (file_exists($destination))
			{
				continue;
			}

			rename($current_pathname, $destination);
		}

		$model->remove_column('cv');
	}
}

/**
 * @module careers.applications
 */
class Update20140911 extends Update
{
	/**
	 * Rename column `siteid` as `site_id`.
	 */
	public function update_column_siteid()
	{
		$this->module->model
		->assert_has_column('siteid')
		->rename_column('siteid', 'site_id');
	}

	/**
	 * Rename column `created` as `created_at`.
	 */
	public function update_column_created_at()
	{
		$this->module->model
		->assert_has_column('created')
		->rename_column('created', 'created_at');
	}
}
