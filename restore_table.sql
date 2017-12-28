
-- phpMyAdmin
-- SQL TAB
-- IMPORTANT! Enable foreign key checks: OFF

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE user;
TRUNCATE TABLE status;
TRUNCATE TABLE following;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `user` (`id`, `user_name`, `password`, `created_at`) VALUES
(1, 'user1', 'ee5281d035bd1bd7786301be4274a68b006ae916', '2017-11-01 00:00:00'),
(2, 'user2', 'ee5281d035bd1bd7786301be4274a68b006ae916', '2017-11-01 00:00:00'),
(3, 'user3', 'ee5281d035bd1bd7786301be4274a68b006ae916', '2017-11-01 00:00:00');


INSERT INTO `status` (`id`, `user_id`, `body`, `created_at`) VALUES
(1, 1, 'status1 user1 test1', '2017-11-01 00:00:00'),
(2, 1, 'status2 user1 test2', '2017-11-01 00:00:00'),
(3, 2, 'status3 user2 test3', '2017-11-01 00:00:00'),
(4, 2, 'status4 user2 test4', '2017-11-01 00:00:00'),
(5, 3, 'status5 user3 test5', '2017-11-01 00:00:00');


INSERT INTO `following` (`user_id`, `following_id`) VALUES
(1, 2);

