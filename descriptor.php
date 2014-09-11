<?php

namespace Icybee\Modules\Careers\Applications;

use ICanBoogie\Module;
use ICanBoogie\ActiveRecord\Model;

return array
(
	Module::T_TITLE => 'Job applications',
	Module::T_CATEGORY => 'feedback',
	Module::T_MODELS => array
	(
		'primary' => array
		(
			Model::SCHEMA => array
			(
				'fields' => array
				(
					'application_id' => 'serial',
					'offer_id' => 'foreign',
					'site_id' => 'foreign',
					'firstname' => array('varchar', 80),
					'lastname' => array('varchar', 80),
					'email' => array('varchar', 80),
					'study_level' => array('integer', 'tiny'),
					'experience' => array('integer', 'tiny'),
					'cover_letter' => 'text',
					'cv_hash' => array('char', 40),
					'created_at' => 'datetime'
				)
			)
		)
	),

	Module::T_NAMESPACE => __NAMESPACE__,
	Module::T_REQUIRES => array
	(
		'forms' => '2.*'
	)
);