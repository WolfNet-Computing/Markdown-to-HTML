<?php
	namespace WolfNet_Computing\MD_Reader;

	class Parser {
		public $OriginalFileContent;

		public function ParseHTML() {
			echo '<p>';
			var_dump($OriginalFileContent);
			echo '</p>';
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