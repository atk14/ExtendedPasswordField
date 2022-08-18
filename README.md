ExtendedPasswordField
=====================

ExtendedPasswordField is a password field with a password reveal function and a password strength progress bar.

The ExtendedPasswordField is intended to be used in ATK14 applications.

Installation
------------

Just use the Composer:

    cd path/to/your/atk14/project/
    composer require atk14/extended-password-field

    ln -s ../../vendor/atk14/extended-password-field/src/app/fields/extended_password_field.php app/fields/extended_password_field.php
    ln -s ../../vendor/atk14/extended-password-field/src/app/widgets/extended_password_widget.php app/widgets/extended_password_widget.php

Usage in an ATK14 application
-----------------------------

TODO

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

License
-------

ExtendedPasswordField is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )
