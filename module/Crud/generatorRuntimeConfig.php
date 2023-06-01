<?php
return array (
  'module' =>
  array (
    'generatedConfigPath' => getcwd().'/module/Crud/config/module.config.php',
  ),
  'inputFilter' =>
  array (
    'address' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseAddressFilter',
      'filter' => '\\Crud\\Filter\\AddressFilter',
    ),
    'albums' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseAlbumsFilter',
      'filter' => '\\Crud\\Filter\\AlbumsFilter',
    ),
    'announcements' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseAnnouncementsFilter',
      'filter' => '\\Crud\\Filter\\AnnouncementsFilter',
    ),
    'ansi_uom' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseAnsiUomFilter',
      'filter' => '\\Crud\\Filter\\AnsiUomFilter',
    ),
    'autoresponders' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseAutorespondersFilter',
      'filter' => '\\Crud\\Filter\\AutorespondersFilter',
    ),
    'autoresponders_train' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseAutorespondersTrainFilter',
      'filter' => '\\Crud\\Filter\\AutorespondersTrainFilter',
    ),
    'bad_words' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseBadWordsFilter',
      'filter' => '\\Crud\\Filter\\BadWordsFilter',
    ),
    'banners' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseBannersFilter',
      'filter' => '\\Crud\\Filter\\BannersFilter',
    ),
    'blog_posts' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseBlogPostsFilter',
      'filter' => '\\Crud\\Filter\\BlogPostsFilter',
    ),
    'call_log' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCallLogFilter',
      'filter' => '\\Crud\\Filter\\CallLogFilter',
    ),
    'cart' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCartFilter',
      'filter' => '\\Crud\\Filter\\CartFilter',
    ),
    'cart_item' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCartItemFilter',
      'filter' => '\\Crud\\Filter\\CartItemFilter',
    ),
    'catalog_availability' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogAvailabilityFilter',
      'filter' => '\\Crud\\Filter\\CatalogAvailabilityFilter',
    ),
    'catalog_builder_product' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogBuilderProductFilter',
      'filter' => '\\Crud\\Filter\\CatalogBuilderProductFilter',
    ),
    'catalog_category' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogCategoryFilter',
      'filter' => '\\Crud\\Filter\\CatalogCategoryFilter',
    ),
    'catalog_category_product' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogCategoryProductFilter',
      'filter' => '\\Crud\\Filter\\CatalogCategoryProductFilter',
    ),
    'catalog_category_website' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogCategoryWebsiteFilter',
      'filter' => '\\Crud\\Filter\\CatalogCategoryWebsiteFilter',
    ),
    'catalog_choice' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogChoiceFilter',
      'filter' => '\\Crud\\Filter\\CatalogChoiceFilter',
    ),
    'catalog_choice_option' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogChoiceOptionFilter',
      'filter' => '\\Crud\\Filter\\CatalogChoiceOptionFilter',
    ),
    'catalog_option' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogOptionFilter',
      'filter' => '\\Crud\\Filter\\CatalogOptionFilter',
    ),
    'catalog_option_image' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogOptionImageFilter',
      'filter' => '\\Crud\\Filter\\CatalogOptionImageFilter',
    ),
    'catalog_product' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductFilter',
    ),
    'catalog_product_document' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductDocumentFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductDocumentFilter',
    ),
    'catalog_product_feature' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductFeatureFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductFeatureFilter',
    ),
    'catalog_product_image' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductImageFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductImageFilter',
    ),
    'catalog_product_option' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductOptionFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductOptionFilter',
    ),
    'catalog_product_spec' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductSpecFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductSpecFilter',
    ),
    'catalog_product_type' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductTypeFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductTypeFilter',
    ),
    'catalog_product_uom' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCatalogProductUomFilter',
      'filter' => '\\Crud\\Filter\\CatalogProductUomFilter',
    ),
    'categories' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCategoriesFilter',
      'filter' => '\\Crud\\Filter\\CategoriesFilter',
    ),
    'config' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseConfigFilter',
      'filter' => '\\Crud\\Filter\\ConfigFilter',
    ),
    'contact' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactFilter',
      'filter' => '\\Crud\\Filter\\ContactFilter',
    ),
    'contact_addresses' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactAddressesFilter',
      'filter' => '\\Crud\\Filter\\ContactAddressesFilter',
    ),
    'contact_companies' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactCompaniesFilter',
      'filter' => '\\Crud\\Filter\\ContactCompaniesFilter',
    ),
    'contact_company' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactCompanyFilter',
      'filter' => '\\Crud\\Filter\\ContactCompanyFilter',
    ),
    'contact_email' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactEmailFilter',
      'filter' => '\\Crud\\Filter\\ContactEmailFilter',
    ),
    'contact_phone' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactPhoneFilter',
      'filter' => '\\Crud\\Filter\\ContactPhoneFilter',
    ),
    'contact_url' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseContactUrlFilter',
      'filter' => '\\Crud\\Filter\\ContactUrlFilter',
    ),
    'countries' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCountriesFilter',
      'filter' => '\\Crud\\Filter\\CountriesFilter',
    ),
    'country' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCountryFilter',
      'filter' => '\\Crud\\Filter\\CountryFilter',
    ),
    'country_city' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCountryCityFilter',
      'filter' => '\\Crud\\Filter\\CountryCityFilter',
    ),
    'country_codes' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCountryCodesFilter',
      'filter' => '\\Crud\\Filter\\CountryCodesFilter',
    ),
    'country_province' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseCountryProvinceFilter',
      'filter' => '\\Crud\\Filter\\CountryProvinceFilter',
    ),
    'earning_stats' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseEarningStatsFilter',
      'filter' => '\\Crud\\Filter\\EarningStatsFilter',
    ),
    'ext_log_entries' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseExtLogEntriesFilter',
      'filter' => '\\Crud\\Filter\\ExtLogEntriesFilter',
    ),
    'followers' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseFollowersFilter',
      'filter' => '\\Crud\\Filter\\FollowersFilter',
    ),
    'friends' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseFriendsFilter',
      'filter' => '\\Crud\\Filter\\FriendsFilter',
    ),
    'funders' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseFundersFilter',
      'filter' => '\\Crud\\Filter\\FundersFilter',
    ),
    'hidden_models' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseHiddenModelsFilter',
      'filter' => '\\Crud\\Filter\\HiddenModelsFilter',
    ),
    'info' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseInfoFilter',
      'filter' => '\\Crud\\Filter\\InfoFilter',
    ),
    'info_modelinfo' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseInfoModelinfoFilter',
      'filter' => '\\Crud\\Filter\\InfoModelinfoFilter',
    ),
    'interactions' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseInteractionsFilter',
      'filter' => '\\Crud\\Filter\\InteractionsFilter',
    ),
    'logo' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseLogoFilter',
      'filter' => '\\Crud\\Filter\\LogoFilter',
    ),
    'managers_studios' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseManagersStudiosFilter',
      'filter' => '\\Crud\\Filter\\ManagersStudiosFilter',
    ),
    'messages' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseMessagesFilter',
      'filter' => '\\Crud\\Filter\\MessagesFilter',
    ),
    'migrations' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseMigrationsFilter',
      'filter' => '\\Crud\\Filter\\MigrationsFilter',
    ),
    'model_access' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelAccessFilter',
      'filter' => '\\Crud\\Filter\\ModelAccessFilter',
    ),
    'model_actions' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelActionsFilter',
      'filter' => '\\Crud\\Filter\\ModelActionsFilter',
    ),
    'model_block_access' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelBlockAccessFilter',
      'filter' => '\\Crud\\Filter\\ModelBlockAccessFilter',
    ),
    'model_info' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelInfoFilter',
      'filter' => '\\Crud\\Filter\\ModelInfoFilter',
    ),
    'model_moderator' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelModeratorFilter',
      'filter' => '\\Crud\\Filter\\ModelModeratorFilter',
    ),
    'model_notes' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelNotesFilter',
      'filter' => '\\Crud\\Filter\\ModelNotesFilter',
    ),
    'model_quotes' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelQuotesFilter',
      'filter' => '\\Crud\\Filter\\ModelQuotesFilter',
    ),
    'model_rates' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelRatesFilter',
      'filter' => '\\Crud\\Filter\\ModelRatesFilter',
    ),
    'model_rates_pending' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelRatesPendingFilter',
      'filter' => '\\Crud\\Filter\\ModelRatesPendingFilter',
    ),
    'model_requests' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelRequestsFilter',
      'filter' => '\\Crud\\Filter\\ModelRequestsFilter',
    ),
    'model_schedule' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelScheduleFilter',
      'filter' => '\\Crud\\Filter\\ModelScheduleFilter',
    ),
    'model_to_categories' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelToCategoriesFilter',
      'filter' => '\\Crud\\Filter\\ModelToCategoriesFilter',
    ),
    'model_user_access' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelUserAccessFilter',
      'filter' => '\\Crud\\Filter\\ModelUserAccessFilter',
    ),
    'model_user_notes' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelUserNotesFilter',
      'filter' => '\\Crud\\Filter\\ModelUserNotesFilter',
    ),
    'model_wall' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModelWallFilter',
      'filter' => '\\Crud\\Filter\\ModelWallFilter',
    ),
    'moderator' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModeratorFilter',
      'filter' => '\\Crud\\Filter\\ModeratorFilter',
    ),
    'moderator_notes' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseModeratorNotesFilter',
      'filter' => '\\Crud\\Filter\\ModeratorNotesFilter',
    ),
    'newsletter' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseNewsletterFilter',
      'filter' => '\\Crud\\Filter\\NewsletterFilter',
    ),
    'newsletter_websites' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseNewsletterWebsitesFilter',
      'filter' => '\\Crud\\Filter\\NewsletterWebsitesFilter',
    ),
    'notifications' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseNotificationsFilter',
      'filter' => '\\Crud\\Filter\\NotificationsFilter',
    ),
    'order' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseOrderFilter',
      'filter' => '\\Crud\\Filter\\OrderFilter',
    ),
    'order_address' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseOrderAddressFilter',
      'filter' => '\\Crud\\Filter\\OrderAddressFilter',
    ),
    'order_flag_linker' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseOrderFlagLinkerFilter',
      'filter' => '\\Crud\\Filter\\OrderFlagLinkerFilter',
    ),
    'order_flags' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseOrderFlagsFilter',
      'filter' => '\\Crud\\Filter\\OrderFlagsFilter',
    ),
    'order_line' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseOrderLineFilter',
      'filter' => '\\Crud\\Filter\\OrderLineFilter',
    ),
    'package' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePackageFilter',
      'filter' => '\\Crud\\Filter\\PackageFilter',
    ),
    'payment' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePaymentFilter',
      'filter' => '\\Crud\\Filter\\PaymentFilter',
    ),
    'payment_method' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePaymentMethodFilter',
      'filter' => '\\Crud\\Filter\\PaymentMethodFilter',
    ),
    'payment_session' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePaymentSessionFilter',
      'filter' => '\\Crud\\Filter\\PaymentSessionFilter',
    ),
    'payment_token' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePaymentTokenFilter',
      'filter' => '\\Crud\\Filter\\PaymentTokenFilter',
    ),
    'payments_info' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePaymentsInfoFilter',
      'filter' => '\\Crud\\Filter\\PaymentsInfoFilter',
    ),
    'permissions' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePermissionsFilter',
      'filter' => '\\Crud\\Filter\\PermissionsFilter',
    ),
    'photos' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePhotosFilter',
      'filter' => '\\Crud\\Filter\\PhotosFilter',
    ),
    'pledge' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePledgeFilter',
      'filter' => '\\Crud\\Filter\\PledgeFilter',
    ),
    'pledge_perk' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePledgePerkFilter',
      'filter' => '\\Crud\\Filter\\PledgePerkFilter',
    ),
    'pledge_update' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePledgeUpdateFilter',
      'filter' => '\\Crud\\Filter\\PledgeUpdateFilter',
    ),
    'purchased_content' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BasePurchasedContentFilter',
      'filter' => '\\Crud\\Filter\\PurchasedContentFilter',
    ),
    'rates' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseRatesFilter',
      'filter' => '\\Crud\\Filter\\RatesFilter',
    ),
    'rates_limits' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseRatesLimitsFilter',
      'filter' => '\\Crud\\Filter\\RatesLimitsFilter',
    ),
    'rb_comments' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseRbCommentsFilter',
      'filter' => '\\Crud\\Filter\\RbCommentsFilter',
    ),
    'resource_values' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseResourceValuesFilter',
      'filter' => '\\Crud\\Filter\\ResourceValuesFilter',
    ),
    'resources' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseResourcesFilter',
      'filter' => '\\Crud\\Filter\\ResourcesFilter',
    ),
    'reviews' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseReviewsFilter',
      'filter' => '\\Crud\\Filter\\ReviewsFilter',
    ),
    'rules' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseRulesFilter',
      'filter' => '\\Crud\\Filter\\RulesFilter',
    ),
    'scheduled_media' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseScheduledMediaFilter',
      'filter' => '\\Crud\\Filter\\ScheduledMediaFilter',
    ),
    'shows' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseShowsFilter',
      'filter' => '\\Crud\\Filter\\ShowsFilter',
    ),
    'sounds' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseSoundsFilter',
      'filter' => '\\Crud\\Filter\\SoundsFilter',
    ),
    'special_requests' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseSpecialRequestsFilter',
      'filter' => '\\Crud\\Filter\\SpecialRequestsFilter',
    ),
    'static_pages' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseStaticPagesFilter',
      'filter' => '\\Crud\\Filter\\StaticPagesFilter',
    ),
    'studios' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseStudiosFilter',
      'filter' => '\\Crud\\Filter\\StudiosFilter',
    ),
    'templates' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseTemplatesFilter',
      'filter' => '\\Crud\\Filter\\TemplatesFilter',
    ),
    'timezones' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseTimezonesFilter',
      'filter' => '\\Crud\\Filter\\TimezonesFilter',
    ),
    'transfer_wall' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseTransferWallFilter',
      'filter' => '\\Crud\\Filter\\TransferWallFilter',
    ),
    'user' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserFilter',
      'filter' => '\\Crud\\Filter\\UserFilter',
    ),
    'user_access' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserAccessFilter',
      'filter' => '\\Crud\\Filter\\UserAccessFilter',
    ),
    'user_addresses' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserAddressesFilter',
      'filter' => '\\Crud\\Filter\\UserAddressesFilter',
    ),
    'user_favorites' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserFavoritesFilter',
      'filter' => '\\Crud\\Filter\\UserFavoritesFilter',
    ),
    'user_newsletter' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserNewsletterFilter',
      'filter' => '\\Crud\\Filter\\UserNewsletterFilter',
    ),
    'user_notifications' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserNotificationsFilter',
      'filter' => '\\Crud\\Filter\\UserNotificationsFilter',
    ),
    'user_notifications_mail' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserNotificationsMailFilter',
      'filter' => '\\Crud\\Filter\\UserNotificationsMailFilter',
    ),
    'user_notifications_type' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserNotificationsTypeFilter',
      'filter' => '\\Crud\\Filter\\UserNotificationsTypeFilter',
    ),
    'user_paid_videos' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserPaidVideosFilter',
      'filter' => '\\Crud\\Filter\\UserPaidVideosFilter',
    ),
    'user_resource' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserResourceFilter',
      'filter' => '\\Crud\\Filter\\UserResourceFilter',
    ),
    'user_resource_value' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserResourceValueFilter',
      'filter' => '\\Crud\\Filter\\UserResourceValueFilter',
    ),
    'user_role' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserRoleFilter',
      'filter' => '\\Crud\\Filter\\UserRoleFilter',
    ),
    'user_role_linker' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserRoleLinkerFilter',
      'filter' => '\\Crud\\Filter\\UserRoleLinkerFilter',
    ),
    'user_settings' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserSettingsFilter',
      'filter' => '\\Crud\\Filter\\UserSettingsFilter',
    ),
    'user_studios' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserStudiosFilter',
      'filter' => '\\Crud\\Filter\\UserStudiosFilter',
    ),
    'user_warnings' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseUserWarningsFilter',
      'filter' => '\\Crud\\Filter\\UserWarningsFilter',
    ),
    'video' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseVideoFilter',
      'filter' => '\\Crud\\Filter\\VideoFilter',
    ),
    'webcam_access' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseWebcamAccessFilter',
      'filter' => '\\Crud\\Filter\\WebcamAccessFilter',
    ),
    'webchat_history' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseWebchatHistoryFilter',
      'filter' => '\\Crud\\Filter\\WebchatHistoryFilter',
    ),
    'webchat_lines' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseWebchatLinesFilter',
      'filter' => '\\Crud\\Filter\\WebchatLinesFilter',
    ),
    'webchat_sessions' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseWebchatSessionsFilter',
      'filter' => '\\Crud\\Filter\\WebchatSessionsFilter',
    ),
    'webchat_users' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseWebchatUsersFilter',
      'filter' => '\\Crud\\Filter\\WebchatUsersFilter',
    ),
    'website' =>
    array (
      'baseFilter' => '\\Crud\\Filter\\BaseFilter\\BaseWebsiteFilter',
      'filter' => '\\Crud\\Filter\\WebsiteFilter',
    ),
  ),
  'model' =>
  array (
    0 => false,
  ),
  'form' =>
  array (
    'address' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseAddressGrid',
      'grid' => '\\Crud\\Grid\\AddressGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseAddressForm',
      'form' => '\\Crud\\Form\\AddressForm',
    ),
    'albums' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseAlbumsGrid',
      'grid' => '\\Crud\\Grid\\AlbumsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseAlbumsForm',
      'form' => '\\Crud\\Form\\AlbumsForm',
    ),
    'announcements' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseAnnouncementsGrid',
      'grid' => '\\Crud\\Grid\\AnnouncementsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseAnnouncementsForm',
      'form' => '\\Crud\\Form\\AnnouncementsForm',
    ),
    'ansi_uom' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseAnsiUomGrid',
      'grid' => '\\Crud\\Grid\\AnsiUomGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseAnsiUomForm',
      'form' => '\\Crud\\Form\\AnsiUomForm',
    ),
    'autoresponders' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseAutorespondersGrid',
      'grid' => '\\Crud\\Grid\\AutorespondersGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseAutorespondersForm',
      'form' => '\\Crud\\Form\\AutorespondersForm',
    ),
    'autoresponders_train' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseAutorespondersTrainGrid',
      'grid' => '\\Crud\\Grid\\AutorespondersTrainGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseAutorespondersTrainForm',
      'form' => '\\Crud\\Form\\AutorespondersTrainForm',
    ),
    'bad_words' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseBadWordsGrid',
      'grid' => '\\Crud\\Grid\\BadWordsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseBadWordsForm',
      'form' => '\\Crud\\Form\\BadWordsForm',
    ),
    'banners' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseBannersGrid',
      'grid' => '\\Crud\\Grid\\BannersGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseBannersForm',
      'form' => '\\Crud\\Form\\BannersForm',
    ),
    'blog_access' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseBlogAccessGrid',
      'grid' => '\\Crud\\Grid\\BlogAccessGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseBlogAccessForm',
      'form' => '\\Crud\\Form\\BlogAccessForm',
    ),
    'blog_categories' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseBlogCategoriesGrid',
      'grid' => '\\Crud\\Grid\\BlogCategoriesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseBlogCategoriesForm',
      'form' => '\\Crud\\Form\\BlogCategoriesForm',
    ),
    'blog_posts' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseBlogPostsGrid',
      'grid' => '\\Crud\\Grid\\BlogPostsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseBlogPostsForm',
      'form' => '\\Crud\\Form\\BlogPostsForm',
    ),
    'blog_purchase' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseBlogPurchaseGrid',
      'grid' => '\\Crud\\Grid\\BlogPurchaseGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseBlogPurchaseForm',
      'form' => '\\Crud\\Form\\BlogPurchaseForm',
    ),
    'call_log' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCallLogGrid',
      'grid' => '\\Crud\\Grid\\CallLogGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCallLogForm',
      'form' => '\\Crud\\Form\\CallLogForm',
    ),
    'cart' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCartGrid',
      'grid' => '\\Crud\\Grid\\CartGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCartForm',
      'form' => '\\Crud\\Form\\CartForm',
    ),
    'cart_item' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCartItemGrid',
      'grid' => '\\Crud\\Grid\\CartItemGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCartItemForm',
      'form' => '\\Crud\\Form\\CartItemForm',
    ),
    'catalog_availability' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogAvailabilityGrid',
      'grid' => '\\Crud\\Grid\\CatalogAvailabilityGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogAvailabilityForm',
      'form' => '\\Crud\\Form\\CatalogAvailabilityForm',
    ),
    'catalog_builder_product' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogBuilderProductGrid',
      'grid' => '\\Crud\\Grid\\CatalogBuilderProductGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogBuilderProductForm',
      'form' => '\\Crud\\Form\\CatalogBuilderProductForm',
    ),
    'catalog_category' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogCategoryGrid',
      'grid' => '\\Crud\\Grid\\CatalogCategoryGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogCategoryForm',
      'form' => '\\Crud\\Form\\CatalogCategoryForm',
    ),
    'catalog_category_product' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogCategoryProductGrid',
      'grid' => '\\Crud\\Grid\\CatalogCategoryProductGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogCategoryProductForm',
      'form' => '\\Crud\\Form\\CatalogCategoryProductForm',
    ),
    'catalog_category_website' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogCategoryWebsiteGrid',
      'grid' => '\\Crud\\Grid\\CatalogCategoryWebsiteGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogCategoryWebsiteForm',
      'form' => '\\Crud\\Form\\CatalogCategoryWebsiteForm',
    ),
    'catalog_choice' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogChoiceGrid',
      'grid' => '\\Crud\\Grid\\CatalogChoiceGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogChoiceForm',
      'form' => '\\Crud\\Form\\CatalogChoiceForm',
    ),
    'catalog_choice_option' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogChoiceOptionGrid',
      'grid' => '\\Crud\\Grid\\CatalogChoiceOptionGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogChoiceOptionForm',
      'form' => '\\Crud\\Form\\CatalogChoiceOptionForm',
    ),
    'catalog_option' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogOptionGrid',
      'grid' => '\\Crud\\Grid\\CatalogOptionGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogOptionForm',
      'form' => '\\Crud\\Form\\CatalogOptionForm',
    ),
    'catalog_option_image' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogOptionImageGrid',
      'grid' => '\\Crud\\Grid\\CatalogOptionImageGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogOptionImageForm',
      'form' => '\\Crud\\Form\\CatalogOptionImageForm',
    ),
    'catalog_product' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductForm',
      'form' => '\\Crud\\Form\\CatalogProductForm',
    ),
    'catalog_product_document' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductDocumentGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductDocumentGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductDocumentForm',
      'form' => '\\Crud\\Form\\CatalogProductDocumentForm',
    ),
    'catalog_product_feature' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductFeatureGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductFeatureGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductFeatureForm',
      'form' => '\\Crud\\Form\\CatalogProductFeatureForm',
    ),
    'catalog_product_image' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductImageGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductImageGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductImageForm',
      'form' => '\\Crud\\Form\\CatalogProductImageForm',
    ),
    'catalog_product_option' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductOptionGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductOptionGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductOptionForm',
      'form' => '\\Crud\\Form\\CatalogProductOptionForm',
    ),
    'catalog_product_spec' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductSpecGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductSpecGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductSpecForm',
      'form' => '\\Crud\\Form\\CatalogProductSpecForm',
    ),
    'catalog_product_type' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductTypeGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductTypeGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductTypeForm',
      'form' => '\\Crud\\Form\\CatalogProductTypeForm',
    ),
    'catalog_product_uom' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCatalogProductUomGrid',
      'grid' => '\\Crud\\Grid\\CatalogProductUomGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCatalogProductUomForm',
      'form' => '\\Crud\\Form\\CatalogProductUomForm',
    ),
    'categories' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCategoriesGrid',
      'grid' => '\\Crud\\Grid\\CategoriesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCategoriesForm',
      'form' => '\\Crud\\Form\\CategoriesForm',
    ),
    'chips' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseChipsGrid',
      'grid' => '\\Crud\\Grid\\ChipsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseChipsForm',
      'form' => '\\Crud\\Form\\ChipsForm',
    ),
    'chips_packages' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseChipsPackagesGrid',
      'grid' => '\\Crud\\Grid\\ChipsPackagesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseChipsPackagesForm',
      'form' => '\\Crud\\Form\\ChipsPackagesForm',
    ),
    'config' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseConfigGrid',
      'grid' => '\\Crud\\Grid\\ConfigGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseConfigForm',
      'form' => '\\Crud\\Form\\ConfigForm',
    ),
    'configadminvalues' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseConfigadminvaluesGrid',
      'grid' => '\\Crud\\Grid\\ConfigadminvaluesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseConfigadminvaluesForm',
      'form' => '\\Crud\\Form\\ConfigadminvaluesForm',
    ),
    'contact' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactGrid',
      'grid' => '\\Crud\\Grid\\ContactGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactForm',
      'form' => '\\Crud\\Form\\ContactForm',
    ),
    'contact_addresses' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactAddressesGrid',
      'grid' => '\\Crud\\Grid\\ContactAddressesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactAddressesForm',
      'form' => '\\Crud\\Form\\ContactAddressesForm',
    ),
    'contact_companies' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactCompaniesGrid',
      'grid' => '\\Crud\\Grid\\ContactCompaniesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactCompaniesForm',
      'form' => '\\Crud\\Form\\ContactCompaniesForm',
    ),
    'contact_company' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactCompanyGrid',
      'grid' => '\\Crud\\Grid\\ContactCompanyGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactCompanyForm',
      'form' => '\\Crud\\Form\\ContactCompanyForm',
    ),
    'contact_email' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactEmailGrid',
      'grid' => '\\Crud\\Grid\\ContactEmailGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactEmailForm',
      'form' => '\\Crud\\Form\\ContactEmailForm',
    ),
    'contact_phone' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactPhoneGrid',
      'grid' => '\\Crud\\Grid\\ContactPhoneGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactPhoneForm',
      'form' => '\\Crud\\Form\\ContactPhoneForm',
    ),
    'contact_url' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseContactUrlGrid',
      'grid' => '\\Crud\\Grid\\ContactUrlGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseContactUrlForm',
      'form' => '\\Crud\\Form\\ContactUrlForm',
    ),
    'countries' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCountriesGrid',
      'grid' => '\\Crud\\Grid\\CountriesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCountriesForm',
      'form' => '\\Crud\\Form\\CountriesForm',
    ),
    'country_city' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCountryCityGrid',
      'grid' => '\\Crud\\Grid\\CountryCityGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCountryCityForm',
      'form' => '\\Crud\\Form\\CountryCityForm',
    ),
    'country_codes' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCountryCodesGrid',
      'grid' => '\\Crud\\Grid\\CountryCodesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCountryCodesForm',
      'form' => '\\Crud\\Form\\CountryCodesForm',
    ),
    'country_province' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCountryProvinceGrid',
      'grid' => '\\Crud\\Grid\\CountryProvinceGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCountryProvinceForm',
      'form' => '\\Crud\\Form\\CountryProvinceForm',
    ),
    'customer' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCustomerGrid',
      'grid' => '\\Crud\\Grid\\CustomerGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCustomerForm',
      'form' => '\\Crud\\Form\\CustomerForm',
    ),
    'earning_stats' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseEarningStatsGrid',
      'grid' => '\\Crud\\Grid\\EarningStatsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseEarningStatsForm',
      'form' => '\\Crud\\Form\\EarningStatsForm',
    ),
    'featured_product' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseFeaturedProductGrid',
      'grid' => '\\Crud\\Grid\\FeaturedProductGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseFeaturedProductForm',
      'form' => '\\Crud\\Form\\FeaturedProductForm',
    ),
    'followers' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseFollowersGrid',
      'grid' => '\\Crud\\Grid\\FollowersGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseFollowersForm',
      'form' => '\\Crud\\Form\\FollowersForm',
    ),
    'friends' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseFriendsGrid',
      'grid' => '\\Crud\\Grid\\FriendsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseFriendsForm',
      'form' => '\\Crud\\Form\\FriendsForm',
    ),
    'hidden_models' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseHiddenModelsGrid',
      'grid' => '\\Crud\\Grid\\HiddenModelsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseHiddenModelsForm',
      'form' => '\\Crud\\Form\\HiddenModelsForm',
    ),
    'images_deprecated' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseImagesDeprecatedGrid',
      'grid' => '\\Crud\\Grid\\ImagesDeprecatedGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseImagesDeprecatedForm',
      'form' => '\\Crud\\Form\\ImagesDeprecatedForm',
    ),
    'info' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseInfoGrid',
      'grid' => '\\Crud\\Grid\\InfoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseInfoForm',
      'form' => '\\Crud\\Form\\InfoForm',
    ),
    'info_modelinfo' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseInfoModelinfoGrid',
      'grid' => '\\Crud\\Grid\\InfoModelinfoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseInfoModelinfoForm',
      'form' => '\\Crud\\Form\\InfoModelinfoForm',
    ),
    'managers_studios' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseManagersStudiosGrid',
      'grid' => '\\Crud\\Grid\\ManagersStudiosGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseManagersStudiosForm',
      'form' => '\\Crud\\Form\\ManagersStudiosForm',
    ),
    'messages' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMessagesGrid',
      'grid' => '\\Crud\\Grid\\MessagesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMessagesForm',
      'form' => '\\Crud\\Form\\MessagesForm',
    ),
    'migrations' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMigrationsGrid',
      'grid' => '\\Crud\\Grid\\MigrationsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMigrationsForm',
      'form' => '\\Crud\\Form\\MigrationsForm',
    ),
    'model' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelGrid',
      'grid' => '\\Crud\\Grid\\ModelGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelForm',
      'form' => '\\Crud\\Form\\ModelForm',
    ),
    'model_access' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelAccessGrid',
      'grid' => '\\Crud\\Grid\\ModelAccessGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelAccessForm',
      'form' => '\\Crud\\Form\\ModelAccessForm',
    ),
    'model_actions' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelActionsGrid',
      'grid' => '\\Crud\\Grid\\ModelActionsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelActionsForm',
      'form' => '\\Crud\\Form\\ModelActionsForm',
    ),
    'model_block_access' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelBlockAccessGrid',
      'grid' => '\\Crud\\Grid\\ModelBlockAccessGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelBlockAccessForm',
      'form' => '\\Crud\\Form\\ModelBlockAccessForm',
    ),
    'model_info' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelInfoGrid',
      'grid' => '\\Crud\\Grid\\ModelInfoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelInfoForm',
      'form' => '\\Crud\\Form\\ModelInfoForm',
    ),
    'model_moderator' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelModeratorGrid',
      'grid' => '\\Crud\\Grid\\ModelModeratorGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelModeratorForm',
      'form' => '\\Crud\\Form\\ModelModeratorForm',
    ),
    'model_notes' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelNotesGrid',
      'grid' => '\\Crud\\Grid\\ModelNotesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelNotesForm',
      'form' => '\\Crud\\Form\\ModelNotesForm',
    ),
    'model_quotes' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelQuotesGrid',
      'grid' => '\\Crud\\Grid\\ModelQuotesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelQuotesForm',
      'form' => '\\Crud\\Form\\ModelQuotesForm',
    ),
    'model_rates' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelRatesGrid',
      'grid' => '\\Crud\\Grid\\ModelRatesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelRatesForm',
      'form' => '\\Crud\\Form\\ModelRatesForm',
    ),
    'model_rates_pending' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelRatesPendingGrid',
      'grid' => '\\Crud\\Grid\\ModelRatesPendingGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelRatesPendingForm',
      'form' => '\\Crud\\Form\\ModelRatesPendingForm',
    ),
    'model_requests' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelRequestsGrid',
      'grid' => '\\Crud\\Grid\\ModelRequestsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelRequestsForm',
      'form' => '\\Crud\\Form\\ModelRequestsForm',
    ),
    'model_schedule' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelScheduleGrid',
      'grid' => '\\Crud\\Grid\\ModelScheduleGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelScheduleForm',
      'form' => '\\Crud\\Form\\ModelScheduleForm',
    ),
    'model_to_categories' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelToCategoriesGrid',
      'grid' => '\\Crud\\Grid\\ModelToCategoriesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelToCategoriesForm',
      'form' => '\\Crud\\Form\\ModelToCategoriesForm',
    ),
    'model_user_access' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelUserAccessGrid',
      'grid' => '\\Crud\\Grid\\ModelUserAccessGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelUserAccessForm',
      'form' => '\\Crud\\Form\\ModelUserAccessForm',
    ),
    'model_user_notes' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelUserNotesGrid',
      'grid' => '\\Crud\\Grid\\ModelUserNotesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelUserNotesForm',
      'form' => '\\Crud\\Form\\ModelUserNotesForm',
    ),
    'model_wall' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModelWallGrid',
      'grid' => '\\Crud\\Grid\\ModelWallGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModelWallForm',
      'form' => '\\Crud\\Form\\ModelWallForm',
    ),
    'moderator' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModeratorGrid',
      'grid' => '\\Crud\\Grid\\ModeratorGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModeratorForm',
      'form' => '\\Crud\\Form\\ModeratorForm',
    ),
    'moderator_notes' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseModeratorNotesGrid',
      'grid' => '\\Crud\\Grid\\ModeratorNotesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseModeratorNotesForm',
      'form' => '\\Crud\\Form\\ModeratorNotesForm',
    ),
    'newsletter' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseNewsletterGrid',
      'grid' => '\\Crud\\Grid\\NewsletterGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseNewsletterForm',
      'form' => '\\Crud\\Form\\NewsletterForm',
    ),
    'newsletter_websites' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseNewsletterWebsitesGrid',
      'grid' => '\\Crud\\Grid\\NewsletterWebsitesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseNewsletterWebsitesForm',
      'form' => '\\Crud\\Form\\NewsletterWebsitesForm',
    ),
    'notifications' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseNotificationsGrid',
      'grid' => '\\Crud\\Grid\\NotificationsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseNotificationsForm',
      'form' => '\\Crud\\Form\\NotificationsForm',
    ),
    'order' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseOrderGrid',
      'grid' => '\\Crud\\Grid\\OrderGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseOrderForm',
      'form' => '\\Crud\\Form\\OrderForm',
    ),
    'order_address' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseOrderAddressGrid',
      'grid' => '\\Crud\\Grid\\OrderAddressGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseOrderAddressForm',
      'form' => '\\Crud\\Form\\OrderAddressForm',
    ),
    'order_flag_linker' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseOrderFlagLinkerGrid',
      'grid' => '\\Crud\\Grid\\OrderFlagLinkerGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseOrderFlagLinkerForm',
      'form' => '\\Crud\\Form\\OrderFlagLinkerForm',
    ),
    'order_flags' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseOrderFlagsGrid',
      'grid' => '\\Crud\\Grid\\OrderFlagsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseOrderFlagsForm',
      'form' => '\\Crud\\Form\\OrderFlagsForm',
    ),
    'order_line' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseOrderLineGrid',
      'grid' => '\\Crud\\Grid\\OrderLineGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseOrderLineForm',
      'form' => '\\Crud\\Form\\OrderLineForm',
    ),
    'payment_method' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePaymentMethodGrid',
      'grid' => '\\Crud\\Grid\\PaymentMethodGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePaymentMethodForm',
      'form' => '\\Crud\\Form\\PaymentMethodForm',
    ),
    'payment_session' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePaymentSessionGrid',
      'grid' => '\\Crud\\Grid\\PaymentSessionGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePaymentSessionForm',
      'form' => '\\Crud\\Form\\PaymentSessionForm',
    ),
    'payments' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePaymentsGrid',
      'grid' => '\\Crud\\Grid\\PaymentsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePaymentsForm',
      'form' => '\\Crud\\Form\\PaymentsForm',
    ),
    'payments_info' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePaymentsInfoGrid',
      'grid' => '\\Crud\\Grid\\PaymentsInfoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePaymentsInfoForm',
      'form' => '\\Crud\\Form\\PaymentsInfoForm',
    ),
    'permissions' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePermissionsGrid',
      'grid' => '\\Crud\\Grid\\PermissionsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePermissionsForm',
      'form' => '\\Crud\\Form\\PermissionsForm',
    ),
    'photos' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePhotosGrid',
      'grid' => '\\Crud\\Grid\\PhotosGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePhotosForm',
      'form' => '\\Crud\\Form\\PhotosForm',
    ),
    'pledge' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePledgeGrid',
      'grid' => '\\Crud\\Grid\\PledgeGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePledgeForm',
      'form' => '\\Crud\\Form\\PledgeForm',
    ),
    'pledge_funder' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePledgeFunderGrid',
      'grid' => '\\Crud\\Grid\\PledgeFunderGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePledgeFunderForm',
      'form' => '\\Crud\\Form\\PledgeFunderForm',
    ),
    'pledge_perk' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePledgePerkGrid',
      'grid' => '\\Crud\\Grid\\PledgePerkGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePledgePerkForm',
      'form' => '\\Crud\\Form\\PledgePerkForm',
    ),
    'pledge_update' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePledgeUpdateGrid',
      'grid' => '\\Crud\\Grid\\PledgeUpdateGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePledgeUpdateForm',
      'form' => '\\Crud\\Form\\PledgeUpdateForm',
    ),
    'product' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseProductGrid',
      'grid' => '\\Crud\\Grid\\ProductGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseProductForm',
      'form' => '\\Crud\\Form\\ProductForm',
    ),
    'purchased_content' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePurchasedContentGrid',
      'grid' => '\\Crud\\Grid\\PurchasedContentGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePurchasedContentForm',
      'form' => '\\Crud\\Form\\PurchasedContentForm',
    ),
    'rates' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseRatesGrid',
      'grid' => '\\Crud\\Grid\\RatesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseRatesForm',
      'form' => '\\Crud\\Form\\RatesForm',
    ),
    'rates_limits' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseRatesLimitsGrid',
      'grid' => '\\Crud\\Grid\\RatesLimitsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseRatesLimitsForm',
      'form' => '\\Crud\\Form\\RatesLimitsForm',
    ),
    'rb_comments' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseRbCommentsGrid',
      'grid' => '\\Crud\\Grid\\RbCommentsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseRbCommentsForm',
      'form' => '\\Crud\\Form\\RbCommentsForm',
    ),
    'reviews' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseReviewsGrid',
      'grid' => '\\Crud\\Grid\\ReviewsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseReviewsForm',
      'form' => '\\Crud\\Form\\ReviewsForm',
    ),
    'rules' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseRulesGrid',
      'grid' => '\\Crud\\Grid\\RulesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseRulesForm',
      'form' => '\\Crud\\Form\\RulesForm',
    ),
    'shows' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseShowsGrid',
      'grid' => '\\Crud\\Grid\\ShowsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseShowsForm',
      'form' => '\\Crud\\Form\\ShowsForm',
    ),
    'sounds' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseSoundsGrid',
      'grid' => '\\Crud\\Grid\\SoundsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseSoundsForm',
      'form' => '\\Crud\\Form\\SoundsForm',
    ),
    'special_requests' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseSpecialRequestsGrid',
      'grid' => '\\Crud\\Grid\\SpecialRequestsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseSpecialRequestsForm',
      'form' => '\\Crud\\Form\\SpecialRequestsForm',
    ),
    'static_pages' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseStaticPagesGrid',
      'grid' => '\\Crud\\Grid\\StaticPagesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseStaticPagesForm',
      'form' => '\\Crud\\Form\\StaticPagesForm',
    ),
    'studios' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseStudiosGrid',
      'grid' => '\\Crud\\Grid\\StudiosGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseStudiosForm',
      'form' => '\\Crud\\Form\\StudiosForm',
    ),
    'templates' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseTemplatesGrid',
      'grid' => '\\Crud\\Grid\\TemplatesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseTemplatesForm',
      'form' => '\\Crud\\Form\\TemplatesForm',
    ),
    'timezones' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseTimezonesGrid',
      'grid' => '\\Crud\\Grid\\TimezonesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseTimezonesForm',
      'form' => '\\Crud\\Form\\TimezonesForm',
    ),
    'transfer_wall' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseTransferWallGrid',
      'grid' => '\\Crud\\Grid\\TransferWallGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseTransferWallForm',
      'form' => '\\Crud\\Form\\TransferWallForm',
    ),
    'user' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserGrid',
      'grid' => '\\Crud\\Grid\\UserGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserForm',
      'form' => '\\Crud\\Form\\UserForm',
    ),
    'user_access' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserAccessGrid',
      'grid' => '\\Crud\\Grid\\UserAccessGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserAccessForm',
      'form' => '\\Crud\\Form\\UserAccessForm',
    ),
    'user_addresses' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserAddressesGrid',
      'grid' => '\\Crud\\Grid\\UserAddressesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserAddressesForm',
      'form' => '\\Crud\\Form\\UserAddressesForm',
    ),
    'user_favorites' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserFavoritesGrid',
      'grid' => '\\Crud\\Grid\\UserFavoritesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserFavoritesForm',
      'form' => '\\Crud\\Form\\UserFavoritesForm',
    ),
    'user_newsletter' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserNewsletterGrid',
      'grid' => '\\Crud\\Grid\\UserNewsletterGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserNewsletterForm',
      'form' => '\\Crud\\Form\\UserNewsletterForm',
    ),
    'user_notifications' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserNotificationsGrid',
      'grid' => '\\Crud\\Grid\\UserNotificationsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserNotificationsForm',
      'form' => '\\Crud\\Form\\UserNotificationsForm',
    ),
    'user_notifications_mail' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserNotificationsMailGrid',
      'grid' => '\\Crud\\Grid\\UserNotificationsMailGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserNotificationsMailForm',
      'form' => '\\Crud\\Form\\UserNotificationsMailForm',
    ),
    'user_notifications_type' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserNotificationsTypeGrid',
      'grid' => '\\Crud\\Grid\\UserNotificationsTypeGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserNotificationsTypeForm',
      'form' => '\\Crud\\Form\\UserNotificationsTypeForm',
    ),
    'user_paid_videos' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserPaidVideosGrid',
      'grid' => '\\Crud\\Grid\\UserPaidVideosGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserPaidVideosForm',
      'form' => '\\Crud\\Form\\UserPaidVideosForm',
    ),
    'user_resource' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserResourceGrid',
      'grid' => '\\Crud\\Grid\\UserResourceGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserResourceForm',
      'form' => '\\Crud\\Form\\UserResourceForm',
    ),
    'user_resource_value' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserResourceValueGrid',
      'grid' => '\\Crud\\Grid\\UserResourceValueGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserResourceValueForm',
      'form' => '\\Crud\\Form\\UserResourceValueForm',
    ),
    'user_role' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserRoleGrid',
      'grid' => '\\Crud\\Grid\\UserRoleGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserRoleForm',
      'form' => '\\Crud\\Form\\UserRoleForm',
    ),
    'user_role_linker' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserRoleLinkerGrid',
      'grid' => '\\Crud\\Grid\\UserRoleLinkerGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserRoleLinkerForm',
      'form' => '\\Crud\\Form\\UserRoleLinkerForm',
    ),
    'user_settings' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserSettingsGrid',
      'grid' => '\\Crud\\Grid\\UserSettingsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserSettingsForm',
      'form' => '\\Crud\\Form\\UserSettingsForm',
    ),
    'user_studios' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserStudiosGrid',
      'grid' => '\\Crud\\Grid\\UserStudiosGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserStudiosForm',
      'form' => '\\Crud\\Form\\UserStudiosForm',
    ),
    'video' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseVideoGrid',
      'grid' => '\\Crud\\Grid\\VideoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseVideoForm',
      'form' => '\\Crud\\Form\\VideoForm',
    ),
    'video_to_categories' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseVideoToCategoriesGrid',
      'grid' => '\\Crud\\Grid\\VideoToCategoriesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseVideoToCategoriesForm',
      'form' => '\\Crud\\Form\\VideoToCategoriesForm',
    ),
    'webcam_access' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseWebcamAccessGrid',
      'grid' => '\\Crud\\Grid\\WebcamAccessGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseWebcamAccessForm',
      'form' => '\\Crud\\Form\\WebcamAccessForm',
    ),
    'webchat_history' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseWebchatHistoryGrid',
      'grid' => '\\Crud\\Grid\\WebchatHistoryGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseWebchatHistoryForm',
      'form' => '\\Crud\\Form\\WebchatHistoryForm',
    ),
    'webchat_lines' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseWebchatLinesGrid',
      'grid' => '\\Crud\\Grid\\WebchatLinesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseWebchatLinesForm',
      'form' => '\\Crud\\Form\\WebchatLinesForm',
    ),
    'webchat_sessions' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseWebchatSessionsGrid',
      'grid' => '\\Crud\\Grid\\WebchatSessionsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseWebchatSessionsForm',
      'form' => '\\Crud\\Form\\WebchatSessionsForm',
    ),
    'webchat_users' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseWebchatUsersGrid',
      'grid' => '\\Crud\\Grid\\WebchatUsersGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseWebchatUsersForm',
      'form' => '\\Crud\\Form\\WebchatUsersForm',
    ),
    'website' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseWebsiteGrid',
      'grid' => '\\Crud\\Grid\\WebsiteGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseWebsiteForm',
      'form' => '\\Crud\\Form\\WebsiteForm',
    ),
    'ext_log_entries' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseExtLogEntriesGrid',
      'grid' => '\\Crud\\Grid\\ExtLogEntriesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseExtLogEntriesForm',
      'form' => '\\Crud\\Form\\ExtLogEntriesForm',
    ),
    'logo' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseLogoGrid',
      'grid' => '\\Crud\\Grid\\LogoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseLogoForm',
      'form' => '\\Crud\\Form\\LogoForm',
    ),
    'country' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseCountryGrid',
      'grid' => '\\Crud\\Grid\\CountryGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseCountryForm',
      'form' => '\\Crud\\Form\\CountryForm',
    ),
    'package' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePackageGrid',
      'grid' => '\\Crud\\Grid\\PackageGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePackageForm',
      'form' => '\\Crud\\Form\\PackageForm',
    ),
    'payment' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePaymentGrid',
      'grid' => '\\Crud\\Grid\\PaymentGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePaymentForm',
      'form' => '\\Crud\\Form\\PaymentForm',
    ),
    'payment_token' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePaymentTokenGrid',
      'grid' => '\\Crud\\Grid\\PaymentTokenGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePaymentTokenForm',
      'form' => '\\Crud\\Form\\PaymentTokenForm',
    ),
    'funders' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseFundersGrid',
      'grid' => '\\Crud\\Grid\\FundersGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseFundersForm',
      'form' => '\\Crud\\Form\\FundersForm',
    ),
    'interaction' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseInteractionGrid',
      'grid' => '\\Crud\\Grid\\InteractionGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseInteractionForm',
      'form' => '\\Crud\\Form\\InteractionForm',
    ),
    'interactions' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseInteractionsGrid',
      'grid' => '\\Crud\\Grid\\InteractionsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseInteractionsForm',
      'form' => '\\Crud\\Form\\InteractionsForm',
    ),
    'photo' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BasePhotoGrid',
      'grid' => '\\Crud\\Grid\\PhotoGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BasePhotoForm',
      'form' => '\\Crud\\Form\\PhotoForm',
    ),
    'resource_values' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseResourceValuesGrid',
      'grid' => '\\Crud\\Grid\\ResourceValuesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseResourceValuesForm',
      'form' => '\\Crud\\Form\\ResourceValuesForm',
    ),
    'resources' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseResourcesGrid',
      'grid' => '\\Crud\\Grid\\ResourcesGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseResourcesForm',
      'form' => '\\Crud\\Form\\ResourcesForm',
    ),
    'scheduled_media' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseScheduledMediaGrid',
      'grid' => '\\Crud\\Grid\\ScheduledMediaGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseScheduledMediaForm',
      'form' => '\\Crud\\Form\\ScheduledMediaForm',
    ),
    'magento_cache' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMagentoCacheGrid',
      'grid' => '\\Crud\\Grid\\MagentoCacheGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMagentoCacheForm',
      'form' => '\\Crud\\Form\\MagentoCacheForm',
    ),
    'magento_cache_tag' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMagentoCacheTagGrid',
      'grid' => '\\Crud\\Grid\\MagentoCacheTagGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMagentoCacheTagForm',
      'form' => '\\Crud\\Form\\MagentoCacheTagForm',
    ),
    'magento_flag' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMagentoFlagGrid',
      'grid' => '\\Crud\\Grid\\MagentoFlagGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMagentoFlagForm',
      'form' => '\\Crud\\Form\\MagentoFlagForm',
    ),
    'magento_session' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMagentoSessionGrid',
      'grid' => '\\Crud\\Grid\\MagentoSessionGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMagentoSessionForm',
      'form' => '\\Crud\\Form\\MagentoSessionForm',
    ),
    'magento_setup_module' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseMagentoSetupModuleGrid',
      'grid' => '\\Crud\\Grid\\MagentoSetupModuleGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseMagentoSetupModuleForm',
      'form' => '\\Crud\\Form\\MagentoSetupModuleForm',
    ),
    'user_warnings' =>
    array (
      'baseGrid' => '\\Crud\\Grid\\BaseGrid\\BaseUserWarningsGrid',
      'grid' => '\\Crud\\Grid\\UserWarningsGrid',
      'baseForm' => '\\Crud\\Form\\BaseForm\\BaseUserWarningsForm',
      'form' => '\\Crud\\Form\\UserWarningsForm',
    ),
  ),
  'controller' =>
  array (
    'address' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseAddressController',
      'controller' => '\\Crud\\Controller\\AddressController',
    ),
    'albums' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseAlbumsController',
      'controller' => '\\Crud\\Controller\\AlbumsController',
    ),
    'announcements' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseAnnouncementsController',
      'controller' => '\\Crud\\Controller\\AnnouncementsController',
    ),
    'ansi_uom' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseAnsiUomController',
      'controller' => '\\Crud\\Controller\\AnsiUomController',
    ),
    'autoresponders' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseAutorespondersController',
      'controller' => '\\Crud\\Controller\\AutorespondersController',
    ),
    'autoresponders_train' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseAutorespondersTrainController',
      'controller' => '\\Crud\\Controller\\AutorespondersTrainController',
    ),
    'bad_words' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseBadWordsController',
      'controller' => '\\Crud\\Controller\\BadWordsController',
    ),
    'banners' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseBannersController',
      'controller' => '\\Crud\\Controller\\BannersController',
    ),
    'blog_access' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseBlogAccessController',
      'controller' => '\\Crud\\Controller\\BlogAccessController',
    ),
    'blog_categories' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseBlogCategoriesController',
      'controller' => '\\Crud\\Controller\\BlogCategoriesController',
    ),
    'blog_posts' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseBlogPostsController',
      'controller' => '\\Crud\\Controller\\BlogPostsController',
    ),
    'blog_purchase' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseBlogPurchaseController',
      'controller' => '\\Crud\\Controller\\BlogPurchaseController',
    ),
    'call_log' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCallLogController',
      'controller' => '\\Crud\\Controller\\CallLogController',
    ),
    'cart' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCartController',
      'controller' => '\\Crud\\Controller\\CartController',
    ),
    'cart_item' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCartItemController',
      'controller' => '\\Crud\\Controller\\CartItemController',
    ),
    'catalog_availability' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogAvailabilityController',
      'controller' => '\\Crud\\Controller\\CatalogAvailabilityController',
    ),
    'catalog_builder_product' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogBuilderProductController',
      'controller' => '\\Crud\\Controller\\CatalogBuilderProductController',
    ),
    'catalog_category' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogCategoryController',
      'controller' => '\\Crud\\Controller\\CatalogCategoryController',
    ),
    'catalog_category_product' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogCategoryProductController',
      'controller' => '\\Crud\\Controller\\CatalogCategoryProductController',
    ),
    'catalog_category_website' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogCategoryWebsiteController',
      'controller' => '\\Crud\\Controller\\CatalogCategoryWebsiteController',
    ),
    'catalog_choice' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogChoiceController',
      'controller' => '\\Crud\\Controller\\CatalogChoiceController',
    ),
    'catalog_choice_option' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogChoiceOptionController',
      'controller' => '\\Crud\\Controller\\CatalogChoiceOptionController',
    ),
    'catalog_option' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogOptionController',
      'controller' => '\\Crud\\Controller\\CatalogOptionController',
    ),
    'catalog_option_image' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogOptionImageController',
      'controller' => '\\Crud\\Controller\\CatalogOptionImageController',
    ),
    'catalog_product' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductController',
      'controller' => '\\Crud\\Controller\\CatalogProductController',
    ),
    'catalog_product_document' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductDocumentController',
      'controller' => '\\Crud\\Controller\\CatalogProductDocumentController',
    ),
    'catalog_product_feature' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductFeatureController',
      'controller' => '\\Crud\\Controller\\CatalogProductFeatureController',
    ),
    'catalog_product_image' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductImageController',
      'controller' => '\\Crud\\Controller\\CatalogProductImageController',
    ),
    'catalog_product_option' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductOptionController',
      'controller' => '\\Crud\\Controller\\CatalogProductOptionController',
    ),
    'catalog_product_spec' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductSpecController',
      'controller' => '\\Crud\\Controller\\CatalogProductSpecController',
    ),
    'catalog_product_type' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductTypeController',
      'controller' => '\\Crud\\Controller\\CatalogProductTypeController',
    ),
    'catalog_product_uom' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCatalogProductUomController',
      'controller' => '\\Crud\\Controller\\CatalogProductUomController',
    ),
    'categories' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCategoriesController',
      'controller' => '\\Crud\\Controller\\CategoriesController',
    ),
    'chips' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseChipsController',
      'controller' => '\\Crud\\Controller\\ChipsController',
    ),
    'chips_packages' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseChipsPackagesController',
      'controller' => '\\Crud\\Controller\\ChipsPackagesController',
    ),
    'config' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseConfigController',
      'controller' => '\\Crud\\Controller\\ConfigController',
    ),
    'configadminvalues' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseConfigadminvaluesController',
      'controller' => '\\Crud\\Controller\\ConfigadminvaluesController',
    ),
    'contact' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactController',
      'controller' => '\\Crud\\Controller\\ContactController',
    ),
    'contact_addresses' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactAddressesController',
      'controller' => '\\Crud\\Controller\\ContactAddressesController',
    ),
    'contact_companies' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactCompaniesController',
      'controller' => '\\Crud\\Controller\\ContactCompaniesController',
    ),
    'contact_company' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactCompanyController',
      'controller' => '\\Crud\\Controller\\ContactCompanyController',
    ),
    'contact_email' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactEmailController',
      'controller' => '\\Crud\\Controller\\ContactEmailController',
    ),
    'contact_phone' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactPhoneController',
      'controller' => '\\Crud\\Controller\\ContactPhoneController',
    ),
    'contact_url' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseContactUrlController',
      'controller' => '\\Crud\\Controller\\ContactUrlController',
    ),
    'countries' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCountriesController',
      'controller' => '\\Crud\\Controller\\CountriesController',
    ),
    'country_city' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCountryCityController',
      'controller' => '\\Crud\\Controller\\CountryCityController',
    ),
    'country_codes' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCountryCodesController',
      'controller' => '\\Crud\\Controller\\CountryCodesController',
    ),
    'country_province' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCountryProvinceController',
      'controller' => '\\Crud\\Controller\\CountryProvinceController',
    ),
    'customer' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCustomerController',
      'controller' => '\\Crud\\Controller\\CustomerController',
    ),
    'earning_stats' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseEarningStatsController',
      'controller' => '\\Crud\\Controller\\EarningStatsController',
    ),
    'featured_product' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseFeaturedProductController',
      'controller' => '\\Crud\\Controller\\FeaturedProductController',
    ),
    'followers' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseFollowersController',
      'controller' => '\\Crud\\Controller\\FollowersController',
    ),
    'friends' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseFriendsController',
      'controller' => '\\Crud\\Controller\\FriendsController',
    ),
    'hidden_models' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseHiddenModelsController',
      'controller' => '\\Crud\\Controller\\HiddenModelsController',
    ),
    'images_deprecated' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseImagesDeprecatedController',
      'controller' => '\\Crud\\Controller\\ImagesDeprecatedController',
    ),
    'info' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseInfoController',
      'controller' => '\\Crud\\Controller\\InfoController',
    ),
    'info_modelinfo' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseInfoModelinfoController',
      'controller' => '\\Crud\\Controller\\InfoModelinfoController',
    ),
    'managers_studios' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseManagersStudiosController',
      'controller' => '\\Crud\\Controller\\ManagersStudiosController',
    ),
    'messages' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMessagesController',
      'controller' => '\\Crud\\Controller\\MessagesController',
    ),
    'migrations' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMigrationsController',
      'controller' => '\\Crud\\Controller\\MigrationsController',
    ),
    'model' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelController',
      'controller' => '\\Crud\\Controller\\ModelController',
    ),
    'model_access' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelAccessController',
      'controller' => '\\Crud\\Controller\\ModelAccessController',
    ),
    'model_actions' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelActionsController',
      'controller' => '\\Crud\\Controller\\ModelActionsController',
    ),
    'model_block_access' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelBlockAccessController',
      'controller' => '\\Crud\\Controller\\ModelBlockAccessController',
    ),
    'model_info' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelInfoController',
      'controller' => '\\Crud\\Controller\\ModelInfoController',
    ),
    'model_moderator' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelModeratorController',
      'controller' => '\\Crud\\Controller\\ModelModeratorController',
    ),
    'model_notes' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelNotesController',
      'controller' => '\\Crud\\Controller\\ModelNotesController',
    ),
    'model_quotes' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelQuotesController',
      'controller' => '\\Crud\\Controller\\ModelQuotesController',
    ),
    'model_rates' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelRatesController',
      'controller' => '\\Crud\\Controller\\ModelRatesController',
    ),
    'model_rates_pending' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelRatesPendingController',
      'controller' => '\\Crud\\Controller\\ModelRatesPendingController',
    ),
    'model_requests' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelRequestsController',
      'controller' => '\\Crud\\Controller\\ModelRequestsController',
    ),
    'model_schedule' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelScheduleController',
      'controller' => '\\Crud\\Controller\\ModelScheduleController',
    ),
    'model_to_categories' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelToCategoriesController',
      'controller' => '\\Crud\\Controller\\ModelToCategoriesController',
    ),
    'model_user_access' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelUserAccessController',
      'controller' => '\\Crud\\Controller\\ModelUserAccessController',
    ),
    'model_user_notes' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelUserNotesController',
      'controller' => '\\Crud\\Controller\\ModelUserNotesController',
    ),
    'model_wall' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModelWallController',
      'controller' => '\\Crud\\Controller\\ModelWallController',
    ),
    'moderator' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModeratorController',
      'controller' => '\\Crud\\Controller\\ModeratorController',
    ),
    'moderator_notes' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseModeratorNotesController',
      'controller' => '\\Crud\\Controller\\ModeratorNotesController',
    ),
    'newsletter' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseNewsletterController',
      'controller' => '\\Crud\\Controller\\NewsletterController',
    ),
    'newsletter_websites' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseNewsletterWebsitesController',
      'controller' => '\\Crud\\Controller\\NewsletterWebsitesController',
    ),
    'notifications' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseNotificationsController',
      'controller' => '\\Crud\\Controller\\NotificationsController',
    ),
    'order' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseOrderController',
      'controller' => '\\Crud\\Controller\\OrderController',
    ),
    'order_address' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseOrderAddressController',
      'controller' => '\\Crud\\Controller\\OrderAddressController',
    ),
    'order_flag_linker' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseOrderFlagLinkerController',
      'controller' => '\\Crud\\Controller\\OrderFlagLinkerController',
    ),
    'order_flags' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseOrderFlagsController',
      'controller' => '\\Crud\\Controller\\OrderFlagsController',
    ),
    'order_line' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseOrderLineController',
      'controller' => '\\Crud\\Controller\\OrderLineController',
    ),
    'payment_method' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePaymentMethodController',
      'controller' => '\\Crud\\Controller\\PaymentMethodController',
    ),
    'payment_session' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePaymentSessionController',
      'controller' => '\\Crud\\Controller\\PaymentSessionController',
    ),
    'payments' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePaymentsController',
      'controller' => '\\Crud\\Controller\\PaymentsController',
    ),
    'payments_info' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePaymentsInfoController',
      'controller' => '\\Crud\\Controller\\PaymentsInfoController',
    ),
    'permissions' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePermissionsController',
      'controller' => '\\Crud\\Controller\\PermissionsController',
    ),
    'photos' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePhotosController',
      'controller' => '\\Crud\\Controller\\PhotosController',
    ),
    'pledge' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePledgeController',
      'controller' => '\\Crud\\Controller\\PledgeController',
    ),
    'pledge_funder' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePledgeFunderController',
      'controller' => '\\Crud\\Controller\\PledgeFunderController',
    ),
    'pledge_perk' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePledgePerkController',
      'controller' => '\\Crud\\Controller\\PledgePerkController',
    ),
    'pledge_update' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePledgeUpdateController',
      'controller' => '\\Crud\\Controller\\PledgeUpdateController',
    ),
    'product' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseProductController',
      'controller' => '\\Crud\\Controller\\ProductController',
    ),
    'purchased_content' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePurchasedContentController',
      'controller' => '\\Crud\\Controller\\PurchasedContentController',
    ),
    'rates' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseRatesController',
      'controller' => '\\Crud\\Controller\\RatesController',
    ),
    'rates_limits' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseRatesLimitsController',
      'controller' => '\\Crud\\Controller\\RatesLimitsController',
    ),
    'rb_comments' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseRbCommentsController',
      'controller' => '\\Crud\\Controller\\RbCommentsController',
    ),
    'reviews' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseReviewsController',
      'controller' => '\\Crud\\Controller\\ReviewsController',
    ),
    'rules' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseRulesController',
      'controller' => '\\Crud\\Controller\\RulesController',
    ),
    'shows' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseShowsController',
      'controller' => '\\Crud\\Controller\\ShowsController',
    ),
    'sounds' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseSoundsController',
      'controller' => '\\Crud\\Controller\\SoundsController',
    ),
    'special_requests' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseSpecialRequestsController',
      'controller' => '\\Crud\\Controller\\SpecialRequestsController',
    ),
    'static_pages' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseStaticPagesController',
      'controller' => '\\Crud\\Controller\\StaticPagesController',
    ),
    'studios' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseStudiosController',
      'controller' => '\\Crud\\Controller\\StudiosController',
    ),
    'templates' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseTemplatesController',
      'controller' => '\\Crud\\Controller\\TemplatesController',
    ),
    'timezones' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseTimezonesController',
      'controller' => '\\Crud\\Controller\\TimezonesController',
    ),
    'transfer_wall' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseTransferWallController',
      'controller' => '\\Crud\\Controller\\TransferWallController',
    ),
    'user' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserController',
      'controller' => '\\Crud\\Controller\\UserController',
    ),
    'user_access' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserAccessController',
      'controller' => '\\Crud\\Controller\\UserAccessController',
    ),
    'user_addresses' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserAddressesController',
      'controller' => '\\Crud\\Controller\\UserAddressesController',
    ),
    'user_favorites' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserFavoritesController',
      'controller' => '\\Crud\\Controller\\UserFavoritesController',
    ),
    'user_newsletter' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserNewsletterController',
      'controller' => '\\Crud\\Controller\\UserNewsletterController',
    ),
    'user_notifications' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserNotificationsController',
      'controller' => '\\Crud\\Controller\\UserNotificationsController',
    ),
    'user_notifications_mail' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserNotificationsMailController',
      'controller' => '\\Crud\\Controller\\UserNotificationsMailController',
    ),
    'user_notifications_type' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserNotificationsTypeController',
      'controller' => '\\Crud\\Controller\\UserNotificationsTypeController',
    ),
    'user_paid_videos' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserPaidVideosController',
      'controller' => '\\Crud\\Controller\\UserPaidVideosController',
    ),
    'user_resource' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserResourceController',
      'controller' => '\\Crud\\Controller\\UserResourceController',
    ),
    'user_resource_value' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserResourceValueController',
      'controller' => '\\Crud\\Controller\\UserResourceValueController',
    ),
    'user_role' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserRoleController',
      'controller' => '\\Crud\\Controller\\UserRoleController',
    ),
    'user_role_linker' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserRoleLinkerController',
      'controller' => '\\Crud\\Controller\\UserRoleLinkerController',
    ),
    'user_settings' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserSettingsController',
      'controller' => '\\Crud\\Controller\\UserSettingsController',
    ),
    'user_studios' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserStudiosController',
      'controller' => '\\Crud\\Controller\\UserStudiosController',
    ),
    'video' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseVideoController',
      'controller' => '\\Crud\\Controller\\VideoController',
    ),
    'video_to_categories' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseVideoToCategoriesController',
      'controller' => '\\Crud\\Controller\\VideoToCategoriesController',
    ),
    'webcam_access' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseWebcamAccessController',
      'controller' => '\\Crud\\Controller\\WebcamAccessController',
    ),
    'webchat_history' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseWebchatHistoryController',
      'controller' => '\\Crud\\Controller\\WebchatHistoryController',
    ),
    'webchat_lines' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseWebchatLinesController',
      'controller' => '\\Crud\\Controller\\WebchatLinesController',
    ),
    'webchat_sessions' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseWebchatSessionsController',
      'controller' => '\\Crud\\Controller\\WebchatSessionsController',
    ),
    'webchat_users' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseWebchatUsersController',
      'controller' => '\\Crud\\Controller\\WebchatUsersController',
    ),
    'website' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseWebsiteController',
      'controller' => '\\Crud\\Controller\\WebsiteController',
    ),
    'ext_log_entries' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseExtLogEntriesController',
      'controller' => '\\Crud\\Controller\\ExtLogEntriesController',
    ),
    'logo' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseLogoController',
      'controller' => '\\Crud\\Controller\\LogoController',
    ),
    'country' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseCountryController',
      'controller' => '\\Crud\\Controller\\CountryController',
    ),
    'package' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePackageController',
      'controller' => '\\Crud\\Controller\\PackageController',
    ),
    'payment' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePaymentController',
      'controller' => '\\Crud\\Controller\\PaymentController',
    ),
    'payment_token' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePaymentTokenController',
      'controller' => '\\Crud\\Controller\\PaymentTokenController',
    ),
    'funders' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseFundersController',
      'controller' => '\\Crud\\Controller\\FundersController',
    ),
    'interaction' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseInteractionController',
      'controller' => '\\Crud\\Controller\\InteractionController',
    ),
    'interactions' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseInteractionsController',
      'controller' => '\\Crud\\Controller\\InteractionsController',
    ),
    'photo' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BasePhotoController',
      'controller' => '\\Crud\\Controller\\PhotoController',
    ),
    'resource_values' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseResourceValuesController',
      'controller' => '\\Crud\\Controller\\ResourceValuesController',
    ),
    'resources' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseResourcesController',
      'controller' => '\\Crud\\Controller\\ResourcesController',
    ),
    'scheduled_media' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseScheduledMediaController',
      'controller' => '\\Crud\\Controller\\ScheduledMediaController',
    ),
    'magento_cache' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMagentoCacheController',
      'controller' => '\\Crud\\Controller\\MagentoCacheController',
    ),
    'magento_cache_tag' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMagentoCacheTagController',
      'controller' => '\\Crud\\Controller\\MagentoCacheTagController',
    ),
    'magento_flag' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMagentoFlagController',
      'controller' => '\\Crud\\Controller\\MagentoFlagController',
    ),
    'magento_session' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMagentoSessionController',
      'controller' => '\\Crud\\Controller\\MagentoSessionController',
    ),
    'magento_setup_module' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseMagentoSetupModuleController',
      'controller' => '\\Crud\\Controller\\MagentoSetupModuleController',
    ),
    'user_warnings' =>
    array (
      'baseController' => '\\Crud\\Controller\\Base\\BaseUserWarningsController',
      'controller' => '\\Crud\\Controller\\UserWarningsController',
    ),
  ),
  'view' =>
  array (
  ),
);