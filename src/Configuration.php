<?php
	namespace MD_Reader;

	class Configuration {
		$DefaultConfiguration = [
				'method'			=>	"POST",
			];

		function __construct($configarray) {
			foreach ($configarray as $index => $configitem) {
				echo "\$configarray[\'" . $index . "\'] is: " . $configitem . "<br>";
				echo "and has the type: " . gettype($configitem) . "<br>";
			}
		}
	}
?>