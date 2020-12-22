<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
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
        $categories = [];
        foreach ($router->getRouteCollection()->getIterator() as $routeName => $route) {
            if (preg_match('/^categories_(.*)/', $routeName, $matches)) {
                $categoryName = $matches[1];
                $isCurrent = $this->request->get('_route') === $routeName ? true : false;
                $categories[] = [
                    'active' => $isCurrent,
                    'path' => $route->getPath(),
                    'name' => ucfirst($categoryName),
                ];
            }
        }
        $this->categories = array_reverse($categories);
    }

    /**
     * Pass categories to view.
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters = array_merge([
            'categories' => $this->categories,
        ], $parameters);

        return parent::render($view, $parameters);
    }
}
