<?php
class ExtendedPasswordField extends PasswordField {

	var $minimum_password_strength_required;

	function __construct($options = []){
		$options += [
			"minimum_password_strength_required" => null,
			"enable_password_reveal" => true,
			"show_password_strength_progressbar" => true,
		];

		$options += [
			"widget" => new ExtendedPasswordInput([
				"minimum_password_strength_required" => $options["minimum_password_strength_required"],
				"enable_password_reveal" => $options["enable_password_reveal"],
				"show_password_strength_progressbar" => $options["show_password_strength_progressbar"],
			]),
		];

		$this->minimum_password_strength_required = $options["minimum_password_strength_required"];

		parent::__construct($options);

		$this->update_messages(array(
			"weak" => _("Síla tohoho hesla je %score%%, vyžadováno je však minimálně %minimum_password_strength_required%%. Zvyšte složitost hesla."),
		));
	}

	function clean($value){
		list($err,$value) = parent::clean($value);

		if(!is_null($err) || !strlen($value)){ return [$err,$value]; }

		$analyzer = new Yarri\PasswordStrengthAnalyzer();
		$score = $analyzer->analyze($value);
		$this->widget->set_current_score($score);
		if($this->minimum_password_strength_required && $score<=$this->minimum_password_strength_required){
			$err = strtr($this->messages["weak"],[
				"%score%" => $score,
				"%minimum_password_strength_required%" => $this->minimum_password_strength_required,
			]);
		}

		return [$err,$value];
	}
}
