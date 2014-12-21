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

use ICanBoogie\Operation;

use Brickrouge\Element;
use Brickrouge\Form;
use Brickrouge\Text;

class ApplyForm extends Form
{
	public function __construct(array $attributes=[])
	{
		parent::__construct(\ICanBoogie\array_merge_recursive($attributes, static::tags(), [

			Form::RENDERER => 'Simple',
			Form::HIDDENS => [

				Operation::DESTINATION => 'careers.applications',
				Operation::NAME => 'save'

			],

			'name' => 'careers.applications/apply'

		]));
	}

	public static function tags(array $properties=[])
	{
		$app = \ICanBoogie\app();
		$offer_id = $app->request['nid'];

		return [

			Form::HIDDENS => [

				'offer_id' => $offer_id

			],

			Element::CHILDREN => [

				'firstname' => new Text([

					Form::LABEL => 'firstname',
					Element::REQUIRED => true

				]),

				'lastname' => new Text([

					Form::LABEL => 'lastname',
					Element::REQUIRED => true

				]),

				'email' => new Text([

					Form::LABEL => 'email',
					Element::REQUIRED => true,
					Element::VALIDATOR => [ 'Brickrouge\Form::validate_email' ]

				]),

				'email-confirm' => new Text([

					Form::LABEL => 'email_confirm',
					Element::REQUIRED => true

				]),

				'cover_letter' => new Element('textarea', [

					Form::LABEL => 'cover_letter',
					Element::REQUIRED => true

				]),

				'cv' => new Element('input', [

					Form::LABEL => 'cv',
					Element::REQUIRED => $app->site->metas['careers_applications.is_cv_required'],

					'type' => 'file'

				])
			]
		];
	}

	public static function get_defaults()
	{
		$email = \ICanBoogie\app()->user->email;

		return [

			'notify_bcc' => $email,
			'notify_from' => 'Candidature <no-reply@example.com>',
			'notify_subject' => 'Dépôt de candidature',
			'notify_template' => <<<EOT
Un message a été posté depuis le formulaire de dépôt de candidature :

Nom : #{@firstname} #{@lastname}, #{@email}

<wdp:if test="@offer">
Candidature pour l'offre : #{@offer.title} (n°#{@offer_id})
</wdp:if>

#{@cover_letter}

C.V. : #{@absolute_cv_url}
EOT
		];
	}

	public function validate($values, \ICanBoogie\Errors $errors)
	{
		if (!empty($_FILES['cv']['size']))
		{
			$values['cv'] = true;
		}

		parent::validate($values, $errors);

		if (isset($values['email']) && isset($values['email-confirm']) && $values['email'] != $values['email-confirm'])
		{
			$erros['email-confirm'] = "The <q>E-mail</q> and <q>E-mail confirm</q> fields don't match";
		}

		return count($errors) == 0;
	}

	public function alter_notify($properties)
	{
		$application = \ICanBoogie\app()->models['careers.applications'][$properties->rc['key']];

		$properties->bind = $application;

		if ($application->offer)
		{
			$recipient = $application->offer->metas['recipient'];

			if ($recipient)
			{
				$properties->mail_tags['to'] = $recipient;
			}
		}
	}
}
