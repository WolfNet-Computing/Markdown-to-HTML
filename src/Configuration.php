<?php
	namespace MD_Reader;

	class Configuration {
		private $DefaultConfiguration = [
			'method'	=>	"GET"
		];
		public $Configuration;

		function __construct($configarray) {
			$this->Configuration = $this->DefaultConfiguration;
			$mdinfo = pathinfo($configarray["doc_file"]);
			$this->Configuration["doc_file"] = $mdinfo["basename"];
			$this->Configuration["doc_root"] = dirname($mdinfo["dirname"]);
			$this->Configuration["doc_root"] = preg_replace('#([.][/])#', "/", $this->Configuration["doc_root"]);
			foreach ($configarray as $index => $configitem) {
				$this->Configuration[$index] = $configitem;
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
		}
	}
?>