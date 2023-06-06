<?php

namespace App\Repository\Sort;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
interface LimiterInterface {

	/**
	 * Get the value of limit
	 *
	 * @return int
	 */
	public function getLimit(): int;

	/**
	 * Set the value of limit
	 *
	 * @param int $limit
	 *
	 * @return self
	 */
	public function setLimit(int $limit): self;

	/**
	 * Get the value of offset
	 *
	 * @return int
	 */
	public function getOffset(): int;

	/**
	 * Set the value of offset
	 *
	 * @param int $offset
	 *
	 * @return self
	 */
	public function setOffset(int $offset): self;
}
