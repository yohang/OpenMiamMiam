<?php

/*
 * This file is part of the OpenMiamMiam project.
 *
 * (c) Isics <contact@isics.fr>
 *
 * This source file is subject to the AGPL v3 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Isics\Bundle\OpenMiamMiamBundle\Controller;

use Isics\Bundle\OpenMiamMiamBundle\Entity\Branch;
use Isics\Bundle\OpenMiamMiamBundle\Entity\SalesOrder;
use Isics\Bundle\OpenMiamMiamBundle\Form\Type\SalesOrderConfirmationType;
use Isics\Bundle\OpenMiamMiamBundle\Model\Cart;
use Isics\Bundle\OpenMiamMiamBundle\Model\SalesOrderConfirmation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SalesOrderController extends Controller
{
    /**
     * Confirms sales order
     *
     * @ParamConverter("branch", class="IsicsOpenMiamMiamBundle:Branch", options={"mapping": {"branch_slug": "slug"}})
     *
     * @param Request $request
     * @param Branch $branch Branch
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmAction(Request $request, Branch $branch)
    {
        $cart = $this->get('open_miam_miam.cart_manager')->get($branch);
        if (count($cart->getItems()) == 0) {
            return $this->redirectToCart($cart);
        }

        $user = $this->get('security.context')->getToken()->getUser();

        $form = $this->createForm(
            new SalesOrderConfirmationType(),
            new SalesOrderConfirmation(),
            array(
                'action' => $this->generateUrl(
                    'open_miam_miam_sales_order_confirm',
                    array('branch_slug' => $cart->getBranch()->getSlug())
                ),
                'method' => 'POST'
            )
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $branchOccurrenceManager = $this->get('open_miam_miam.branch_occurrence_manager');
                $orderManager = $this->get('open_miam_miam.sales_order_manager');

                $order = $orderManager->processSalesOrder(
                    $cart,
                    $branchOccurrenceManager->getNext($branch),
                    $user,
                    $form->getData()
                );

                return $this->redirect($this->generateUrl(
                    'open_miam_miam_sales_order_confirm_creation',
                    array('branch_slug' => $branch->getSlug(), 'id' => $order->getId())
                ));
            }
        }

        return $this->render('IsicsOpenMiamMiamBundle:SalesOrder:confirm.html.twig', array(
            'branch' => $branch,
            'cart'   => $cart,
            'user'   => $user,
            'form'   => $form->createView()
        ));
    }

    /**
     * Confirms sales order
     *
     * @ParamConverter("branch", class="IsicsOpenMiamMiamBundle:Branch", options={"mapping": {"branch_slug": "slug"}})
     *
     * @param Branch $branch Branch
     * @param SalesOrder $order
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirmCreationAction(Branch $branch, SalesOrder $order)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if ($user->getId() !== $order->getUser()->getId()) {
            throw $this->createNotFoundException('Sales order not found');
        }

        return $this->render('IsicsOpenMiamMiamBundle:SalesOrder:confirmCreation.html.twig', array(
            'branch' => $branch,
            'order' => $order
        ));
    }

    /**
     * Redirect to cart
     *
     * @param Cart $cart
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToCart(Cart $cart)
    {
        return $this->redirect($this->generateUrl('open_miam_miam_cart_show', array('branch_slug' => $cart->getBranch()->getSlug())));
    }
}
