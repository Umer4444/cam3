<?php
return array(

    'view_manager' => array(

        'template_map' => array(

            'layout/layout_base' => __DIR__ . '/../view/layout/layout_base.twig',
            'layout/bare' => __DIR__ . '/../view/layout/bare.twig',
            'layout/backend' => __DIR__ . '/../view/layout/backend.twig',
            'layout/frontend' => __DIR__ . '/../view/layout/frontend.twig',
            'layout/user-profile' => __DIR__ . '/../view/layout/user-profile.twig',
            'index/index' => __DIR__ . '/../view/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/zf1-fallback.phtml',
            'zf1/fallback' => __DIR__ . '/../view/error/zf1-fallback.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            'zfc-admin/admin/index' => __DIR__ . '/../view/zfc-admin/admin/index.twig',
            'news' => __DIR__ . '/../view/partials/news.twig',

            // buttons
            'buttons/button' => __DIR__ . '/../view/partials/buttons/button.twig',
            'buttons/context' => __DIR__ . '/../view/partials/buttons/context.twig',
            'buttons/default' => __DIR__ . '/../view/partials/buttons/default.twig',
            'buttons/follow' => __DIR__ . '/../view/partials/buttons/follow.twig',
            'buttons/favorite' => __DIR__ . '/../view/partials/buttons/favorite.twig',
            'buttons/friend' => __DIR__ . '/../view/partials/buttons/friend.twig',
            'buttons/call' => __DIR__ . '/../view/partials/buttons/call.twig',
            'buttons/sms' => __DIR__ . '/../view/partials/buttons/sms.twig',
            'buttons/tip' => __DIR__ . '/../view/partials/buttons/tip.twig',
            'buttons/kick' => __DIR__ . '/../view/partials/buttons/kick.twig',
            'buttons/message' => __DIR__ . '/../view/partials/buttons/message.twig',
            'buttons/offer' => __DIR__ . '/../view/partials/buttons/offer.twig',
            'buttons/appointment' => __DIR__ . '/../view/partials/buttons/appointment.twig',
            'buttons/special' => __DIR__ . '/../view/partials/buttons/special.twig',
            'buttons/profile' => __DIR__ . '/../view/partials/buttons/profile.twig',
            'buttons/watch' => __DIR__ . '/../view/partials/buttons/watch.twig',
            'buttons/private' => __DIR__ . '/../view/partials/buttons/private.twig',
            'buttons/watch-popup' => __DIR__ . '/../view/partials/buttons/watch-popup.twig',
            'buttons/buy' => __DIR__ . '/../view/partials/buttons/buy.twig',
            'buttons/play' => __DIR__ . '/../view/partials/buttons/play.twig',
            'buttons/repost' => __DIR__ . '/../view/partials/buttons/repost.twig',

            'disclaimer/popup' => __DIR__ . '/../view/partials/disclaimer-popup.twig',
            'tags' => __DIR__ . '/../view/partials/tags.twig',
            'hallOfFame' => __DIR__ . '/../view/application/index/hall-of-fame.twig',
            'hallOfFameList' => __DIR__ . '/../view/partials/hall-of-fame-list.twig',
            'popularRooms' => __DIR__ . '/../view/application/index/popular-rooms.twig',
            'popularRoomsList' => __DIR__ . '/../view/partials/popular-rooms-list.twig',
            'contests' => __DIR__ . '/../view/application/index/contests.twig',
            'contestsList' => __DIR__ . '/../view/partials/contests-list.twig',
            'wishlist' => __DIR__ . '/../view/application/index/wishlist.twig',
            'wishlistList' => __DIR__ . '/../view/partials/wishlist-list.twig',
            'wishlist-list-item' => __DIR__ . '/../view/partials/wishlist-list-item.twig',
            'performer-wishlist' => __DIR__ . '/../view/partials/performer-wishlist.twig',
            'requests' => __DIR__ . '/../view/application/index/requests.twig',
            'requestsList' => __DIR__ . '/../view/partials/requests-list.twig',
            'clubsList' => __DIR__ . '/../view/partials/clubs-list.twig',
            'phone' => __DIR__ . '/../view/application//index/phone.twig',
            'pledges' => __DIR__ . '/../view/application/pledge/index.twig',
            'pledge-item' => __DIR__ . '/../view/partials/pledge-item.twig',
            'pledge-subscription' => __DIR__ . '/../view/partials/pledge-subscription.twig',
            'presentations' => __DIR__ . '/../view/application/index/presentations.twig',

            // messages
            'messages/tab-messages' => __DIR__ . '/../view/partials/messages/tab-messages.twig',
            'messages/message-list' => __DIR__ . '/../view/partials/messages/message-list.twig',
            'messages/message-item' => __DIR__ . '/../view/partials/messages/item-message.twig',
            'partials/filter' => __DIR__ . '/../view/partials/filter.twig',
            'partials/timezone' => __DIR__ . '/../view/partials/timezone.twig',
            'partials/presentation' => __DIR__ . '/../view/partials/presentation.twig',
            'partials/presentation-slider' => __DIR__ . '/../view/partials/presentation-slider.twig',
            'requestsItem' => __DIR__ . '/../view/partials/requests-item.twig',

            // payments
            'payment/package' => __DIR__ . '/../view/partials/payment/package.phtml',

            // blocks
            'performer/block' => __DIR__ . '/../view/partials/performer/block.twig',
            'partials/moderate' => __DIR__ . '/../view/partials/moderation/moderate.phtml.twig',
            'partials/videoCategories' => __DIR__ . '/../view/partials/video-categories.twig',
            'partials/nextShow' => __DIR__ . '/../view/partials/next-show.twig',
            'calendar/event' => __DIR__ . '/../view/partials/event.twig',
            'calendar/eventNext' => __DIR__ . '/../view/partials/event-next.twig',
            'interests' => __DIR__ . '/../view/partials/performer/interests.twig',

            // profile
            'performer/profile/left' => __DIR__ . '/../view/partials/performer/profile/left.twig',
            'user/profile/left' => __DIR__ . '/../view/partials/user/profile/left.twig',
            'contest-list-item' => __DIR__ . '/../view/partials/contest-list-item.twig',
            'performer/profile/blocks' => __DIR__ . '/../view/partials/performer/profile/blocks.twig',
            'user/profile/blocks' => __DIR__ . '/../view/partials/user/profile/blocks.twig',
            'performer/wall' => __DIR__ . '/../view/partials/performer/wall/wall.twig',
            'performer/profile/navigation' => __DIR__ . '/../view/partials/performer/profile/navigation.phtml',
            'friends/small-block' => __DIR__ . '/../view/partials/friends-small-block.twig',
            'performer/revenueStats' => __DIR__ . '/../view/application/performer/revenue-stats.twig',
            'wishlist-item' => __DIR__ . '/../view/partials/wishlist-item.twig',

            // blog
            'blog/item' => __DIR__ . '/../view/partials/blog/item.twig',
            'blog/view' => __DIR__ . '/../view/application/blog/view.twig',
            'blog/item-big' => __DIR__ . '/../view/partials/blog/item-big.twig',
            'blog/item-small' => __DIR__ . '/../view/partials/blog/item-small.twig',
            'blog/related' => __DIR__ . '/../view/partials/blog-related.twig',

            // others
            'chat' => __DIR__ . '/../view/partials/chat.twig',
            'chat/chat-sidebar' => __DIR__ . '/../view/partials/chat-sidebar.twig',
            'empty' => __DIR__ . '/../view/partials/empty.twig',

            // plugins for timeline
            'plugins/timeline/transfers' => __DIR__ . '/../view/partials/plugins/timeline/transfers.twig',
            'plugins/timeline/sentTransfers' => __DIR__ . '/../view/partials/plugins/timeline/transfers.twig',
            'plugins/timeline/blogs' => __DIR__ . '/../view/partials/plugins/timeline/blogs.twig',
            'timeline-activity' => __DIR__ . '/../view/partials/timeline-activity.twig',
            'timeline-activity-filter' => __DIR__ . '/../view/partials/timeline-activity-filter.twig',

            // because user controller is now extended
            'application/friends/list' => __DIR__ . '/../view/friends/list.twig',
            'application/user/login' => __DIR__ . '/../view/zfc-user/user/login.twig',
            'application/user/index' => __DIR__ . '/../view/zfc-user/user/index.phtml',
            'application/user/register' => __DIR__ . '/../view/zfc-user/user/register.phtml',
            'application/user/changeemail' => __DIR__ . '/../view/zfc-user/user/changeemail.phtml',
            'application/user/changepassword' => __DIR__ . '/../view/zfc-user/user/changepassword.phtml',
            'user-account/user-account/index' => __DIR__. '/../view/zfc-user/user/index.phtml',
            'event_calendar' => __DIR__ . '/../view/partials/event_calendar.twig',
            'ex/index' => __DIR__ . '/../view/ex/index.twig',
        ),

        'template_path_stack' => array(
            'zf-table' => __DIR__ . '/../view',
            __DIR__ . '/../view',
        ),

    ),

);