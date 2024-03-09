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
		private $FindMDUnorderedListItem = '/^( - )/';

		function __construct($file) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile, filesize($file));
			fclose($mdfile);
			clearstatcache();
		}

		function InsertIntoArray(&$array, $index, $data) {
			$arraybegin = array_slice($array,0,$index - 1);
			$arraybegin[] = $data;
			$arrayend = array_slice($array,$index);
			$array = array_merge($arraybegin, $arrayend);
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array...
		function DisplayFormatted() {
			$wasunorderedlist = False;
			$FormattedOutput = explode("\n", $this->OriginalFileContent);
			for ($i = 0; $i < count($FormattedOutput); $i++) {
				$formatted[$i] = str_replace(array("\r\n", "\n", "\r"), "", $FormattedOutput[$i]);
				# Check for any Markdown Unordered Lists...
				if (preg_match_all($this->FindMDUnorderedListItem, $FormattedOutput[$i], $regexarray1) > 0) {
					if ($wasunorderedlist) {
						$FormattedOutput[$i] = preg_replace($this->FindMDUnorderedListItem, "<li>", $FormattedOutput[$i]) . "</li>";
					} else {
						$wasunorderedlist = True;
						$this->InsertIntoArray($FormattedOutput, $i, "<ul>");
						$FormattedOutput[$i + 1] = preg_replace($this->FindMDUnorderedListItem, "<li>", $FormattedOutput[$i]) . "</li";
						continue;
					}
				} else {
					if ($wasunorderedlist) {
						$wasunorderedlist = False;
						$this->InsertIntoArray($FormattedOutput, $i, "</ul><br>");
						continue;
					}
				}
				# Check for the Markdown Header level 1, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader1, $FormattedOutput[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$FormattedOutput[$i] = preg_replace($this->FindMDHeader1, '<h1>', $FormattedOutput[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
					$FormattedOutput[$i] = $FormattedOutput[$i] . '</h1><br>';
				}
				# Check for the Markdown Header level 2, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader2, $FormattedOutput[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$FormattedOutput[$i] = preg_replace($this->FindMDHeader2, '<h2>', $FormattedOutput[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
					$FormattedOutput[$i] = $FormattedOutput[$i] . '</h2><br>';
				}
				# Check for the Markdown Header level 3, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader3, $FormattedOutput[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$FormattedOutput[$i] = preg_replace($this->FindMDHeader3, '<h3>', $FormattedOutput[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
					$FormattedOutput[$i] = $FormattedOutput[$i] . '</h3><br>';
				}
				# Check for the Markdown Header level 4, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader4, $FormattedOutput[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$FormattedOutput[$i] = preg_replace($this->FindMDHeader4, '<h4>', $FormattedOutput[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
					$FormattedOutput[$i] = $FormattedOutput[$i] . '</h4><br>';
				}
				# Check for the Markdown Header level 5, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader5, $FormattedOutput[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$FormattedOutput[$i] = preg_replace($this->FindMDHeader5, '<h5>', $FormattedOutput[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
					$FormattedOutput[$i] = $FormattedOutput[$i] . '</h5><br>';
				}
				# Check for the Markdown Header level 6, remove it and add the h1 opening and closing tags for HTML...
				if (preg_match($this->FindMDHeader6, $FormattedOutput[$i]) == 1) {
					# Need to remove the carriage returns and line feeds...
					$FormattedOutput[$i] = preg_replace($this->FindMDHeader6, '<h6>', $FormattedOutput[$i]);
					# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
					$FormattedOutput[$i] = $FormattedOutput[$i] . '</h6><br>';
				}
				# Check for any Markdown Links...
				if (preg_match_all($this->FindMDLink1, $FormattedOutput[$i], $regexarray1) > 0) {
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
							$FormattedOutput[$i] = preg_replace($this->FindMDLink1, $finalstr, $FormattedOutput[$i]);
						} else {
							$str = substr($regexarray1[0][$j], 1, (strlen($regexarray1[0][$j]) - 2));
							$linkcontent = explode("](", $str);
							$FormattedOutput[$i] = preg_replace($this->FindMDLink1, "<a href=" . $linkcontent[1] . ">" . $linkcontent[0] . "</a>", $FormattedOutput[$i]);
						}
					}
				}
				# Next to split the string by the markdown double space newline and append the HTML newline to the end of each of the strings in the resulting array...
				$FormattedOutput[$i] = preg_replace($this->FindMDNewline, '<br>', $FormattedOutput[$i]);
			}
			return $FormattedOutput;
		}
	}
?>