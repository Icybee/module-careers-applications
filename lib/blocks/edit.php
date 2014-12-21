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

use Brickrouge\Element;
use Brickrouge\Form;

/**
 * A block to edit job applications.
 */
class EditBlock extends \Icybee\EditBlock
{
	protected function lazy_get_attributes()
	{
		return \ICanBoogie\array_merge_recursive(parent::lazy_get_attributes(), [

			Element::GROUPS => [

				'more' => [

					'title' => 'Mobilité et Poste'

				],

				'taxonomy' => [

					'title' => 'Taxinomie'

				],

				'file' => [

					'title' => 'Fichiers associés'

				]
			]
		]);
	}

	protected function lazy_get_children()
	{
		$form_attributes = \Icybee\Modules\Careers\Applications\ApplyForm::tags($this->values);

		return array_merge(parent::lazy_get_children(), $form_attributes[Element::CHILDREN], [

			'email-confirm' => null

		]);
	}
}
