CREATE TABLE `test` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET armscii8 NOT NULL,
  `months` int(11) NOT NULL,
  `price` double NOT NULL,
  `discounted_price` double NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;