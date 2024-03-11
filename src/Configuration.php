<?php
	namespace MD_Reader;

	class Configuration {
		private $DefaultConfiguration = [
				'method'			=>	"POST",
			];

		function __construct($configarray) {
			foreach ($configarray as $index => $configitem) {
				echo "\$configarray['" . $index . "'] is: " . $configitem . "and has the type: " . gettype($configitem) . "<br>";
			}
		}
	}
?>