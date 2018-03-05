<?php
/**
 * Created by PhpStorm.
 * User: Duroy-PC
 * Date: 05/03/2018
 * Time: 14:49
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller{

	/**
	 * @Route("/cart", name = "cart_summary"))
	 */

	public function IndexAction()
	{
		return $this->render("Cart/index.html.twig");
	}

}