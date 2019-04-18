DROP TABLE `user`;
CREATE TABLE `user` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `name` TEXT,
  `email` TEXT,
  `password` TEXT,
  `created` TEXT,
  `modified` TEXT
);

DROP TABLE `product`;
CREATE TABLE `product` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `title` TEXT,
  `description` TEXT,
  `image` TEXT,
  `price` REAL,
  `created` TEXT,
  `modified` TEXT
);