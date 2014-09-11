# Careers Applications

The module allows visitors to send unsolicited job applications. The module can also be used by
the [Careers Offers][] module to apply to job offers.





### Demonstration

A demonstration of the module is available on the Icybee demo website:

http://demo.icybee.org/careers/offers/





## Provided features

### Dashboard: `careers-applications-summary`

The `careers-applications-summary` dashboard panel displays the latests submitted applications.





### Form model: `careers.applications`

The module defines the `careers.applications` form model which can be used to created
application forms.





### View: `apply`

The `apply` view displays the application form selected in the module configuration.





----------





## Requirement

The package requires PHP 5.4 or later.





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/).
Create a `composer.json` file and run `php composer.phar install` command to install it:

```json
{
	"minimum-stability": "dev",
	"require":
	{
		"icybee/module-careers-applications": "*"
	}
}
```





### Cloning the repository

The package is [available on GitHub](https://github.com/Icybee/module-careers-applications), its repository can be
cloned with the following command line:

	$ git clone https://github.com/Icybee/module-careers-applications.git careers.applications





## Documentation

The documentation for the package and its dependencies can be generated with the `make doc`
command. The documentation is generated in the `docs` directory using [ApiGen](http://apigen.org/).
The package directory can later by cleaned with the `make clean` command.





## License

The module is licensed under the New BSD License - See the [LICENSE](LICENSE) file for details.





[Careers Offers]: https://github.com/Icybee/module-careers-offers