<?php

/*
 * This file is part of the Icybee package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Icybee\Modules\Careers\Applications\Operation;

use ICanBoogie\DateTime;
use ICanBoogie\Errors;
use ICanBoogie\HTTP\Request;

use Icybee\Modules\Careers\Applications\Module;

class SaveOperation extends \ICanBoogie\Module\Operation\SaveOperation
{
	protected $accept = [

		'.pdf' => 'application/pdf',
		'.odt' => 'application/vnd.oasis.opendocument.text',
		'.doc' => 'application/msword',
		'.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'

	];

	protected function lazy_get_properties()
	{
		$properties = array_merge(parent::lazy_get_properties(), [

			'site_id' => $this->app->site_id,
			'created_at' => DateTime::now()

		]);

		return $properties;
	}

	public function __invoke(Request $request)
	{
		/* @var $cv \ICanBoogie\HTTP\File */

		$cv = $request->files['cv'];

		if ($cv->is_valid)
		{
			$request['cv'] = $cv;
		}

		return parent::__invoke($request);

		/*
		$response = parent::__invoke($request);

		if ($response->rc)
		{
			$app = $this->app;
			$record = $this->module->model[$response->rc['key']];
			$metas = $app->site->metas;

			if ($metas['careers_applications.is_notify'])
			{
				$message = Patron($metas['jobs_applications.notify.template'], $record);

				$app->mail([

					'bcc' => $metas['jobs_applications.notify.bcc'],
					'to' => $record->email,
					'from' => $metas['jobs_applications.notify.from'],
					'body' => $message,
					'subject' => $metas['jobs_applications.notify.subject'],
					'type' => 'plain'

				]);
			}

			#
			# si une offre est associée, et que l'adresse de retour est définie, on modifie
			# l'adresse de notification du formulaire.
			#

			if ($record->offer)
			{
				$reply = $record->offer->metas['reply'];

				if ($reply)
				{
// 					$this->super->record->notify_destination = $reply;
				}
			}
		}

		return $response;
		*/
	}

	/**
	 * Checks if the files handled during the control_operation_save() method have errors.
	 */
	protected function validate(Errors $errors)
	{
		$request = $this->request;

		/* @var $cv \ICanBoogie\HTTP\File */

		$cv = $request['cv'];

		if ($cv)
		{
			$accept = $this->accept;

			if ($cv->match($accept))
			{
				$hash = sha1_file($cv->pathname);
				$extension = $cv->extension;
				$destination = \ICanBoogie\REPOSITORY . Module::DIRECTORY_NAME . DIRECTORY_SEPARATOR . $hash . $extension;

				if (!file_exists($destination))
				{
					copy($cv->pathname, $destination);
				}

				$request['cv_hash'] = $hash;
			}
			else
			{
				$errors['cv']->add("Wrong file format, must be one of the following: !formats", [

					'formats' => implode(' ', array_keys($accept))

				]);
			}
		}

		return parent::validate($errors);
	}
}
