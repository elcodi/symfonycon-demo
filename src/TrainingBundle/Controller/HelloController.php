<?php

namespace Elcodi\TrainingBundle\Controller;

use Elcodi\Component\Currency\Entity\Money;
use Elcodi\Component\Product\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
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
            ->get('elcodi.currency_wrapper')->loadCurrency();

        // Money Value Object
        $newPrice = Money::create(1000, $currency);

        $storedProduct->setPrice($newPrice);
        $productObjectManager->flush();

        return new Response("Changed price!");
    }

    public function changeMediaAction()
    {
        $productRepository = $this->container
            ->get('elcodi.repository.product');
        $storedProduct = $productRepository->find(1);

        // This can also be an instance of UploadedFile
        $file = new File('/tmp/my_image.jpg');

        // Factors an Image and sets media meta-data
        $image = $this->container
            ->get('elcodi.image_manager')
            ->createImage($file);

        $imageObjectManager = $this->container
            ->get('elcodi.object_manager.image');
        $imageObjectManager->persist($image);
        $imageObjectManager->flush($image);

        // Stores the image using the underlying
        // Gaufrette adapter
        $this->container
            ->get('elcodi.file_manager')
            ->uploadFile($image, $image->getContent(), false);

        $storedProduct->addImage($image);
        $storedProduct->setPrincipalImage($image);
        $this->container
            ->get('elcodi.object_manager.product')
            ->flush();

        return new Response("Changed image!");
    }

    public function addProductAction()
    {
        $productRepository = $this->container
            ->get('elcodi.repository.product');
        $storedProduct = $productRepository->find(1);

        $cart = $this->container
            ->get('elcodi.cart_wrapper')->loadCart();

        $this->container
            ->get('elcodi.cart_manager')
            ->addProduct($cart, $storedProduct, 1);

        return new Response('Added to cart!');
    }

    public function createOrderAction()
    {
        // We assume we have an authenticated customer
        $customer = $this->container
            ->get('elcodi.repository.customer')->find(1);
        $this->container
            ->get('elcodi.customer_wrapper')->setCustomer($customer);

        $cart = $this->container
            ->get('elcodi.cart_wrapper')->loadCart();

        $order = $this->container
            ->get('elcodi.cart_order_transformer')
            ->createOrderFromCart($cart);

        return new Response(
            sprintf('Order %d created! ', $order->getId())
        );
    }
}
