<?php
	namespace WolfNet_Computing\MD_Reader;

	class Parser {
		private $OriginalFileContent = '';
		private $OutputType = '';

		function __construct($OutputType) {
			if ($OutputType == 'HTML') {
				return $this->OutputType = 'HTML';
			} else {
				die('Unrecognised output format!');
			}
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array.
		function ParseHTML($file, $HtmlString) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile,filesize($file));
			fclose($mdfile);
			# First to split the string by the markdown double space newline and append the HTML newline to the end of each of the strings in the resulting array.
			$array = explode('  ', $HtmlString);
			for ($i = 0; $i < count($array); ++$i) {
				$array[$i] = $array[$i] . '<br>';
			}
			return $array;
		}
	}
?>