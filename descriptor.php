<?php

namespace Icybee\Modules\Careers\Applications;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Module\Descriptor;

return array
(
	Descriptor::TITLE => 'Job applications',
	Descriptor::CATEGORY => 'feedback',
	Descriptor::MODELS => array
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

	Descriptor::NS => __NAMESPACE__,
	Descriptor::REQUIRES => array
	(
		'forms' => '2.*'
	)
);