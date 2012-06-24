window.addEvent
(
	'domready', function()
	{
		var notifyToggler = $(document.body).getElement('[name="local[careers_applications.is_notify]"]')
		, notifyTarget = $(document.body).getElement('.control-group--local-careers-applications-notify')

		function checkNotifyToggler()
		{
			notifyTarget[notifyToggler.checked ? 'addClass' : 'removeClass']('enabled')
		}

		if (notifyToggler && notifyTarget)
		{
			notifyToggler.addEvent('change', checkNotifyToggler)

			checkNotifyToggler()
		}
	}
);