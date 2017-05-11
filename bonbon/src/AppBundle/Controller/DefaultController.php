<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Stock;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    const NUM_RESULT = 2;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/dbal/{id}/{name}")
     */
    public function dbalAction($id, $name)
    {


        $conn = $this->get('doctrine.dbal.connection_factory')->createConnection([
            'driver' => 'pdo_mysql',
            'dbname' => 'bonbon',
            'user' => 'root',
            'password' => '',
            'host' => '127.0.0.1',
            'port' => null
        ]);

        $result = $conn->prepare('SELECT * FROM product WHERE id = :id AND name like :name');

        $result->bindParam(':id', $id);
        $result->bindParam(':name', $name);

        $data = $result->execute();


        dump($result->fetch());


        return new JsonResponse([
            'succes' => true
        ]);
    }


    /**
     * @Route("/repos")
     */
    public function reposAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Category');

        dump($repo->findAll());
        return $this->render('::base.html.twig');

    }

    /**
     * @Route("/query/{category}/{published}")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function queryAction($category)
    {
        $cat = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($category);

        $result = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Product')
            ->findByPublishedAndCategory( $cat);

        dump($result);

        return $this->render('::base.html.twig');
    }

    /**
     * @Route("/pagination", name="product_pages")
     * @param Request $request
     * @Template()
     * @return array
     */
    public function productsAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $repo = $manager->getRepository('AppBundle:Product');


        $product_count = $repo->fetchProductCount();
        $products = $repo->fetchProductsPaginated($request->get('page', 1), self::NUM_RESULT);

        $pages = ceil($product_count / self::NUM_RESULT);

        return compact('products','product_count','pages');
    }




}
