<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{

    /**
     * @Route("/cart/add", name="cart_add")
     */
    public function addAction(Request $request)
    {

        $manager = $this->getDoctrine()->getManager();
        $session = $this->get('session');


        $id_cart = $session->get('id_cart', false);

        if (!$id_cart) {
            $cart = new Cart();
            $cart->setUserId(1);
            $cart->setDateCreated(new \DateTime());
            $cart->setDateUpdated(new \DateTime());

            $manager->persist($cart);
            $manager->flush();

            $session->set('id_cart', $cart->getId());
        }

        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find($session->get('id_cart', false));

        $products = $request->get('products');

        foreach ($products as $id_product) {
            $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id_product);

            if ($product) {


                $cp = $this->getDoctrine()->getRepository('AppBundle:CartProduct')->findOneBy([
                    'cart' => $cart,
                    'product' => $product
                ]);

                if (!$cp) {
                    $cp = new CartProduct();
                    $cp->setCart($cart);
                    $cp->setProduct($product);
                    $cp->setQty(1);
                } else {
                    $cp->setQty($cp->getQty() + 1);
                }


                $manager->persist($cp);
            }
        }

        $cart->setDateUpdated(new \DateTime());

        $manager->persist($cart);

        $manager->flush();


        return $this->redirectToRoute('cart_list');
    }


    /**
     * @Route("/cart/list", name="cart_list")
     */
    public function listAction()
    {
        $session = $this->get('session');
        $cart = $this->getDoctrine()->getRepository('AppBundle:Cart')->find($session->get('id_cart', false));
        if (!$cart) {
            throw  new NotFoundHttpException();
        }

        return $this->render("cart/list.html.twig", ['cart' => $cart]);
    }


}
