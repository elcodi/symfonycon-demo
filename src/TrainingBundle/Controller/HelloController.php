<?php

namespace Elcodi\TrainingBundle\Controller;

use Elcodi\Component\Currency\Entity\Money;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends Controller
{
    public function helloAction()
    {
        $productRepository = $this->container
            ->get('elcodi.repository.product');
        $storedProduct = $productRepository->find(1);

        $productObjectManager = $this->container
            ->get('elcodi.object_manager.product');

        $newProduct = $this->container
            ->get('elcodi.factory.product')->create()
            ->setName('SymfonyCon ticket');

        $productObjectManager->persist($newProduct);
        $productObjectManager->flush();

        return new Response($newProduct->getId());
    }

    public function priceAction()
    {
        $productObjectManager = $this->container
            ->get('elcodi.object_manager.product');

        $productRepository = $this->container
            ->get('elcodi.repository.product');
        $storedProduct = $productRepository->find(1);

        // Currency from a session based wrapper
        $currency = $this->container
            ->get('elcodi.currency_wrapper')->getCurrency();

        // Money Value Object
        $newPrice = Money::create(1000, $currency);

        $storedProduct->setPrice($newPrice);
        $productObjectManager->flush();

        return new Response("Changed price!");
    }
} 