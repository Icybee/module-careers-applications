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

use ICanBoogie\Uploaded;
use ICanBoogie\Mailer;
use ICanBoogie\HTTP\Request;
use ICanBoogie\ActiveRecord\File;

class SaveOperation extends \ICanBoogie\SaveOperation
{
	protected $accept = array
	(
		'.pdf' => 'application/pdf',
		'.odt' => 'application/vnd.oasis.opendocument.text',
		'.doc' => 'application/msword',
		'.docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
	);

	protected function lazy_get_properties()
	{
		global $core;

		$properties = parent::lazy_get_properties();

		if (empty($properties['cv']))
		{
			unset($properties['cv']);
		}

		$properties['site_id'] = $core->site_id;

		return $properties;
	}

	public function __invoke(Request $request)
	{
		global $core;

		$response = parent::__invoke($request);

		if ($response->rc)
		{
			$record = $this->module->model[$response->rc['key']];
// 			$this->super->notify_bind = $record;
			$metas = $core->site->metas;

			if ($metas['careers_applications.is_notify'])
			{
				$message = Patron($metas['jobs_applications.notify.template'], $record);

				$mailer = new Mailer
				(
					array
					(
						Mailer::T_BCC => $metas['jobs_applications.notify.bcc'],
						Mailer::T_DESTINATION => $record->email,
						Mailer::T_FROM => $metas['jobs_applications.notify.from'],
						Mailer::T_MESSAGE => $message,
						Mailer::T_SUBJECT => $metas['jobs_applications.notify.subject'],
						Mailer::T_TYPE => 'plain'
					)
				);

				$mailer();
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
	}

	/**
	 * Extends the method to handle missing files.
	 *
	 * The only purpose of this method is to handle missing files, further errors - such as type
	 * errors - should be handled during the _validate_ method.
	 *
	 * @param array $controls
	 *
	 * @return boolean Control success.
	 */
	protected function control(array $controls)
	{
		global $core;

		$request = $this->request;

		if (!$request['cv'])
		{
			$request['cv'] = $file = new Uploaded('cv', $this->accept);

			if (!$file->er)
			{
				$path = $core->config['repository.temp'] . '/' . basename($file->location) . $file->extension;
				$file->move(\ICanBoogie\DOCUMENT_ROOT . $path, true);
			}
		}

		return parent::control($controls);
	}

	/**
	 * Checks if the files handled during the control_operation_save() method have errors.
	 */
	protected function validate(\ICanBoogie\Errors $errors)
	{
		$properties = $this->properties;

		if (isset($properties['cv']) && $properties['cv'] instanceof Uploaded)
		{
			$file = $properties['cv'];

			if ($file->er)
			{
				$errors[File::PATH] = t('Unable to upload file %file. :message', array('%file' => $file->name, ':message' => $file->er_message));

				return false;
			}
			else if (!$file->location)
			{
				unset($this->properties['cv']);
			}
		}

		return parent::validate($errors);
	}
}