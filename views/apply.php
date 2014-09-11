<?php

/*
if (!$core->user->has_permission(ICanBoogie\Module::PERMISSION_CREATE, $module))
{
	echo new \Brickrouge\AlertMessage
	(
		<<<EOT
<p>You don't have permission the create applications,
<a href="{$core->site->path}/admin/users.roles">the <q>{$core->user->role->name}</q> role should be modified</a>.</p>
EOT
, array(), 'error'
	);

	return;
}
*/

$formid = $core->site->metas['careers_applications.form_id'];

if (!$formid)
{
	throw new \ICanBoogie\Exception\Config($core->modules['careers.applications']);
}

try
{
	$form = $core->models['forms'][$formid];

	$page->node = $form;

	echo $form;
}
catch (\Exception $e)
{
	throw $e;
}