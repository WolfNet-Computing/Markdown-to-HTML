<?php
	namespace WolfNet_Computing\MD_Reader;

	class HtmlParser {
		private $NumberOfPasses = 3;
		
		private $OriginalFileContent;
		private $FindMDNewline = "/[ ]{2}/";
		private $FindMDHeader1 = '/^(# )/';
		private $FindMDHeader2 = '/^(## )/';
		private $FindMDHeader3 = '/^(### )/';
		private $FindMDHeader4 = '/^(#### )/';
		private $FindMDHeader5 = '/^(##### )/';
		private $FindMDHeader6 = '/^(###### )/';
		private $FindMDLink1 = '/\[.+\]\(.+\)/';
		private $FindMDLink2 = '/\).+\[/';
		private $FindMDUnorderedListItem = '/^( (?:-|\*|\+) )/';
		private $FindMDFirstOrderedListItem = '/^( [1][\.] )/';
		private $FindMDAnyOrderedListItem = '/^( [\d]+[\.] )/';
		private $FindMDBoldTextItem = '/[\*\_]{2}.+[\*\_]{2}/';
		private $FindMDItalicTextItem = '/[\*\_].+[\*\_]/';
		private $FindMDEscape = '/[\\]/';

		function __construct($file) {
			$mdfile = fopen($file, 'r') or die('Unable to open file!');
			$this->OriginalFileContent = fread($mdfile, filesize($file));
			fclose($mdfile);
			clearstatcache();
		}

		function InsertIntoArray(&$array, $index, $data) {
			$array = array_pad($array, count($array) + 1, "");
			for ($i = count($array) - 1; $i > $index; $i--) {
				$array[$i] = $array[$i - 1];
			}
			$array[$index] = $data;
		}

		# Returns the HTML formatted array of lines contained in the $HtmlFormattedMarkdown array...
		function DisplayFormatted() {
			for ($p = 0; $p < $this->NumberOfPasses; $p++) {
				$wasunorderedlist = False;
				$wasorderedlist = False;
				$FormattedOutput = explode("\n", $this->OriginalFileContent);
				for ($i = 0; $i < count($FormattedOutput); $i++) {
					$formatted[$i] = str_replace(array("\r\n", "\n", "\r"), "", $FormattedOutput[$i]);
					# if Markdown Bold Text...
					if (preg_match($this->FindMDBoldTextItem, $FormattedOutput[$i], $regexarray1) == 1) {
						for ($j = 0; $j < count($regexarray1); $j++) {
							if (preg_match_all($this->FindMDBoldTextItem, $regexarray1[$j], $regexarray2) > 0) {
								$finalstr = "";
								for ($k = 0; $k < count($regexarray2); $k++) {
									for ($l = 0; $l < count($regexarray2[$k]); $l++) {
										$boldremoved = explode($regexarray2[$k][$l], $regexarray1[$j]);
										if ($k != 0) {
											$finalstr = $finalstr . $boldremoved[0];
										}
										$finalstr = $finalstr . preg_replace('/(^[\*\_]{2})/', "<strong>", substr($regexarray2[$k][$l], 0, strlen($regexarray2[$k][$l]) - 2)) . "</strong>";
									}
								}
								$FormattedOutput[$i] = preg_replace($this->FindMDBoldTextItem, $finalstr, $FormattedOutput[$i]);
							} else {
								$FormattedOutput[$i] = preg_replace('/(^[\*\_]{2})/', "<strong>", substr($FormattedOutput[$i], 2, strlen($FormattedOutput[$i] - 4))) . "</strong>";
							}
						}
					}
					# if Markdown Italic Text...
					if (preg_match($this->FindMDItalicTextItem, $FormattedOutput[$i], $regexarray1) == 1) {
						for ($j = 0; $j < count($regexarray1); $j++) {
							if (preg_match_all($this->FindMDItalicTextItem, $regexarray1[$j], $regexarray2) > 0) {
								$finalstr = "";
								for ($k = 0; $k < count($regexarray2); $k++) {
									for ($l = 0; $l < count($regexarray2[$k]); $l++) {
										$italicremoved = explode($regexarray2[$k][$l], $regexarray1[$j]);
										if ($k != 0) {
											$finalstr = $finalstr . $italicremoved[0];
										}
										$finalstr = $finalstr . preg_replace('/(^[\*\_])/', "<em>", substr($regexarray2[$k][$l], 0, strlen($regexarray2[$k][$l]) - 1)) . "</em>";
									}
								}
								$FormattedOutput[$i] = preg_replace($this->FindMDItalicTextItem, $finalstr, $FormattedOutput[$i]);
							} else {
								$FormattedOutput[$i] = preg_replace('/(^[\*\_])/', "<em>", substr($FormattedOutput[$i], 1, strlen($FormattedOutput[$i] - 2))) . "</em>";
							}
						}
					}
					# if Markdown Unordered List...
					if (preg_match($this->FindMDUnorderedListItem, $FormattedOutput[$i], $regexarray1) == 1) {
						if ($wasunorderedlist) {
							# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
							$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
							$FormattedOutput[$i] = preg_replace($this->FindMDUnorderedListItem, "<li>", $FormattedOutput[$i]) . "</li>";
						} else {
							$wasunorderedlist = True;
							$this->InsertIntoArray($FormattedOutput, $i, "<ul>");
							continue;
						}
					} else {
						if ($wasunorderedlist) {
							$wasunorderedlist = False;
							$this->InsertIntoArray($FormattedOutput, $i, "</ul><br>");
							continue;
						}
					}
					# if Markdown Ordered List...
					if (preg_match($this->FindMDFirstOrderedListItem, $FormattedOutput[$i], $regexarray1) == 1) {
						if ($wasorderedlist) {
							# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
							$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
							$FormattedOutput[$i] = preg_replace($this->FindMDFirstOrderedListItem, "<li>", $FormattedOutput[$i]) . "</li>";
						} else {
							$wasorderedlist = True;
							$this->InsertIntoArray($FormattedOutput, $i, "<ol>");
							continue;
						}
					} elseif ((preg_match($this->FindMDAnyOrderedListItem, $FormattedOutput[$i], $regexarray1) == 1) && $wasorderedlist) {
						if ($wasorderedlist) {
							# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
							$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
							$FormattedOutput[$i] = preg_replace($this->FindMDAnyOrderedListItem, "<li>", $FormattedOutput[$i]) . "</li>";
						}
					} elseif ($wasorderedlist) {
						$wasorderedlist = False;
						$this->InsertIntoArray($FormattedOutput, $i, "</ol><br>");
						continue;
					}
					# if Markdown Header level 1...
					if (preg_match($this->FindMDHeader1, $FormattedOutput[$i]) == 1) {
						# Need to remove the carriage returns and line feeds...
						$FormattedOutput[$i] = preg_replace($this->FindMDHeader1, '<h1>', $FormattedOutput[$i]);
						# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
						$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
						$FormattedOutput[$i] = $FormattedOutput[$i] . '</h1><br>';
					}
					# if Markdown Header level 2...
					if (preg_match($this->FindMDHeader2, $FormattedOutput[$i]) == 1) {
						# Need to remove the carriage returns and line feeds...
						$FormattedOutput[$i] = preg_replace($this->FindMDHeader2, '<h2>', $FormattedOutput[$i]);
						# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
						$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
						$FormattedOutput[$i] = $FormattedOutput[$i] . '</h2><br>';
					}
					# if Markdown Header level 3...
					if (preg_match($this->FindMDHeader3, $FormattedOutput[$i]) == 1) {
						# Need to remove the carriage returns and line feeds...
						$FormattedOutput[$i] = preg_replace($this->FindMDHeader3, '<h3>', $FormattedOutput[$i]);
						# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
						$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
						$FormattedOutput[$i] = $FormattedOutput[$i] . '</h3><br>';
					}
					# if Markdown Header level 4...
					if (preg_match($this->FindMDHeader4, $FormattedOutput[$i]) == 1) {
						# Need to remove the carriage returns and line feeds...
						$FormattedOutput[$i] = preg_replace($this->FindMDHeader4, '<h4>', $FormattedOutput[$i]);
						# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
						$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
						$FormattedOutput[$i] = $FormattedOutput[$i] . '</h4><br>';
					}
					# if Markdown Header level 5...
					if (preg_match($this->FindMDHeader5, $FormattedOutput[$i]) == 1) {
						# Need to remove the carriage returns and line feeds...
						$FormattedOutput[$i] = preg_replace($this->FindMDHeader5, '<h5>', $FormattedOutput[$i]);
						# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
						$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
						$FormattedOutput[$i] = $FormattedOutput[$i] . '</h5><br>';
					}
					# if Markdown Header level 6...
					if (preg_match($this->FindMDHeader6, $FormattedOutput[$i]) == 1) {
						# Need to remove the carriage returns and line feeds...
						$FormattedOutput[$i] = preg_replace($this->FindMDHeader6, '<h6>', $FormattedOutput[$i]);
						# Need to remove the Markdown newline character so it isn't processed later as we are adding our own manually here...
						$FormattedOutput[$i] = preg_replace($this->FindMDNewline, "", $FormattedOutput[$i]);
						$FormattedOutput[$i] = $FormattedOutput[$i] . '</h6><br>';
					}
					# if Markdown Link...
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
					# if Markdown New Line...
					$FormattedOutput[$i] = preg_replace($this->FindMDNewline, '<br>', $FormattedOutput[$i]);
					# if Markdown Escape Character...
					$FormattedOutput[$i] = preg_replace($this->FindMDEscape, '', $FormattedOutput[$i]);
					# if we're on the last line and there still remain unclosed statements then close them for displaying...
					if ($i == count($FormattedOutput)) {
						if ($wasunorderedlist) {
							$wasunorderedlist = True;
							$this->InsertIntoArray($FormattedOutput, $i, "</ul><br>");
						} elseif ($wasorderedlist) {
							$wasorderedlist = True;
							$this->InsertIntoArray($FormattedOutput, $i, "</ol><br>");
						}
					}
				}
				return $FormattedOutput;
			}
		}
	}
?>