<?php
namespace SWLPacker;

/**
* SWL packer lets you pack a SWF file into a SWL.
*/
class SWLPacker
{
	/**
	*
	*/
	public function		__construct()
	{

	}

	/**
	* Take a SWF file and package it as a SWL file.
	* @return a binary string.
	*/
	public function		pack($swf)
	{
			$header_offset = 76;
			$version = 0;
			$framerate = 24;
			$class_count = 0;
			$swl = "";

			$swl = pack('ccIi', $header_offset, $version, $framerate,
				$class_count);
			if ($swl === false)
				throw new \Exception("Unable to pack SWF file.");
			$swl .= $swf;
			return ($swl);
	}

	/**
	* Checks that the argument is a path to a SWF file. Reads the file,
	* calls the pack function, and writes the generated SWL file.
	*
	*/
	public function		run($args)
	{
		if (isset($args[1]) === null || !file_exists($args[1]))
		{
			throw new \InvalidArgumentException("The file does not exist or " .
				"does not have the necessary permissions.");
			return false;
		}
		$path_parts = pathinfo($args[1]);
		if ($path_parts['extension'] !== 'swf')
		{
			throw new \Exception("The file does not have a .swf extension.");
			return false;
		}
		$file_descriptor = fopen($args[1], 'r');
		$swf = fread($file_descriptor, filesize($args[1]));
		$swl = $this->pack($swf);
		file_put_contents($path_parts['filename'] . '.swl', $swl);
		fclose($file_descriptor);
	}
}

/**
* Entry point of cli-packer.
*/
if (count($_SERVER['argv']) <= 1)
	return (false);
$packer = new SWLPacker();
try
{
	$packer->run($_SERVER['argv']);
}	catch (\Exception $e) {
		printf("Error: %s" . PHP_EOL, $e->getMessage())	;
}
?>
