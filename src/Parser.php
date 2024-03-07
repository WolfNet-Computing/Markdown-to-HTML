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

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array.
		function ParseHTML() {
			$temp = explode("  ", $this->OriginalFileContent);
			echo gettype($temp) . '<br>';
			var_dump($temp);
		}
	}
?>