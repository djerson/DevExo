<?php
/**
 * Created by PhpStorm.
 * User: Duroy-PC
 * Date: 05/03/2018
 * Time: 14:49
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Regions;

class CartController extends Controller{

	/**
	 * @Route("/cart", name = "cart_summary"))
	 */

	public function IndexAction()
	{
		$templating = $this->container->get('templating');
		$session = $this->getRequest()->getSession();
		if(!$session->has('cartElements'))
			$session->set('cartElements', array());
		$cart = $session->get('cartElements');
		if(count($cart) <= 0)
		{
			$datas =  $templating->render("Cart/index.html.twig",
				[
					'empty_cart' => true
				]
			);
			return new Response($datas);
		}else{

			$conn = $this->get("database_connection");
			$q = "SELECT * FROM products WHERE (";
			$i=1;
			foreach ($cart as $key => $value)
			{
				$q .= " id = ".$key;
				$q .= (count($cart)>$i) ? " OR": "";
				$i++;
			}
			$q .= ")";
			$rows = $conn-> fetchAll($q);
			$prods = array();
			$sub_total = 0;
			$total_weight = 0;
			foreach ($rows as $prod)
			{
				$prod_line = array(
					'id' => $prod['id'],
					'name' => $prod['name'],
					'price' => $prod['price'],
					'qtty' => $cart[$prod['id']],
					'total' => $prod['price'] * $cart[$prod['id']],
					'weight' => $prod['weight']
				);
				$prods[] = $prod_line;
				$sub_total += $prod['price'] * $cart[$prod['id']];
				$total_weight += $prod['weight'];
			}
			$q2 = "SELECT * FROM shipping_price WHERE range_min <= ".$total_weight." AND range_max >= ".$total_weight." AND region = 1";
			$rows_ship = $conn-> fetchAll($q2);

			$datas =  $templating->render("Cart/index.html.twig",
				[
					'products' => $prods,
					'sub_total' => $sub_total,
					'shipping_charge' => $rows_ship[0]['price'],
					'empty_cart' => false
				]
			);
			return new Response($datas);
		}
	}


	/**
	 * @Route("regions", name="all_region")
	 */
	public function getAllRegionAction(Request $req)
	{
		if($req->isXmlHttpRequest())
		{
			$conn = $this->get("database_connection");
			$q = "SELECT * FROM regions";
			$rows = $conn-> fetchAll($q);
			return new JsonResponse($rows);
		}
		return new Response("Error: Not Ajax Request",400);
	}

	/**
	 * @param Request $req
	 * @Route("cart/getAllproduct", name="get_cart_products")
	 * @return Response
	 */
	public function getCartAction(Request $req)
	{
		if($req->isXmlHttpRequest()) {

			return new JsonResponse(null);
		}
		return new Response("Error: Not Ajax Request",400);
	}

	/**
	 * @param Request $req
	 * @Route("cart/delete/{id}" , name="delete_from_cart")
	 * @return RedirectResponse
	 */
	public function deleteFromCartAction(Request $req, $id = -1)
	{
		$session = $this->getRequest()->getSession();

		$cart = $session->get('cartElements') ;
		unset($cart[$id]);
		$session->set('cartElements', $cart);

		return $this->redirectToRoute('cart_summary');
	}

}