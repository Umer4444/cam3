<?php
namespace Videos\Controller;

use Application\Entity\Categories;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PerfectWeb\Core\Traits;

class VideosController extends AbstractActionController
{
    use Traits\EntityManager;

    public function videosAction()
    {}

    public function videosListAction()
    {
        /** @var \Videos\Paginator\VideosPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Videos\Paginator\VideosPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(['paginator' => $paginator]);
    }

    public function videoAction()
    {}

    public function clipsListAction()
    {
        /** @var \Videos\Paginator\VideosPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Videos\Paginator\VideosPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(['paginator' => $paginator]);

    }

    public function clipAction()
    {}

    public function clipsAction()
    {}

    public function camsListAction()
    {
        /** @var \Videos\Paginator\VideosPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Videos\Paginator\UserPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(['paginator' => $paginator]);

    }

    public function camAction()
    {}

    public function camsAction()
    {}

    public function vodsListAction()
    {

        /** @var \Videos\Paginator\VideosPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Videos\Paginator\VodVideosPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(
            ['paginator' => $paginator]
        );

    }

    public function vodCategoryAction()
    {}

    public function vodsCategoryAction()
    {
        /** @var \Videos\Paginator\VideosPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Videos\Paginator\VodCategoryPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new Categories(
            ['paginator' => $paginator]
        );
    }

    public function vodAction()
    {}

    public function vodsAction()
    {}

    public function premiersListAction()
    {
        /** @var \Videos\Paginator\VideosPaginator $paginator */
        $paginator = $this->getServiceLocator()->get(\Videos\Paginator\PremiereVideoPaginator::class);
        $paginator->setData(array_merge($this->params()->fromRoute(), ['route' => true]));

        return new ViewModel(
            ['paginator' => $paginator]
        );

    }

    public function premiereAction()
    {}

    public function premiersAction()
    {}

}