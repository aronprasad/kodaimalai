<?php

interface Ed_School_Importer_Interface {

	public function import();

	public function get_filename();

	public function set_filename( $filename );

}