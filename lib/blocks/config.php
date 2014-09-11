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
 * A block to configure careers applications.
 */
class ConfigBlock extends \Icybee\ConfigBlock
{
	protected static function add_assets(\Brickrouge\Document $document)
	{
		parent::add_assets($document);

		$document->css->add(DIR . 'public/admin.css');
		$document->js->add(DIR . 'public/admin.js');
	}

	protected function alter_children(array $children, array &$properties, array &$attributes)
	{
		$ns = $this->module->flat_id;

		return array_merge
		(
			parent::alter_children($children, $properties, $attributes), array
			(
				"local[$ns.form_id]" => new \WdFormSelectorElement
				(
					'select', array
					(
						Form::LABEL => 'form_id',
						Element::REQUIRED => true,
						Element::DESCRIPTION => 'form_id'
					)
				),

				"local[$ns.is_notify]" => new Element
				(
					Element::TYPE_CHECKBOX, array
					(
						Element::LABEL => 'is_notify',
						Element::DESCRIPTION => 'is_notify'
					)
				),

				"local[$ns.notify]" => new \WdEMailNotifyElement
				(
					array
					(
						Form::LABEL => 'notify',
						Element::DEFAULT_VALUE => array
						(
							'subject' => 'Exemple : Accusé de réception de votre candidature',
							'template' => <<<EOT
Objet : accusé de réception de candidature.

Bonjour,

Nous avons bien reçu votre candidature et vous remercions de l'intérêt que vous portez à
notre société. Nous allons procéder très rapidement à l'examen de votre dossier. Aussi, sans
réponse de notre part, nous vous demandons de bien considérer qu'il n'a pas été retenu.

Toutefois, sans avis contraire de votre part, nous nous permettons de conserver votre profil dans
nos fichiers de façon à éventuellement revenir vers vous dans le cadre de nouvelles opportunités
correspondant à vos attentes.

Cordialement,

L'équipe Recrutement.



Conformément à l'article 34 de la loi "Informatique et libertés", vous disposez d'un droit d'accès,
de modification, de rectification et de suppression des données qui vous concernent. Pour
l'exercer, vous pouvez écrire à :

Direction des ressources Humaines
EOT
						)
					)
				),

				"local[$ns.is_cv_required]" => new Element
				(
					Element::TYPE_CHECKBOX, array
					(
						Element::LABEL => 'Le C.V. est obligatoire'
					)
				)
			)
		);
	}
}