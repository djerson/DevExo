<?php
/**
 * Created by PhpStorm.
 * User: Duroy-PC
 * Date: 05/03/2018
 * Time: 14:23
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller {

	/**
	 * @Route("" , name = "list_product")
	 */
	public function IndexAction(  ) {
		return $this->render("Product/list.html.twig");
	}
}