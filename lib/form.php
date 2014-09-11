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

use ICanBoogie\Mailer;
use ICanBoogie\Operation;

use Brickrouge\Button;
use Brickrouge\Element;
use Brickrouge\File;
use Brickrouge\Form;
use Brickrouge\Text;

class ApplyForm extends Form
{
	public function __construct(array $attributes=array())
	{
		parent::__construct
		(
			\ICanBoogie\array_merge_recursive
			(
				$attributes, static::tags(), array
				(
					Form::RENDERER => 'Simple',
					Form::HIDDENS => array
					(
						Operation::DESTINATION => 'careers.applications',
						Operation::NAME => 'save'
					),

					'name' => 'careers.applications/apply'
				)
			)
		);
	}

	public static function tags(array $properties=array())
	{
		global $core;

		$offer_id = $core->request['nid'];

		return array
		(
			Form::HIDDENS => array
			(
				'offer_id' => $offer_id
			),

			Element::CHILDREN => array
			(
				'firstname' => new Text
				(
					array
					(
						Form::LABEL => 'firstname',
						Element::REQUIRED => true
					)
				),

				'lastname' => new Text
				(
					array
					(
						Form::LABEL => 'lastname',
						Element::REQUIRED => true
					)
				),

				'email' => new Text
				(
					array
					(
						Form::LABEL => 'email',
						Element::REQUIRED => true,
						Element::VALIDATOR => array('Brickrouge\Form::validate_email')
					)
				),

				'email-confirm' => new Text
				(
					array
					(
						Form::LABEL => 'email_confirm',
						Element::REQUIRED => true
					)
				),

				'cover_letter' => new Element
				(
					'textarea', array
					(
						Form::LABEL => 'cover_letter',
						Element::REQUIRED => true
					)
				),

				'cv' => new Element
				(
					'input', array
					(
						Form::LABEL => 'cv',
// 						Element::FILE_WITH_LIMIT => 4096,
// 						Element::FILE_WITH_REMINDER => true,
						Element::REQUIRED => $core->site->metas['careers_applications.is_cv_required'],

						'type' => 'file'
					)
				)
			)
		);
	}

	public static function get_defaults()
	{
		global $core;

		$email = $core->user->email;

		return array
		(
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
		);
	}

	public function validate($values, \ICanBoogie\Errors $errors)
	{
		if (!empty($_FILES['cv']['size']))
		{
			$values['cv'] = true;
		}

		$rc = parent::validate($values, $errors);

		if (isset($values['email']) && isset($values['email-confirm']) && $values['email'] != $values['email-confirm'])
		{
			$erros['email-confirm'] = "The <q>E-mail</q> and <q>E-mail confirm</q> fields don't match";
		}

		return count($errors) == 0;
	}

	public function alter_notify($properties)
	{
		global $core;

		$application = $core->models['careers.applications'][$properties->rc['key']];

		$properties->bind = $application;

		if ($application->offer)
		{
			$recipient = $application->offer->metas['recipient'];

			if ($recipient)
			{
				$properties->mail_tags[Mailer::T_DESTINATION] = $recipient;
			}
		}
	}
}