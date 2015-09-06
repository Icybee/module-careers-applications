<?php

namespace Icybee\Modules\Careers\Applications\Block\ManageBlock;

use Icybee\Block\ManageBlock\Column;

class CVColumn extends Column
{
	public function render_cell($record)
	{
		$path = $record->cv_url;

		if (!$path)
		{
			return null;
		}

		$root = \ICanBoogie\DOCUMENT_ROOT;

		if (!file_exists($root . $path))
		{
			return '<em class="warn small">Missing file: ' . basename($path) . '</em>';
		}

		$size = filesize($root . $path);
		$extension = pathinfo($path, PATHINFO_EXTENSION) ?: '?';

		return '<a href="' . \ICanBoogie\escape($path) . '" class="btn small"><i class="icon-download-alt"></i> ' . \ICanBoogie\I18n\format_size($size) .  ' – .' . $extension . '</a>';
	}
}
