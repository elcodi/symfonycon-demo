<?php

namespace Elcodi\TrainingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends Controller
{
    public function helloAction()
    {
        return new Response("HELLO!");
    }
} 