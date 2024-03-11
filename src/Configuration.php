<?php
	namespace MD_Reader;

	class Configuration {
		private $DefaultConfiguration = [
			'method'	=>	"POST",
		];
		public $Configuration;

		function __construct($configarray) {
			$Configuration = $DefaultConfiguration;
			foreach ($configarray as $index => $configitem) {
				echo "\$configarray['" . $index . "'] is: " . $configitem . " and has the type: " . gettype($configitem) . "<br>";
				foreach ($DefaultConfiguration as $DefaultConfigurationIndex => $DefaultConfigurationItem) {
					if ($index == $DefaultConfigurationIndex) {
						echo "Found a match in the default configuration.<br>";
					}
					$Configuration[$index] = $configitem;
				}
			}
		}
	}
?>