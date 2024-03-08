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
		private $FindMDLink1 = '/\[.+\]\(.+\)/';
		private $FindMDLink2 = '/\).+\[/';

		function __construct($file) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile, filesize($file));
			fclose($mdfile);
			clearstatcache();
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array...
		function DisplayFormatted() {
			$line = explode("\n", $this->OriginalFileContent);
			for ($i = 0; $i < count($line); $i++) {
				$line[$i] = str_replace(array("\r\n", "\n", "\r"), "", $line[$i]);
				# Check for the Markdown Header level 1, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader1, $line[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$line[$i] = preg_replace($this->FindMDHeader1, '<h1>', $line[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$line[$i] = preg_replace($this->FindMDNewline, "", $line[$i]);
					$line[$i] = $line[$i] . '</h1><br>';
				}
				# Check for the Markdown Header level 2, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader2, $line[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$line[$i] = preg_replace($this->FindMDHeader2, '<h2>', $line[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$line[$i] = preg_replace($this->FindMDNewline, "", $line[$i]);
					$line[$i] = $line[$i] . '</h2><br>';
				}
				# Check for the Markdown Header level 3, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader3, $line[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$line[$i] = preg_replace($this->FindMDHeader3, '<h3>', $line[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$line[$i] = preg_replace($this->FindMDNewline, "", $line[$i]);
					$line[$i] = $line[$i] . '</h3><br>';
				}
				# Check for the Markdown Header level 4, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader4, $line[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$line[$i] = preg_replace($this->FindMDHeader4, '<h4>', $line[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$line[$i] = preg_replace($this->FindMDNewline, "", $line[$i]);
					$line[$i] = $line[$i] . '</h4><br>';
				}
				# Check for the Markdown Header level 5, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader5, $line[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$line[$i] = preg_replace($this->FindMDHeader5, '<h5>', $line[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$line[$i] = preg_replace($this->FindMDNewline, "", $line[$i]);
					$line[$i] = $line[$i] . '</h5><br>';
				}
				# Check for the Markdown Header level 6, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader6, $line[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$line[$i] = preg_replace($this->FindMDHeader6, '<h6>', $line[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$line[$i] = preg_replace($this->FindMDNewline, "", $line[$i]);
					$line[$i] = $line[$i] . '</h6><br>';
				}
				# Check for any Markdown Links...
				if (preg_match_all($this->FindMDLink1, $line[$i], $regexarray1) > 0) {
					for ($j = 0; $j < count($regexarray1[0]); $j++) {
						if (preg_match_all($this->FindMDLink2, $regexarray1[0][$j], $regexarray2) > 0) {
							$finalstr = "";
							for ($k = 0; $k < count($regexarray2[0]); $k++) {
								$explodedlink = explode(substr($regexarray2[0][$k], 1, (strlen($regexarray2[0][$k]) - 2)), $regexarray1[0][$j]);
								for ($l = 0; $l < count($explodedlink); $l++) {
									$str = substr($explodedlink[$l], 1, (strlen($explodedlink[$l]) - 2));
									$linkcontent = explode("](", $str);
									if ($l != 0) {
										$finalstr = $finalstr . substr($regexarray2[0][$k], 1, (strlen($regexarray2[0][$k]) - 2));
									}
									$finalstr = $finalstr . "<a href=" . $linkcontent[1] . ">" . $linkcontent[0] . "</a>";
								}
							}
							$line[$i] = preg_replace($this->FindMDLink2, $finalstr, $line[$i]);
						} else {
							$str = substr($regexarray1[0][$j], 1, (strlen($regexarray1[0][$j]) - 2));
							$linkcontent = explode("](", $str);
							$line[$i] = preg_replace($this->FindMDLink1, "<a href=" . $linkcontent[1] . ">" . $linkcontent[0] . "</a>", $line[$i]);
						}
					}
				}
				# Next to split the string by the markdown double space newline and append the HTML newline to the end of each of the strings in the resulting array...
				$line[$i] = preg_replace($this->FindMDNewline, '<br>', $line[$i]);
			}
			return $line;
		}
	}
?>