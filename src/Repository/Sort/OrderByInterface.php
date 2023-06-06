<?php

namespace App\Repository\Sort;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
interface OrderByInterface {

	/**
	 * Get the value of sort
	 *
	 * @return string
	 */
	public function getSort(): string;

	/**
	 * Set the value of sort
	 *
	 * @param string $sort
	 *
	 * @return self
	 */
	public function setSort(string $sort): self;

	/**
	 * Get the value of order
	 *
	 * @return string
	 */
	public function getOrder(): string;

	/**
	 * Set the value of order
	 *
	 * @param string $order
	 *
	 * @return self
	 */
	public function setOrder(string $order): self;
}
