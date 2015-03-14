<?php

$form_id = $app->site->metas['careers_applications.form_id'];

if (!$form_id)
{
	throw new \ICanBoogie\Exception\Config($app->modules['careers.applications']);
}

try
{
	/* @var $form \Icybee\Modules\Forms\Form */

	$app->request->context->node = $form = $app->models['forms'][$form_id];

	echo $form;
}
catch (\Exception $e)
{
	throw $e;
}
