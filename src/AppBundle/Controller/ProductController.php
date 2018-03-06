<?php
/**
 * Created by PhpStorm.
 * User: Duroy-PC
 * Date: 05/03/2018
 * Time: 14:23
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Products;
use Symfony\Component\HttpFoundation\Request;

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

	/**
	 * @param Request $req
	 * @Route("getProduct", name="get_product")
	 * @return JsonResponse|Response
	 */
	public function getProduct(Request $req)
	{
		if($req->isXmlHttpRequest())
		{
			$id = $req->get('id');
			$conn = $this->get("database_connection");
			$q = "SELECT * FROM products WHERE id = ".$id;
			$rows = $conn-> fetchAll($q);
			return new JsonResponse($rows[0]);
		}
		return new Response("Error: Not Ajax Request",400);
	}

	/**
	 * @param Request $req
	 * @Route("product/addtocart" , name="add_to_cart")
	 * @Method({"POST"})
	 * @return RedirectResponse
	 */
	public function addToCart(Request $req)
	{
		$id = $req->get('id_prod');
		$session = $this->getRequest()->getSession();
		if(!$session->has('cartElements'))
			$session->set('cartElements', array());

		$cart = $session->get('cartElements');

		if( array_key_exists( $id, $cart==null?array():$cart))
		{
			if($this->getRequest()->query->get('qtty') != null)
				$cart[$id] = $this->getRequest()->query->get('qtty');
		}
		else{
			if($this->getRequest()->query->get('qtty') != null)
				$cart[$id] = $this->getRequest()->query->get('qtty');
			else
				$cart[$id] = 1;
		}

		$session->set('cartElements', $cart);

		return $this->redirectToRoute('cart_summary');
	}

	/**
	 * @param Request $req
	 * @Route("product/get_shipping_charge", name="show_shipping_charge")
	 * @Method({"POST"})
	 * @return JsonResponse|Response
	 */
	public function getShippingChargeAction(Request $req)
	{
		if($req->isXmlHttpRequest())
		{
			$idregion = $req->get('id_region');
			$prods = $req->get('prods');

			$conn = $this->get("database_connection");
			$q = "SELECT * FROM products WHERE (";
			$i=1;
			foreach ($prods as $p)
			{
				$q .= " id = ".$p['id'];
				$q .= (count($prods)>$i) ? " OR": "";
				$i++;
			}
			$q .= " )";
			$rows = $conn-> fetchAll($q);
			$total_weight = 0;
			foreach ($rows as $r) {
				foreach ($prods as $p)
				{
					if($p['id'] == $r['id']){
						$session = $this->getRequest()->getSession();
						$cart = $session->get('cartElements');
						$cart[$p['id']] = $p['qtty'];
						$session->set('cartElements', $cart);
						$total_weight += floatval($r['weight'])*$p['qtty'];
					}
				}
			}

			$q2 = "SELECT * FROM shipping_price WHERE range_min <= ".$total_weight." AND range_max >= ".$total_weight." AND region = ".$idregion;
			$rows2 = $conn-> fetchAll($q2);
			$ship_price = $rows2[0]['price'];

			return new JsonResponse(array("ship_price"=>$ship_price));
		}
		return new Response("Error: Not Ajax Request",400);
	}
}