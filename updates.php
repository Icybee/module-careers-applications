<?php

namespace Icybee\Modules\Careers\Applications;

/**
 * @module careers.applications
 */
class Update20140911 extends \ICanBoogie\Updater\Update
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
