<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

abstract class NavigationAwareController extends AbstractController
{
    /**
     * @var array
     */
    protected $navigation;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Initialize request and navigation array.
     */
    public function __construct(RequestStack $requestStack, RouterInterface $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $navigation = [];
        $id = 1;
        $parentId = null;
        $categoryName = '';
        foreach ($router->getRouteCollection()->getIterator() as $routeName => $route) {
            $requestedRouteName = $this->request->get('_route');
            if (preg_match('/^navigation_parent_(.*)/', $routeName, $matches)) {
                $parentId = $id;
                $categoryName = $matches[1];
                $isCurrentCat = ($requestedRouteName === $routeName || preg_match(sprintf('/^navigation_sub_%s_(.*)/', $categoryName), $requestedRouteName)) ? true : false;
                $navigation[$categoryName] = [
                    'id' => $parentId,
                    'active' => $isCurrentCat,
                    'path' => $route->getPath(),
                    'name' => ucfirst($categoryName),
                    'parent_id' => null,
                ];
            } else if (preg_match(sprintf('/^navigation_sub_%s_(.*)/', $categoryName), $routeName, $matches)) {
                $subCategoryName = $matches[1];
                $isCurrentSubCat = $requestedRouteName === $routeName ? true : false;
                $navigation[$categoryName]['sub'][] = [
                    'id' => $id,
                    'active' => $isCurrentSubCat,
                    'path' => $route->getPath(),
                    'name' => ucfirst($subCategoryName),
                    'parent' => $parentId,
                ];
            } else {
                $parentId = null;
                $categoryName = '';
            }
            $id++;
        }
        $this->navigation = array_reverse($navigation);
    }

    /**
     * Pass navigation to view.
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $parameters = array_merge([
            'navigation' => $this->navigation,
        ], $parameters);

        return parent::render($view, $parameters);
    }
}
