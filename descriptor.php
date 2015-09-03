<?php

namespace Icybee\Modules\Careers\Applications;

use ICanBoogie\ActiveRecord\Model;
use ICanBoogie\Module\Descriptor;

return [

	Descriptor::TITLE => 'Job applications',
	Descriptor::CATEGORY => 'feedback',
	Descriptor::MODELS => [

		'primary' => [

			Model::SCHEMA => [

				'application_id' => 'serial',
				'offer_id' => 'foreign',
				'site_id' => 'foreign',
				'firstname' => [ 'varchar', 80 ],
				'lastname' => [ 'varchar', 80 ],
				'email' => [ 'varchar', 80 ],
				'study_level' => [ 'integer', 'tiny' ],
				'experience' => [ 'integer', 'tiny' ],
				'cover_letter' => 'text',
				'cv_hash' => [ 'char', 40 ],
				'created_at' => 'datetime'

			]
		]
	],

	Descriptor::NS => __NAMESPACE__,
	Descriptor::REQUIRES => [ 'forms' ]
];
