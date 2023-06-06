<?php

namespace App\Repository\Sort;

/**
 * @author Stas Plov <SaviouR.S@mail.ru>
 */
class Limiter implements LimiterInterface {

	private int $limit;
	private int $offset;

	public function __construct(int $limit, int $offset) {
		$this->limit = $limit;
		$this->offset = $offset;
	}

	/**
	 * Get the value of limit
	 *
	 * @return int
	 */
	public function getLimit(): int
	{
		return $this->limit;
	}

	/**
	 * Set the value of limit
	 *
	 * @param int $limit
	 *
	 * @return self
	 */
	public function setLimit(int $limit): self
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Get the value of offset
	 *
	 * @return int
	 */
	public function getOffset(): int
	{
		return $this->offset;
	}

	/**
	 * Set the value of offset
	 *
	 * @param int $offset
	 *
	 * @return self
	 */
	public function setOffset(int $offset): self
	{
		$this->offset = $offset;

		return $this;
	}
}