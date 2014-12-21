<?php

$formid = $app->site->metas['careers_applications.form_id'];

if (!$formid)
{
	throw new \ICanBoogie\Exception\Config($app->modules['careers.applications']);
}

try
{
	$form = $app->models['forms'][$formid];
	$page->node = $form;

	echo $form;
}
catch (\Exception $e)
{
	throw $e;
}
