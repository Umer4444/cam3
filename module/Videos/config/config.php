<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Videos\Controller\Videos' => 'Videos\Controller\VideosController',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'partial/video' => __DIR__ . '/../view/videos/partials/video.twig',
            'partial/clip' => __DIR__ . '/../view/videos/partials/clip.twig',
            'partial/profile-video' => __DIR__ . '/../view/videos/partials/profile-video.twig',
            'video/partial/related' => __DIR__ . '/../view/videos/partials/related.twig',
            'video/partial/related-video-inner' => __DIR__ . '/../view/videos/partials/related-video-inner.twig',
            'partial/vod' => __DIR__ . '/../view/videos/partials/vod.twig',
            'partial/premier' => __DIR__ . '/../view/videos/partials/premier.twig',
            'partial/cam' => __DIR__ . '/../view/videos/partials/cam.twig',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'paginators' => array(
        'doctrine' => array(
            'Videos\Paginator\VideosPaginator' => \Videos\Entity\Video::class,
            'Videos\Paginator\VodVideosPaginator' => \Videos\Entity\VodVideo::class,
            'Videos\Paginator\PremiereVideoPaginator' => \Videos\Entity\PremiereVideo::class,
            'Videos\Paginator\UserPaginator' => \Application\Entity\User::class,
        ),
    ),
);