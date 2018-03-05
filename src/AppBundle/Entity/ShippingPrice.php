<?php
/**
 * Created by PhpStorm.
 * User: Duroy-PC
 * Date: 05/03/2018
 * Time: 18:27
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="shipping_price")
 */
class ShippingPrice {

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\OneToOne(targetEntity="AppBundle\Entity\Regions", cascade={"persist"})
	 */
	private $region;

	/**
	 * @ORM\Column(type="float")
	 */
	private $price;

	/**
	 * @ORM\Column(type="float")
	 */
	private $range_min;

	/**
	 * @ORM\Column(type="float")
	 */
	private $range_max;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * @param mixed $region
	 */
	public function setRegion( $region ) {
		$this->region = $region;
	}

	/**
	 * @return mixed
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param mixed $price
	 */
	public function setPrice( $price ) {
		$this->price = $price;
	}

	/**
	 * @return mixed
	 */
	public function getRangeMin() {
		return $this->range_min;
	}

	/**
	 * @param mixed $range_min
	 */
	public function setRangeMin( $range_min ) {
		$this->range_min = $range_min;
	}

	/**
	 * @return mixed
	 */
	public function getRangeMax() {
		return $this->range_max;
	}

	/**
	 * @param mixed $range_max
	 */
	public function setRangeMax( $range_max ) {
		$this->range_max = $range_max;
	}



}