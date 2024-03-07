<?php
	namespace WolfNet_Computing\MD_Reader;

	class HtmlParser {
		private $OriginalFileContent;
		private $FindMDNewline = "/( ){2}/";
		private $FindMDHeader1 = '/^(# )/';
		private $FindMDHeader2 = '/^(## )/';
		private $FindMDHeader3 = '/^(### )/';
		private $FindMDHeader4 = '/^(#### )/';
		private $FindMDHeader5 = '/^(##### )/';
		private $FindMDHeader6 = '/^(###### )/';
		private $FindMDLink = '/\[.+\]\(((http|https):\/\/(.+))\)/';

		function __construct($file) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile, filesize($file));
			fclose($mdfile);
			clearstatcache();
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array...
		function DisplayFormatted() {
			$array = explode("\n", $this->OriginalFileContent);
			for ($i = 0; $i < count($array); $i++) {
				$array[$i] = str_replace(array("\r\n", "\n", "\r"), "", $array[$i]);
				# Check for the Markdown Header level 1, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader1, $array[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$array[$i] = preg_replace($this->FindMDHeader1, '<h1>', $array[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$array[$i] = preg_replace($this->FindMDNewline, "", $array[$i]);
					$array[$i] = $array[$i] . '</h1><br>';
				}
				# Check for the Markdown Header level 2, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader2, $array[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$array[$i] = preg_replace($this->FindMDHeader2, '<h2>', $array[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$array[$i] = preg_replace($this->FindMDNewline, "", $array[$i]);
					$array[$i] = $array[$i] . '</h2><br>';
				}
				# Check for the Markdown Header level 3, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader3, $array[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$array[$i] = preg_replace($this->FindMDHeader3, '<h3>', $array[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$array[$i] = preg_replace($this->FindMDNewline, "", $array[$i]);
					$array[$i] = $array[$i] . '</h3><br>';
				}
				# Check for the Markdown Header level 4, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader4, $array[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$array[$i] = preg_replace($this->FindMDHeader4, '<h4>', $array[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$array[$i] = preg_replace($this->FindMDNewline, "", $array[$i]);
					$array[$i] = $array[$i] . '</h4><br>';
				}
				# Check for the Markdown Header level 5, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader5, $array[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$array[$i] = preg_replace($this->FindMDHeader5, '<h5>', $array[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$array[$i] = preg_replace($this->FindMDNewline, "", $array[$i]);
					$array[$i] = $array[$i] . '</h5><br>';
				}
				# Check for the Markdown Header level 6, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader6, $array[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$array[$i] = preg_replace($this->FindMDHeader6, '<h6>', $array[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$array[$i] = preg_replace($this->FindMDNewline, "", $array[$i]);
					$array[$i] = $array[$i] . '</h6><br>';
				}
				# Check for any Markdown Links...
				if (preg_match_all($this->FindMDLink, $array[$i], $linkarray) > 0) {
					for ($j = 0; $j < count($linkarray); $j++) {
						var_dump($linkarray[$j]);
						echo "<br>";
						break;
					}
				}
				# Next to split the string by the markdown double space newline and append the HTML newline to the end of each of the strings in the resulting array...
				$array[$i] = preg_replace($this->FindMDNewline, '<br>', $array[$i]);
			}
			return $array;
		}
	}
?>