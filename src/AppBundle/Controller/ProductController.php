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
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Products;

class ProductController extends Controller {

	/**
	 * @Route("" , name = "list_product")
	 */
	public function IndexAction(  ) {

		$templating = $this->container->get('templating');

		$products = $this->getDoctrine()
		                ->getRepository(Products::class)
		                ->findAll();
		$datas =  $templating->render("Product/list.html.twig",
			[
				'products'=>$products
			]);
		return new Response($datas);
	}
}