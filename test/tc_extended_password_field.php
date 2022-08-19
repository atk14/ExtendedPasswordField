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
}
