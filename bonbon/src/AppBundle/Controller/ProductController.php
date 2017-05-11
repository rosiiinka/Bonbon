<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Entity\Stock;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('product/index.html.twig', array(
            'products' => $products,
            'user' => $this->getUser(),
        ));
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($this->getUser());
            /** @var UploadedFile $file */
            $file = $product->getImageForm();
            $filename = md5($product->getName() . '' . $product->getId());
            $file->move($this->get('kernel')->getRootDir() . '/../web/images/product/', $filename);
            $product->setImage($filename);

            $stock = new Stock();
            $stock->setProduct($product);
            $stock->setCount('100');

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->persist($stock);
            $em->flush();

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);

        return $this->render('product/show.html.twig', array(
            'product' => $product,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product)
    {
        if ($product->getUser()->getId() != $this->getUser()->getId() &&
            !$this->isGranted('ROLE_ADMIN', $this->getUser())
            || !$this->isGranted('ROLE_EDITOR', $this->getUser())

        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not the owner of this product');

            return $this->redirectToRoute('product_index');
        }


        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            if ($product->getImageForm() instanceof UploadedFile) {
                /** @var UploadedFile $file */
                $file = $product->getImageForm();

                $filename = md5($product->getName() . '' . $product->getId());

                $file->move(
                    $this->get('kernel')->getRootDir() . '/../web/images/product/',
                    $filename
                );

                $product->setImage($filename);
            }

            $this->getDoctrine()->getManager()->flush();

            $this->get('session')->getFlashBag()->add('success', 'Product is edited successfully!');

            return $this->redirectToRoute('product_index', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
            'product' => $product,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product)
    {
        if ($product->getUser()->getId() != $this->getUser()->getId() &&
            !$this->isGranted('ROLE_ADMIN', $this->getUser())
        ) {
            $this->get('session')->getFlashBag()->add('error', 'You are not the owner of this product');

            return $this->redirectToRoute('product_index');
        }

        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a product entity.
     *
     * @param Product $product The product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * @Route("/manage/products", name="admin_manage_products")
     */
    public function manageProductAction()
    {

        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository('AppBundle:Product')->findAll();

        return $this->render('product/manage.html.twig', array(
            'products' => $products,
            'user' => $this->getUser(),

        ));
    }

    /**
     * Deletes a product entity.
     *
     * @Route("/manage/products/{id}/delete", name="admin_product_delete")
     * @Method("GET")
     */
    public function manageDeleteAction(Request $request, Product $product)
    {


        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();


        return $this->redirectToRoute('admin_manage_products');
    }
}
