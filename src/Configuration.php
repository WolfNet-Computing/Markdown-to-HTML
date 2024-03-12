<?php
	namespace MD_Reader;

	class Configuration {
		private $DefaultConfiguration = [
			'method'	=>	"GET"
		];
		public $Configuration;

		function __construct($configarray) {
			if ($this->Configuration["method"] != "GET") {
				throw new Exception("class MD_Reader\Configuration contains an invalid method.");
			}
			if (array_key_exists("method_var", $this->Configuration) != True) {
				throw new Exception("class MD_Reader\Configuration doesn't contain a method_var key.");
			}
			if (array_key_exists("doc_handler", $this->Configuration) != True) {
				throw new Exception("class MD_Reader\Configuration doesn't contain a doc_handler key.");
			}
			if (array_key_exists("doc_file", $this->Configuration) != True) {
				throw new Exception("class MD_Reader\Configuration doesn't contain a doc_file key.");
			}
			$this->Configuration = $this->DefaultConfiguration;
			$mdinfo = pathinfo($configarray["doc_file"]);
			$this->Configuration["doc_root"] = $mdinfo["dirname"];
			foreach ($configarray as $index => $configitem) {
				$this->Configuration[$index] = $configitem;
			}
		}
	}
?>