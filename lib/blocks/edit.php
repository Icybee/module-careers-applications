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

use Brickrouge\Element;
use Brickrouge\Form;

/**
 * A block to edit job applications.
 */
class EditBlock extends \Icybee\EditBlock
{
	protected function alter_attributes(array $attributes)
	{
		return \ICanBoogie\array_merge_recursive
		(
			parent::alter_attributes($attributes), array
			(
				Element::GROUPS => array
				(
					'more' => array
					(
						'title' => 'Mobilité et Poste'
					),

					'taxonomy' => array
					(
						'title' => 'Taxinomie'
					),

					'file' => array
					(
						'title' => 'Fichiers associés'
					)
				)
			)
		);
	}

	protected function alter_children(array $children, array &$properties, array &$attributes)
	{
		$form_attributes = \ICanBoogie\Modules\Careers\Applications\ApplyForm::tags($properties);

		return array_merge
		(
			parent::alter_children($children, $properties, $attributes), $form_attributes[self::CHILDREN], array
			(
				'email-confirm' => null
			)
		);
	}
}