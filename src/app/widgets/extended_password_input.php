<?php
class ExtendedPasswordInput extends PasswordInput {

	protected $minimum_password_strength_required = null;
	protected $enable_password_reveal;
	protected $show_password_strength_progressbar;
	protected $current_score = 0;

	function __construct($options = []){
		$options += [
			"minimum_password_strength_required" => null,
			"enable_password_reveal" => true,
			"show_password_strength_progressbar" => true,
			"attrs" => [],
		];

		$options["attrs"] += [
			"class" => "form-control",
		];

		$this->minimum_password_strength_required = $options["minimum_password_strength_required"];
		$this->enable_password_reveal = $options["enable_password_reveal"];
		$this->show_password_strength_progressbar = $options["show_password_strength_progressbar"];

		$options["attrs"]["data-extended_password_field"] = "1";
		$options["attrs"]["data-minimum_password_strength_required"] = $this->minimum_password_strength_required ? $this->minimum_password_strength_required : "100";
		if($this->enable_password_reveal){
			$options["attrs"]["data-password_reveal"] = "1";
		}

		parent::__construct($options);
	}

	function render($name, $value, $options = []){
		$password = trim((string)$value);

		$analyzer = new Yarri\PasswordStrengthAnalyzer();
		$strength = $analyzer->analyze($password);
		$minimum_password_strength_required = $this->minimum_password_strength_required ? $this->minimum_password_strength_required : 100;

		$progressbar_class = "progress-bar-danger bg-danger";
		if($strength>=$minimum_password_strength_required * 0.75){
			$progressbar_class = "progress-bar-warning bg-warning";
		}
		if($strength>=$minimum_password_strength_required){
			$progressbar_class = "progress-bar-success bg-success";
		}

		if(defined("USING_IONICONS") && constant("USING_IONICONS")){
			$icon_eye_open = '<i class="ion ion-md-eye"></i>';
			$icon_eye_close = '<i class="ion ion-md-eye-off"></i>';
		}elseif(USING_BOOTSTRAP3 && !USING_FONTAWESOME){
			$icon_eye_open = '<span class="glyphicon glyphicon-eye-open"></span>';
			$icon_eye_close = '<span class="glyphicon glyphicon-eye-close"></span>';
		}else{
			$icon_eye_open = '<i class="fa-solid fa-eye"></i>';
			$icon_eye_close = '<i class="fa-solid fa-eye-slash"></i>';
		}

		if(USING_BOOTSTRAP4){
			$show_password_icon = $this->enable_password_reveal ? '
			<span class="password-reveal-button" title="'.h(_("Show password")).'">
				<span class="password-reveal-button__hidden">
					%icon_eye_open%
				</span>
				<span class="password-reveal-button__visible">
					%icon_eye_close%
				</span>
			</span>' : '';
		} else {
			$show_password_icon = $this->enable_password_reveal ? '
			<span class="password-reveal-button" title="'.h(_("Show password")).'">
				<span class="password-reveal-button__hidden">
					%icon_eye_open%
				</span>
				<span class="password-reveal-button__visible">
					%icon_eye_close%
				</span>
			</span>' : '';
		}

		$show_password_icon = strtr($show_password_icon,[
			"%icon_eye_open%" => $icon_eye_open,
			"%icon_eye_close%" => $icon_eye_close,
		]);

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
