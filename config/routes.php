<?php

namespace Icybee\Modules\Careers\Applications;

use Icybee\Routing\RouteMaker as Make;

return Make::admin('careers.applications', Routing\ApplicationAdminController::class);
