<?php
	namespace WolfNet_Computing\MD_Reader;

	class Parser {
		public $OriginalFileContent;

		function __construct($file, $OutputType) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile,filesize($file));
			fclose($mdfile);
			if ($OutputType == 'HTML') {
				$this->ParseHTML($this->OriginalFileContent);
			} else {
				die('Unrecognised output format!');
			}
		}

		function ParseHTML() {
			echo '<p>';
			var_dump($this->OriginalFileContent);
			echo '</p>';
		}
	}
?>