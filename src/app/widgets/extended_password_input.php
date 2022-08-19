<?php
class ExtendedPasswordInput extends PasswordInput {

	protected $minimum_score_required = null;
	protected $enable_password_reveal;
	protected $show_password_strength_progressbar;
	protected $current_score = 0;

	function __construct($options = []){
		$options += [
			"minimum_score_required" => null,
			"enable_password_reveal" => true,
			"show_password_strength_progressbar" => true,
			"attrs" => [],
		];

		$options["attrs"] += [
			"class" => "form-control",
		];

		$this->minimum_score_required = $options["minimum_score_required"];
		$this->enable_password_reveal = $options["enable_password_reveal"];
		$this->show_password_strength_progressbar = $options["show_password_strength_progressbar"];

		$options["attrs"]["data-extended_password_field"] = "1";
		$options["attrs"]["data-minimum_score_required"] = $this->minimum_score_required;
		if($this->enable_password_reveal){
			$options["attrs"]["data-password_reveal"] = "1";
		}

		parent::__construct($options);
	}

	function render($name, $value, $options = []){
		$password = trim((string)$value);
		$analyzer = new Yarri\PasswordStrengthAnalyzer();
		$strength = $analyzer->analyze($password);
		$progressbar_class = $strength < $this->minimum_score_required ? "progress-bar-danger" : "progress-bar-success";

		if(USING_BOOTSTRAP4){
			$show_password_icon = $this->enable_password_reveal ? '
			<span class="password-reveal-button" title="'.h(_("Show password")).'">
				<span class="password-reveal-button__hidden">
					<i class="fa-solid fa-eye"></i>
				</span>
				<span class="password-reveal-button__visible">
					<i class="fa-solid fa-eye-slash"></i>
				</span>
			</span>' : '';
		} else {
			$show_password_icon = $this->enable_password_reveal ? '
			<span class="password-reveal-button" title="'.h(_("Show password")).'">
				<span class="password-reveal-button__hidden">
					<span class="glyphicon glyphicon-eye-open"></span>
				</span>
				<span class="password-reveal-button__visible">
					<span class="glyphicon glyphicon-eye-close"></span>
				</span>
			</span>' : '';
		}

		

		$progressbar = $this->show_password_strength_progressbar ? '
				<div class="form-group form-group--password_strength">
					<label class="control-label">'.h(_("Password strength")).'</label>
					<div class="progress password_strength__progressbar">
						<div class="progress-bar '.$progressbar_class.'" role="progressbar" aria-valuenow="%current_score%" aria-valuemin="0" aria-valuemax="100" style="width: %current_score%%;">
							%current_score%%
						</div>
					</div>
				</div>
		' : '';

		$template = '
			%password_input%
			'.$show_password_icon.'
			'.$progressbar.'
		';

		$password_input = parent::render($name, $value, $options);
		$src = strtr($template,[
			"%current_score%" => $this->current_score,
			"%password_input%" => $password_input,
		]);
		return $src;
	}

	function set_current_score($score){
		$this->current_score = $score;
	}
}
