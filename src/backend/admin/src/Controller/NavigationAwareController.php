<?php

namespace App\Controller;

use App\Entity\Navigation;
use App\Repository\NavigationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

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
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->request = $requestStack->getCurrentRequest();
        /** @var NavigationRepository */
        $navigationRepository = $em->getRepository(Navigation::class);
        /** @var Navigation[] */
        $categories = $navigationRepository->findAllCategories();
        $requestedPath = $this->request->getPathInfo();
        $categoriesArray = [];
        foreach ($categories as $category) {
            $categoriesArray[$category->getName()] = [
                'id' => $category->getId(),
                'active' => false,
                'name' => $category->getName(),
                'path' => $category->getUrl(),
                'icon' => $category->getIcon(),
                'parent_id' => null,
            ];
            if ($requestedPath === $category->getUrl()) {
                $categoriesArray[$category->getName()]['active'] = true;
            }
            foreach ($category->getChildren() as $index => $subcategory) {
                $categoriesArray[$category->getName()]['sub'][] = [
                    'id' => $subcategory->getId(),
                    'active' => false,
                    'name' => $subcategory->getName(),
                    'path' => $subcategory->getUrl(),
                    'icon' => $subcategory->getIcon(),
                    'parent_id' => $subcategory->getParent()->getId(),
                ];
                if ($requestedPath === $subcategory->getUrl()) {
                    $categoriesArray[$category->getName()][$index]['active'] = true;
                    $categoriesArray[$category->getName()]['active'] = true;
                }
            }
        }
        $this->navigation = $categoriesArray;
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
