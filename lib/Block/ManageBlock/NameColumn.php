<?php

namespace Icybee\Modules\Careers\Applications\Block\ManageBlock;

use Icybee\Block\ManageBlock\Column;
use Icybee\Block\ManageBlock\EditDecorator;

class NameColumn extends Column
{
	public function render_cell($record)
	{
		$lastname = $record->lastname;
		$firstname = $record->firstname;

		$rc = new EditDecorator($lastname . ' ' . $firstname, $record);

		if ($record->cover_letter)
		{
			$rc .= '<div class="excerpt small">' . \ICanBoogie\excerpt($record->cover_letter, 32) . '</div>';
		}

		return $rc;
	}
}
