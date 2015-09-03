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

use Brickrouge\Document;

class ManageBlock extends \Icybee\ManageBlock
{
	protected static function add_assets(Document $document)
	{
		parent::add_assets($document);

		$document->css->add(DIR . 'public/admin.css');
	}

	public function __construct(Module $module, array $attributes=[])
	{
		parent::__construct($module, $attributes + [

			self::T_COLUMNS_ORDER => [ 'name', 'cv', 'email', 'offer_id', 'created_at' ],
			self::T_ORDER_BY => [ 'created_at', 'desc' ]

		]);
	}

	protected function alter_query(Query $query, array $filters)
	{
		$query = parent::alter_query($query, $filters);

		return $query->visible;
	}

	protected function get_available_columns()
	{
		return parent::get_available_columns() + [

			'name' => __CLASS__ . '\NameColumn',
			'cv' => __CLASS__ . '\CVColumn',
			'email' => __CLASS__ . '\EmailColumn',
			'offer_id' => __CLASS__ . '\OfferColumn',
			'created_at' => 'Icybee\ManageBlock\DateTimeColumn',

		];
	}
}

namespace Icybee\Modules\Careers\Applications\ManageBlock;

use Icybee\ManageBlock\Column;
use Icybee\ManageBlock\EditDecorator;
use Icybee\ManageBlock\FilterDecorator;

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

class CVColumn extends Column
{
	public function render_cell($record)
	{
		$path = $record->cv_url;

		if (!$path)
		{
			return;
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

class EmailColumn extends Column
{

}

class OfferColumn extends Column
{
	public function render_cell($record)
	{
		$property = $this->id;
		$offer_id = $record->$property;

		if (!$offer_id)
		{
			return new FilterDecorator
			(
				$record,
				$property,
				$this->is_filtering,
				'<em class="small">' . $this->t('Unsolicited application') . '</em>'
			);
		}

		return new FilterDecorator
		(
			$record,
			$property,
			$this->is_filtering,
			$record->offer ? $record->offer->title : '<em class="warn">' . $this->t('Unknown offer: {0}', [ $offer_id ]) . '</em>'
		);
	}
}
