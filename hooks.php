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

class Hooks
{
	public static function dashboard_summary()
	{
		global $core;

		$core->document->css->add('public/dashboard.css');

		$last = $core->models['careers.applications']->visible->order('created DESC')->limit(3)->all;

		if (!$last)
		{
			return '<p class="nothing">No application yet</p>';
		}

		$last_date = null;

		$rc = '<table>';

		foreach ($last as $record)
		{
			$date = \ICanBoogie\I18n\date_period($record->created);

			if ($date === $last_date)
			{
				$date = '<span class="lighter">&mdash;</span>';
			}
			else
			{
				$last_date = $date;
			}

			$title = $record->firstname . ' ' . $record->lastname;
			$title = \ICanBoogie\shorten($title, 48);
			$title = \ICanBoogie\escape($title);

			$offer = $record->offer;

			if ($offer)
			{
				$offer_title = \ICanBoogie\shorten($offer->title, 32);
				$offer = "<a href=\"/admin/{$offer->constructor}/{$offer->nid}/edit\">" . \ICanBoogie\escape($offer_title) . '</a>';
			}
			else
			{
				$offer = '<em class="small light">' . t('Unsolicited application') . '</em>';
			}

			$excerpt = null;

			if ($record->cover_letter)
			{
				$excerpt = '<div class="excerpt">' . \ICanBoogie\shorten($record->cover_letter, 256) . '</div>';
			}

			$cv = null;
			$root = $_SERVER['DOCUMENT_ROOT'];
			$path = $record->cv;

			if ($path)
			{
				if (!file_exists($root . $path))
				{
					$cv = '<em class="warn small">Missing file: ' . basename($path) . '</em>';
				}
				else
				{
					$size = filesize($root . $path);
					$extension = '?';

					if (preg_match('#\..+$#', $path, $matches))
					{
						list($extension) = $matches;
					}

					$cv = '<a href="' . \ICanBoogie\escape($path) . '" class="download"><span class="small">' . \ICanBoogie\I18n\format_size($size) .  ' – ' . $extension . '</span></a>';
				}
			}

			if ($cv)
			{
				$cv = '<div class="cv">' . $cv . '</div>';
			}

			$rc .= <<<EOT
	<tr>
	<td class="date light">$date</td>
	<td class="title"><a href="{$core->site->path}/admin/careers.applications/{$record->application_id}/edit">{$title}</a> <span class="lighter">&mdash;</span> $offer
	$excerpt $cv
	</td>
	</tr>
EOT;
		}

		$rc .= '</table>';

		$path = $core->site->path;
		$count = $core->models['careers.applications']->visible->count;
		$txt_all = t('careers_applications.count', array(':count' => $count));

		$rc .= <<<EOT
<div class="panel-footer"><a href="$path/admin/careers.applications">$txt_all</a></div>
EOT;

		return $rc;
	}
}