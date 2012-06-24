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

use ICanBoogie\ActiveRecord;

class DeleteBlock extends \Icybee\DeleteBlock
{
	/**
	 * Returns the record excerpt as preview.
	 *
	 * @see Icybee.DeleteBlock::render_preview()
	 */
	protected function render_preview(ActiveRecord $record)
	{
		return \Textmark_Parser::parse($record->cover_letter);
	}
}