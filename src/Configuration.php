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
			$this->Configuration["doc_file"] = preg_replace('/( [\.][\/] )/', "", $mdinfo["basename"]);
			echo $this->Configuration["doc_file"] . "<br>";
			$this->Configuration["doc_root"] = $mdinfo["dirname"];
			$this->Configuration["doc_root"] = preg_replace('/( [\.][\/] )/', "", $this->Configuration["doc_root"]);
			echo $this->Configuration["doc_root"] . "<br>";
			foreach ($configarray as $index => $configitem) {
				if ($index != "doc_file" && $index != "doc_root") {
					$this->Configuration[$index] = $configitem;
				}
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