DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `var` varchar(255) NOT NULL,
  `val` varchar(255) NOT NULL,
  PRIMARY KEY (`var`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO `config` (`var`, `val`) VALUES
('banner_size', '{"0":"300x150","1":"150x300","2":"600x150"}'),
('banner_zone', '{"300x120":["z1","z2"],"500x120":["zone1","zone2"]}'),
('call_cost', '56'),
('chips_parity', '10'),
('chips_transfer_limit', '200'),
('contact_mail', 'razvan.moldovan@xexposed.com'),
('default_upload_dir', '/uploads'),
('disqus_shortname', 'devchatbranch'),
('gift_address', '43 s pompano prakway'),
('gift_city', '12208'),
('gift_country', '230'),
('gift_region', '4175'),
('gift_zip', '33064'),
('login_attempt', '3'),
('max_allowed_time', '700'),
('max_allowed_time_without_chips', '66'),
('max_group_users', '10'),
('min_group_users', '2'),
('model_album_cover', '/assets/defaults/images/no-picture.jpg'),
('model_body_type', 'petite , slim , curvy , thick ,athletic ,muscular,bbw, amble '),
('model_default_chat_timeout', '5'),
('model_default_cover', '/assets/defaults/users/avatar-default.jpg'),
('model_default_headshot', '/assets/defaults/users/avatar-default.jpg'),
('model_default_photo_id', '/assets/defaults/users/avatar-default.jpg'),
('model_hair_type', 'black, brunette, blonde, redhead , gray, other '),
('model_orientation', 'strictly dickly , lesbo , go both ways , bi-curious'),
('photo_watermark', '/uploads/watermarks/watermark.png'),
('rtmp', 'rtmp://xexposed.com/oflaDemo'),
('screenshots_per_video', '6'),
('site_name', 'dev.Xexposed.com'),
('timezone', '80'),
('tokens_per_second', '1'),
('user_default_avatar', '/assets/defaults/users/avatar-default.jpg'),
('video_default_bio', '/assets/defaults/videos/default-bio.mp4'),
('video_logo', '/uploads/watermarks/logo-player.png'),
('vod_price', '4.99');