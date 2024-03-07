<?php
	namespace WolfNet_Computing\MD_Reader;

	class Parser {
		public $OriginalFileContent;

		function __construct($file, $OutputType) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile,filesize($file));
			fclose($mdfile);
			if ($OutputType == 'HTML') {
				return $this->ParseHTML($this->OriginalFileContent);
			} else {
				die('Unrecognised output format!');
			}
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array.
		function ParseHTML() {
			# First to split the string by the markdown double space newline and append the HTML newline to the end of each of the strings in the resulting array.
			$array = explode('  ', $this->OriginalFileContent);
			for ($i = 0; $i < count($array); ++$i) {
				$array[$i] = $array[$i] . '<br>';
			}
		}
	}
?>