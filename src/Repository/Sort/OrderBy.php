<?php

namespace App\Repository\Sort;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
class OrderBy implements OrderByInterface {

	private string $sort;
	private string $order;

	public function __construct(string $sort, string $order) {
		$this->sort = $sort;
		$this->order = $order;
	}

	/**
	 * Get the value of order
	 */
	public function getOrder(): string {
		return $this->order;
	}

	/**
	 * Set the value of order
	 */
	public function setOrder($order): self {
		$this->order = $order;

		return $this;
	}

	/**
	 * Get the value of sort
	 *
	 * @return string
	 */
	public function getSort(): string {
		return $this->sort;
	}

	/**
	 * Set the value of sort
	 *
	 * @param string $sort
	 *
	 * @return self
	 */
	public function setSort(string $sort): self {
		$this->sort = $sort;

		return $this;
	}
}