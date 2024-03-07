<?php
	namespace WolfNet_Computing\MD_Reader;

	class Parser {
		private $FindMDNewline = '/ {2}/';
		private $FindMDHeader1 = '/# /';
		private $OriginalFileContent;

		function __construct($file) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile, filesize($file));
			fclose($mdfile);
			clearstatcache();
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array...
		function Parse() {
			$array = explode("\n", $this->OriginalFileContent);
			foreach ($array as $i => $line) {
				echo 'gettype($array[$i]) returns: ' . gettype($array[$i]) . '<br>';
				echo $array[$i] . '<br>';
				# First to split the string by the markdown double space newline and append the HTML newline to the end of each of the strings in the resulting array...
				$array[$i] = preg_replace($this->FindMDNewline, '<br>', $array[$i]);
				# Check for the Markdown Header level 1, remove it and add the h1 opening and closing tags for HTML...
				echo preg_match($this->FindMDHeader1, $array[$i]);
				if (preg_match($this->FindMDHeader1, $array[$i]) == 1) {
					preg_replace($this->FindMDHeader1, '<h1>', $array[$i]);
					$array[$i] .= '</h1>';
				}
			}
			return $array;
		}
	}
?>