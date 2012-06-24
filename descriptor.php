<?php

namespace ICanBoogie;

use ICanBoogie\ActiveRecord\Model;

return array
(
	Module::T_TITLE => 'Job applications',
	Module::T_CATEGORY => 'feedback',
	Module::T_MODELS => array
	(
		'primary' => array
		(
			Model::T_SCHEMA => array
			(
				'fields' => array
				(
					'application_id' => 'serial',
					'offer_id' => 'foreign',
					'siteid' => 'foreign',
					'firstname' => array('varchar', 80),
					'lastname' => array('varchar', 80),
					'email' => array('varchar', 80),
					'study_level' => array('integer', 'tiny'),
					'experience' => array('integer', 'tiny'),
					'cover_letter' => 'text',
					'cv' => 'varchar',
					'created' => array('timestamp', 'default' => 'current_timestamp()')
				)
			)
		)
	),

	Module::T_REQUIRES => array
	(
		'forms' => '1.0'
	)
);