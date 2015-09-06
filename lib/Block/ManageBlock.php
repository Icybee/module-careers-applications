<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Careers\Applications\Block;

use ICanBoogie\ActiveRecord\Query;

use Brickrouge\Document;

use Icybee\Block\ManageBlock\DateTimeColumn;
use Icybee\Modules\Careers\Applications\Module;

class ManageBlock extends \Icybee\Block\ManageBlock
{
	protected static function add_assets(Document $document)
	{
		parent::add_assets($document);

		$document->css->add(\Icybee\Modules\Careers\Applications\DIR . 'public/admin.css');
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

			'name' => ManageBlock\NameColumn::class,
			'cv' => ManageBlock\CVColumn::class,
			'email' => ManageBlock\EmailColumn::class,
			'offer_id' => ManageBlock\OfferColumn::class,
			'created_at' => DateTimeColumn::class,

		];
	}
}
