<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class CategoriesAwareController extends AbstractController
{
    /**
     * @var array
     */
    protected $categories;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Initialize request and categories array.
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->categories = [];
        foreach ($router->getRouteCollection()->getIterator() as $routeName => $route) {
            if (preg_match('/^categories_(.*)/', $routeName, $matches)) {
                $isCurrent = $this->request->get('_route') === $routeName ? true : false;
                $this->categories[] = [
                    'active' => $isCurrent,
                    'path' => $route->getPath(),
                    'name' => ucfirst($matches[1]),
                ];
            }
        }
    }
}
