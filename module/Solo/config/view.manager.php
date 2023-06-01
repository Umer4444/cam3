<?php

$theme = 'anakaliyah.com';

return array(
    'view_manager' => array(
        'template_map' => array(

            'layout/frontend' => __DIR__ . '/../view/layout/solo.twig',
            'layout/profile' => __DIR__ . '/../view/layout/solo.twig', // there is no profilel on the solo

            'index.twig' => __DIR__ . '/../view/solo/index/index.twig',
            'my_friends' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/my_friends.twig',
            'friends' => __DIR__ . '/../view/solo/index/friends.twig',
            'socialMedia' => __DIR__ . '/../view/partial/social-media.twig',
            'message_filter' => __DIR__ . '/../view/partial/message-filter.phtml',
            'message_list' => __DIR__ . '/../view/partial/message-list.phtml',
            'item_message' => __DIR__ . '/../view/partial/item-message.phtml',
            'message_paginator' => __DIR__ . '/../view/partial/message-paginator.phtml',
            'latest_video' => 'themes/'.$theme.'/views/scripts/partials/latest_video.twig',
            'like_buttons' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/like-buttons.twig',
            'stars' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/stars.twig',
            'pledge-solo-homepage' => __DIR__ . '/../view/partial/pledge-solo-subscription.twig',

            'solo/twitter' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/twitter.phtml',
            'solo/instagram' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/instagram.twig',
            'solo/facebook' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/facebook.twig',
            'solo/myspace' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/myspace.twig',
            'solo/google' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/google.twig',
            'solo/xbiz' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/xbiz.twig',
            'solo/fubar' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/fubar.twig',
            'solo/vine' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/vine.twig',
            'solo/snapchat' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/snapchat.twig',
            'solo/kik' => __DIR__ . '/../../../themes/'.$theme.'/views/scripts/partials/social/kik.twig',

            'application/blog/index' => __DIR__ . '/../view/solo/blog/index.twig',
            'blog/item' => __DIR__ . '/../view/partial/blog/item.twig',
            'blog-solo/item-big' => __DIR__ . '/../view/partial/blog/item-big.twig',
            'blog-solo/item-small' => __DIR__ . '/../view/partial/blog/item-small.twig',
            'application/user/login' => __DIR__ . '/../view/solo-user/user/login.twig',
            'application/user/index' => __DIR__ . '/../view/solo-user/user/index.phtml',
            'application/user/register' => __DIR__ . '/../view/solo-user/user/register.twig',
            'application/user/changeemail' => __DIR__ . '/../view/solo-user/user/changeemail.phtml',
            'application/user/changepassword' => __DIR__ . '/../view/solo-user/user/changepassword.phtml',

            'solo/solo-user/edit' => __DIR__ . '/../view/solo-user/user/edit.phtml',
            'user-profile/user-profile/index' => __DIR__ . '/../view/solo-user/user/profile.phtml',
            'solo/solo-user/edit-profile' => __DIR__ . '/../view/solo-user/user/editprofile.phtml',
            'solo/index/messages' => __DIR__ . '/../view/solo/index/messages.phtml',
            'faq' => __DIR__ . '/../view/solo/index/faq-static-pages.phtml',
            'about' => __DIR__ . '/../view/solo/index/about.twig',
            'contact' => __DIR__ . '/../view/solo/index/contact.twig',
            'solo/buttons' => __DIR__ . '/../view/partial/profile-buttons.twig',
            'solo/partial/video' => __DIR__ . '/../view/partial/video.twig',
            'video-item' => __DIR__ . '/../view/partial/video-item.twig',
            'album/solo-album-item' => __DIR__ . '/../view/partial/album-solo-item.twig',

        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);