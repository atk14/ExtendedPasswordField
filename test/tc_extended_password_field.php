<?php
class TcExtendedPasswordField extends TcBase {

	function test(){
		$this->field = new ExtendedPasswordField([
			"minimum_password_strength_required" => 50,
		]);

		$value = $this->assertValid(' YouAreTheB00s ');
		$this->assertEquals("YouAreTheB00s",$value);

		$err = $this->assertInvalid("weak");
		$this->assertEquals("The strength of this password is 1%, but the minimum required value is 50%. Increase the password complexity.",$err);

		$err = $this->assertInvalid("  ");
		$this->assertEquals("This field is required.",$err);
	}

	function test_widget(){
		$form = new Atk14Form();

		$form->add_field("password1", new ExtendedPasswordField([
			"show_password_strength_progressbar" => true,
		]));

		$form->add_field("password2", new ExtendedPasswordField([
			"show_password_strength_progressbar" => false,
		]));

		$f1 = $form->get_field("password1");
		$f2 = $form->get_field("password2");

		$this->assertContains('div class="progress-bar',$f1->as_widget());
		$this->assertNotContains('div class="progress-bar',$f2->as_widget());

		// --

		$form->add_field("password3", new ExtendedPasswordField([
			"minimum_password_strength_required" => null,
		]));

		$form->add_field("password4", new ExtendedPasswordField([
			"minimum_password_strength_required" => 90,
		]));

		$f3 = $form->get_field("password3");
		$f4 = $form->get_field("password4");

		$this->assertContains('data-minimum_password_strength_required="100"',$f3->as_widget());
		$this->assertContains('data-minimum_password_strength_required="90"',$f4->as_widget());
	}

	function test_progressbar_colors(){
		$form = new Atk14Form();

		$form->add_field("password", new ExtendedPasswordField([
			"show_password_strength_progressbar" => true,
			"minimum_password_strength_required" => 80,
		]));

		$form->validate(["password" => "x"]); // 1%
		$f = $form->get_field("password");
		$this->assertContains('class="progress-bar progress-bar-danger"',$f->as_widget());

		$form->validate(["password" => "xXske#@3459a!#D,c:!:dS~"]); // 100%
		$f = $form->get_field("password");
		$this->assertContains('class="progress-bar progress-bar-success"',$f->as_widget());

		$form->validate(["password" => "jdjjSDdfje#,dsA"]); // 61%
		$f = $form->get_field("password");
		$this->assertContains('class="progress-bar progress-bar-warning"',$f->as_widget());

	}
}
