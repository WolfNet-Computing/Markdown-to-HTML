<?php
	namespace WolfNet-Computing\MD-Reader;

	class Parser {
		public $OriginalFileContent;

		private function ParseHTML() {
			var_dump($OriginalFileContent);
		}

		public function __construct($file, $OutputType) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$OriginalFileContent = fread($mdfile,filesize($file));
			fclose($mdfile);
			if ($OutputType == 'HTML') {
				ParseHTML($OriginalFileContent);
			} else {
				die('Unrecognised output format!');
			}
		}
	}
?>