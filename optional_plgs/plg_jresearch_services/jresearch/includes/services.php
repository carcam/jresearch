<?php

abstract class JResearchServices
{
	/**
	 * @return string
	 */
	abstract public function getTitle();
	/**
	 * @return string
	 */
	abstract public function getAbstract();
	/**
	 * @return array
	 */
	abstract public function getAuthors();
	/**
	 * @return bool
	 */
	abstract public function hasResult();
}

?>