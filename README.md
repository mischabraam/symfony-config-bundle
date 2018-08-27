# We Provide Config Bundle

The We Provide Config Bundle for Symfony adds an interface into the Sonata/AdminBundle in which you can configure application parameters.


## Dependencies

This bundle is developed with a Symfony Standard Edition on PHP 7.1 using We Provide's version of valet+. Obviously it has dependencies to other projects and/or bundles. Below a list of dependencies, please use the installation guides of these bundles first.
 * [Symfony Standard Edition 3.3](https://symfony.com/doc/current/setup.html#creating-symfony-applications-with-composer)
 * [Sonata Admin Bundle 3.23](https://sonata-project.org/bundles/admin/3-x/doc/getting_started/installation.html) (with [SonataDoctrineORMAdminBundle](https://sonata-project.org/bundles/doctrine-orm-admin/master/doc/reference/installation.html))
 

## Installation

Install this bundle into your project using Composer.

```
composer require weprovide/symfony-config-bundle
```

Enable the bundle by inserting it in your Symfony's `AppKernel.php`.
```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...

            new WeProvide\ConfigBundle\WeProvideConfigBundle(),
        );

        // ...
    }

    // ...
}
```


## License

This bundle has been released under the MIT license and open for improvements, please share your thoughts which will be much appreciated.



## Authors

- Mischa Braam ([@mischabraam](https://github.com/mischabraam))

## TODO

- Add event listeners where the client application can hook onto.