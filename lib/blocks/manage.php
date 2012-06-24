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

use ICanBoogie\ActiveRecord\Query;

use Brickrouge\Document;

class ManageBlock extends \WdManager
{
	protected static function add_assets(Document $document)
	{
		parent::add_assets($document);

		$document->css->add('../../public/admin.css');
	}

	public function __construct(Module $module, array $attributes=array())
	{
		parent::__construct
		(
			$module, $attributes + array
			(
				self::T_KEY => 'application_id',
				self::T_COLUMNS_ORDER => array('lastname', 'cv', 'email', 'offer_id', 'created'),
				self::T_ORDER_BY => array('created', 'desc')
			)
		);
	}

	protected function alter_query(Query $query, array $filters)
	{
		global $core;

		$query = parent::alter_query($query, $filters);

		return $query->visible;
	}

	protected function columns()
	{
		return parent::columns() + array
		(
			'lastname' => array
			(
				'label' => 'Name'
			),

			'cv' => array
			(
				'orderable' => false
			),

			'email' => array
			(
				'discreet' => true
			),

			'offer_id' => array
			(
				'label' => 'Offer'
			),

			'created' => array
			(
				self::COLUMN_HOOK => array($this, 'render_cell_datetime'),
				'class' => 'date'
			)
		);
	}

	protected function render_cell_lastname($record, $property)
	{
		$lastname = $record->$property;
		$firstname = $record->firstname;

		$rc = parent::modify_code($lastname . ' ' . $firstname, $record->application_id, $this);

		if ($record->cover_letter)
		{
			$rc .= '<div class="excerpt">' . \ICanBoogie\excerpt($record->cover_letter, 32) . '</div>';
		}

		return $rc;
	}

	protected function render_cell_offer_id($record, $property)
	{
		$offer_id = $record->$property;

		if (!$offer_id)
		{
			return parent::render_filter_cell($record, $property, '<em class="small">' . t('Unsolicited application') . '</em>');
		}

		return parent::render_filter_cell($record, $property, $record->offer ? $record->offer->title : '<em class="warn">Unknown offer: ' . $offer_id . '</em>');
	}

	protected function render_cell_cv($record, $property)
	{
		static $last;

		$path = $record->$property;

		if (!$path)
		{
			return;
		}

		if ($path == $last)
		{
			return self::REPEAT_PLACEHOLDER;
		}

		$last = $path;
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