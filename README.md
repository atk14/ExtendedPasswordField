ExtendedPasswordField
=====================

ExtendedPasswordField is a password field with a password reveal function and a password strength progress bar.

The ExtendedPasswordField is intended to be used in ATK14 applications.

Installation
------------

Just use the Composer:

    cd path/to/your/atk14/project/
    composer require atk14/extended-password-field

    ln -s ../../vendor/atk14/extended-password-field/src/app/fields/extended_password_field.php app/fields/
    ln -s ../../vendor/atk14/extended-password-field/src/app/widgets/extended_password_widget.php app/widgets/
    ln -s ../../../vendor/atk14/extended-password-field/src/public/scripts/utils/extended_password_field.js public/scripts/utils/

    ln -s ../../../vendor/atk14/extended-password-field/src/app/controllers/api/password_strength_analyzer_controller.php app/controllers/api/
    ln -s ../../../vendor/atk14/extended-password-field/src/app/forms/api/password_strength_analyzer app/forms/api/


Link a proper style form either for  or Bootstrap 4 (scss) or Bootstrap 3 (less).

    # Bootstrap 4
    ln -s ../../../vendor/atk14/extended-password-field/src/public/styles/shared/_extended_password_field.scss public/styles/shared/

    # or Bootstrap 3
    ln -s ../../../vendor/atk14/extended-password-field/src/public/styles/shared/extended_password_field.less public/styles/shared/

Include public/scripts/utils/extended_password_field.js in gulpfile.js into applicationScripts.

    var applicationScripts = [
      // ...
      "public/scripts/utils/extended_password_field.js",
      "public/scripts/application.js"
    ];

Initialize the ExtendedPasswordField in the desired place in the public/scripts/application.js file.

    // file: public/scripts/application.js

    // ...

    var
	  UTILS = window.UTILS,

	  APPLICATION = {
      // ...
      users {
        create_new: function() {
          UTILS.extended_password_field.init();
        }
      },
      // ...  
    };

    // ...

Usage in an ATK14 application
-----------------------------

In a form:

  
    <?php
    // file: app/forms/users/create_new_form.php
    class CreateNewForm extends ApplicationForm {

      function set_up(){
        // ...
        $this->add_field("password", new ExtendedPasswordField([
          "minimum_password_strength_required" => 80, // %
          "enable_password_reveal" => true, // default true
          "show_password_strength_progressbar" => true, // default true
        ]));
      }
    }

Testing
-------

    composer update --dev
    cd test
    ../vendor/bin/run_unit_tests

License
-------

ExtendedPasswordField is free software distributed [under the terms of the MIT license](http://www.opensource.org/licenses/mit-license)

[//]: # ( vim: set ts=2 et: )
