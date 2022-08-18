<?php
class PasswordStrengthAnalyzerController extends ApiController {

	/**
	 * ### Password strength rating from 0 to 100%
	 */
	function analyze(){
		if($this->request->post() && ($d = $this->form->validate($this->params))){
			$analyzer = new Yarri\PasswordStrengthAnalyzer();
			$score = $analyzer->analyze($d["password"]);

			$this->api_data = [
				"score" => $score
			];
		}
	}
}
