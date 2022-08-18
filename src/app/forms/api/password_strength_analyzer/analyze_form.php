<?php
class AnalyzeForm extends ApiForm {

	function set_up(){
		$this->set_method("post");

		$this->add_field("password", new CharField([
			"max_length" => 200,
			"required" => false,
		]));
	}
}
