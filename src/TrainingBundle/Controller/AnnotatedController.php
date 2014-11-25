<?php

namespace Elcodi\TrainingBundle\Controller;

use Mmoreram\ControllerExtraBundle\Annotation\Paginator as PaginatorAnnotation;
use Symfony\Component\HttpKernel\Tests\Controller;

class AnnotatedController extends Controller
{
    /**
     * Simple controller method
     *
     * @PaginatorAnnotation(
     *      class = "MmoreramCustomBundle:User",
     *      wheres = {
     *          {"x", "enabled", "=", true},
     *          {"x", "age", ">", “~age~”},
     *          {"x", "name", "LIKE", "Efervescencio"},
     *      }
     * )
     */
    public function paginatorAction(Paginator $paginator)
    {
    }

    /**
     * Simple controller method
     *
     * @Route(
     *      path = "/user/{id}",
     *      name = "view_user"
     * )
     * @ParamConverter("user", class="MmoreramCustomBundle:User")
     * @FormAnnotation(
     *      class         = "user_type",
     *      entity        = "user"
     *      handleRequest = true,
     *      name          = "userForm",
     *      validate      = "isValid",
     * )
     */
    public function formAction(User $user, Form $userForm, $isValid)
    {
    }

    /**
     * Simple controller method
     *
     * This Controller matches pattern /user/edit/{id}/{username}
     *
     * @EntityAnnotation(
     *      class = {
     *          "factory" = my.bundle.factory.myentity_factory,
     *          "method" = "create",
     *          "static" = true,
     *      },
     *      name  = "user",
     *      mapping = {
     *          "id": "~id~",
     *          "username": "~username~"
     *      }
     * )
     */
    public function entityAction(User $user)
    {
    }
} 