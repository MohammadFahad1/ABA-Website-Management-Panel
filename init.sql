-- Ahmed Bawany Academy Panel Database Initialization Script
CREATE DATABASE IF NOT EXISTS `ahmedbawanyacade_sacc` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ahmedbawanyacade_sacc`;

-- Table structure for `website_notices`
CREATE TABLE IF NOT EXISTS `website_notices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `entry_by` INT(11) DEFAULT 1,
  `entry_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `status` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `notice_files`
CREATE TABLE IF NOT EXISTS `notice_files` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `notice_id` INT(11) NOT NULL,
  `files` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notice_id` (`notice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `website_front_carousel`
CREATE TABLE IF NOT EXISTS `website_front_carousel` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `slide_1` VARCHAR(255) DEFAULT 'images/front-carousel-slider/01.jpg',
  `slide1_title` VARCHAR(255) DEFAULT '',
  `slide1_desc` TEXT DEFAULT NULL,
  `slide_2` VARCHAR(255) DEFAULT 'images/front-carousel-slider/02.jpg',
  `slide2_title` VARCHAR(255) DEFAULT '',
  `slide2_desc` TEXT DEFAULT NULL,
  `slide_3` VARCHAR(255) DEFAULT 'images/front-carousel-slider/03.jpg',
  `slide3_title` VARCHAR(255) DEFAULT '',
  `slide3_desc` TEXT DEFAULT NULL,
  `slide_4` VARCHAR(255) DEFAULT 'images/front-carousel-slider/04.jpg',
  `slide4_title` VARCHAR(255) DEFAULT '',
  `slide4_desc` TEXT DEFAULT NULL,
  `slide_5` VARCHAR(255) DEFAULT 'images/front-carousel-slider/05.jpg',
  `slide5_title` VARCHAR(255) DEFAULT '',
  `slide5_desc` TEXT DEFAULT NULL,
  `slide_6` VARCHAR(255) DEFAULT 'images/front-carousel-slider/06.jpg',
  `slide6_title` VARCHAR(255) DEFAULT '',
  `slide6_desc` TEXT DEFAULT NULL,
  `slide_7` VARCHAR(255) DEFAULT 'images/front-carousel-slider/07.jpg',
  `slide7_title` VARCHAR(255) DEFAULT '',
  `slide7_desc` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `teacher_staff_info`
CREATE TABLE IF NOT EXISTS `teacher_staff_info` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `shift` VARCHAR(50) DEFAULT NULL,
  `section` VARCHAR(100) DEFAULT NULL,
  `designation` VARCHAR(100) DEFAULT NULL,
  `sl` INT(11) DEFAULT 0,
  `mobile` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for `website_gallery`
CREATE TABLE IF NOT EXISTS `website_gallery` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `image_url` VARCHAR(255) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert initial record for website_front_carousel if empty
INSERT INTO `website_front_carousel` (`id`, `slide_1`, `slide1_title`, `slide1_desc`, `slide_2`, `slide2_title`, `slide2_desc`, `slide_3`, `slide3_title`, `slide3_desc`, `slide_4`, `slide4_title`, `slide4_desc`, `slide_5`, `slide5_title`, `slide5_desc`, `slide_6`, `slide6_title`, `slide6_desc`, `slide_7`, `slide7_title`, `slide7_desc`)
SELECT 1, 'images/front-carousel-slider/01.jpg', 'Welcome to Ahmed Bawany Academy', 'Excellence in Education', 'images/front-carousel-slider/02.jpg', 'Campus View', 'Modern Learning Environment', 'images/front-carousel-slider/03.jpg', 'Academic Excellence', 'Dedicated Faculty & Staff', 'images/front-carousel-slider/04.jpg', 'Co-curricular Activities', 'Nurturing Talent & Creativity', 'images/front-carousel-slider/05.jpg', 'Sports & Fitness', 'Building Team Spirit', 'images/front-carousel-slider/06.jpg', 'Science & Tech Lab', 'Innovation and Learning', 'images/front-carousel-slider/07.jpg', 'Library & Research', 'Knowledge Center'
FROM DUAL WHERE NOT EXISTS (SELECT * FROM `website_front_carousel` WHERE `id` = 1);
