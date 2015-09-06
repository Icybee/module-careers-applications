<?php

namespace Icybee\Modules\Careers\Applications\Block\ManageBlock;

use Icybee\Block\ManageBlock\Column;
use Icybee\Block\ManageBlock\FilterDecorator;

class OfferColumn extends Column
{
	public function render_cell($record)
	{
		$property = $this->id;
		$offer_id = $record->$property;

		if (!$offer_id)
		{
			return new FilterDecorator($record, $property, $this->is_filtering,
				'<em class="small">' . $this->t('Unsolicited application') . '</em>');
		}

		return new FilterDecorator($record, $property, $this->is_filtering,
			$record->offer
				? $record->offer->title
				: '<em class="warn">' . $this->t('Unknown offer: {0}', [ $offer_id ]) . '</em>');
	}
}
