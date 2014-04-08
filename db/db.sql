-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014 年 04 月 08 日 14:28
-- 服务器版本: 5.5.24-log
-- PHP 版本: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `shoppingcart`
--

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL COMMENT '管理员名',
  `pwd` varchar(50) NOT NULL COMMENT '密码',
  `leve` int(1) NOT NULL DEFAULT '1' COMMENT '1：管理员，2：超级管理员',
  `delete_flg` int(1) NOT NULL DEFAULT '0' COMMENT '0：未删除，1：已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `uname`, `pwd`, `leve`, `delete_flg`) VALUES
(1, 'admin', '4297f44b13955235245b2497399d7a93', 2, 0);

-- --------------------------------------------------------

--
-- 表的结构 `advertisement`
--

CREATE TABLE IF NOT EXISTS `advertisement` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ad_name` varchar(20) NOT NULL,
  `ad_type` varchar(20) NOT NULL COMMENT '广告类型',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `advertisement`
--

INSERT INTO `advertisement` (`ad_id`, `ad_name`, `ad_type`) VALUES
(1, 'é¦–é¡µè¼ªæ’­', 'index_top'),
(2, 'å·¦ä¾§æ ', 'left'),
(3, 'å³ä¾§æ ', 'right');

-- --------------------------------------------------------

--
-- 表的结构 `ad_product`
--

CREATE TABLE IF NOT EXISTS `ad_product` (
  `ad_product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ad_id` int(11) NOT NULL COMMENT '广告位id FK',
  `product_id` int(11) NOT NULL COMMENT '商品id FK',
  PRIMARY KEY (`ad_product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `ad_product`
--

INSERT INTO `ad_product` (`ad_product_id`, `ad_id`, `product_id`) VALUES
(8, 2, 32),
(9, 3, 32),
(16, 1, 34),
(17, 2, 34),
(34, 1, 31),
(35, 3, 31),
(36, 2, 22);

-- --------------------------------------------------------

--
-- 表的结构 `cart`
--

CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) NOT NULL COMMENT '用户表id FK',
  `total` int(11) NOT NULL COMMENT '总金额',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `cart_product`
--

CREATE TABLE IF NOT EXISTS `cart_product` (
  `cproduct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `product_id` int(11) NOT NULL COMMENT '商品表id FK',
  PRIMARY KEY (`cproduct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车和商品关联表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `forum`
--

CREATE TABLE IF NOT EXISTS `forum` (
  `forum_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `forum_name` varchar(50) NOT NULL COMMENT '版块名',
  PRIMARY KEY (`forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `forum`
--

INSERT INTO `forum` (`forum_id`, `forum_name`) VALUES
(1, 'æ–°å•†å“'),
(2, 'æŽ¨èå•†å“');

-- --------------------------------------------------------

--
-- 表的结构 `forum_product`
--

CREATE TABLE IF NOT EXISTS `forum_product` (
  `fp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `forum_id` int(11) NOT NULL COMMENT '版块id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`fp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `forum_product`
--

INSERT INTO `forum_product` (`fp_id`, `forum_id`, `product_id`) VALUES
(1, 1, 22),
(2, 1, 31),
(5, 2, 34),
(6, 2, 31);

-- --------------------------------------------------------

--
-- 表的结构 `image_product`
--

CREATE TABLE IF NOT EXISTS `image_product` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `product_id` int(11) NOT NULL COMMENT '商品表id',
  `image_path` varchar(50) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `image_product`
--

INSERT INTO `image_product` (`image_id`, `product_id`, `image_path`) VALUES
(2, 19, 'upload/1395578769350170.jpg'),
(3, 19, 'upload/1395647496410019.jpg'),
(4, 21, 'upload/1395649323564312.jpg'),
(5, 21, 'upload/1395649323778577.jpg'),
(6, 21, 'upload/1395649323984240.jpg'),
(7, 22, 'upload/1395650387405995.jpg'),
(8, 31, 'upload/1395805941477787.jpg'),
(9, 31, 'upload/1395805941756590.jpg'),
(10, 34, 'upload/1396089508437477.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) NOT NULL COMMENT '用户表id FK',
  `total` int(11) NOT NULL COMMENT '总金额',
  `status` int(11) DEFAULT '0' COMMENT '状态 0:未付款 1:已付款',
  `creat_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `delete_flg` int(11) DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `total`, `status`, `creat_time`, `update_time`, `delete_flg`) VALUES
(1, 1, 200, 1, 1396421573, 1396425812, 0);

-- --------------------------------------------------------

--
-- 表的结构 `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `oproduct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) NOT NULL COMMENT '订单表id FK',
  `product_id` int(11) NOT NULL COMMENT '商品表id FK',
  PRIMARY KEY (`oproduct_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单和商品关联表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `order_product`
--

INSERT INTO `order_product` (`oproduct_id`, `order_id`, `product_id`) VALUES
(1, 1, 19),
(2, 1, 22),
(3, 1, 21);

-- --------------------------------------------------------

--
-- 表的结构 `page`
--

CREATE TABLE IF NOT EXISTS `page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `page_title` varchar(50) NOT NULL COMMENT '页面标题',
  `page_body` text NOT NULL COMMENT '页面内容',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `page`
--

INSERT INTO `page` (`page_id`, `page_title`, `page_body`, `create_time`, `update_time`) VALUES
(3, 'æµ‹è¯•é¡µé¢1', 'æµ‹è¯•é¡µé¢1æµ‹è¯•é¡µé¢1', 1396689349, 1396689349);

-- --------------------------------------------------------

--
-- 表的结构 `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(100) NOT NULL COMMENT '商品名',
  `ptype_id` int(11) NOT NULL COMMENT '商品分类 FK',
  `original_price` int(11) DEFAULT NULL COMMENT '原价',
  `price` int(11) NOT NULL COMMENT '现价',
  `stock` int(11) DEFAULT '0' COMMENT '库存',
  `description` text COMMENT '介绍',
  `is_add` int(1) NOT NULL DEFAULT '0' COMMENT '是否上架 0:未上架 1:已上架',
  `creat_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL,
  `delete_flg` int(11) DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品表' AUTO_INCREMENT=35 ;

--
-- 转存表中的数据 `product`
--

INSERT INTO `product` (`product_id`, `name`, `ptype_id`, `original_price`, `price`, `stock`, `description`, `is_add`, `creat_time`, `update_time`, `delete_flg`) VALUES
(19, 'æµ‹è¯•å•†å“1', 18, 100, 50, 10, '&lt;p&gt;æµ‹è¯•å•†å“1è¯´æ˜Ž&lt;/p&gt;', 1, 1395578769, 1395578769, 0),
(22, 'æµ‹è¯•å•†å“3', 19, 250, 100, 10, '&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;æµ‹è¯•å•†å“3&lt;br/&gt;&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;', 1, 1395650387, 1396173923, 0),
(21, 'æµ‹è¯•å•†å“2', 13, 200, 150, 20, '&lt;p&gt;\r\n				&lt;p&gt;æµ‹è¯•å•†å“2&lt;/p&gt;			&lt;/p&gt;', 1, 1395648844, 1395648844, 0),
(31, 'æµ‹è¯•å•†å“4', 19, 400, 200, 10, '&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;				&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;æµ‹è¯•å•†å“4&lt;br/&gt;&lt;/p&gt;&lt;p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;', 1, 1395805941, 1396173901, 0),
(34, 'æµ‹è¯•å•†å“5', 0, 100, 200, 10, '&lt;p&gt;				&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;æµ‹è¯•å•†å“5&lt;/p&gt;&lt;p&gt;			&lt;/p&gt;', 1, 1396089508, 1396172936, 0);

-- --------------------------------------------------------

--
-- 表的结构 `product_producttype`
--

CREATE TABLE IF NOT EXISTS `product_producttype` (
  `ppt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ptype_id` int(11) NOT NULL COMMENT '商品类型id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`ppt_id`),
  KEY `pt_id` (`ptype_id`),
  KEY `p_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `product_producttype`
--

INSERT INTO `product_producttype` (`ppt_id`, `ptype_id`, `product_id`) VALUES
(7, 23, 34),
(8, 24, 34),
(10, 23, 31),
(11, 23, 22),
(12, 22, 22);

-- --------------------------------------------------------

--
-- 表的结构 `product_type`
--

CREATE TABLE IF NOT EXISTS `product_type` (
  `ptype_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '分类名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  PRIMARY KEY (`ptype_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品分类表' AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `product_type`
--

INSERT INTO `product_type` (`ptype_id`, `name`, `parent_id`) VALUES
(13, 'ç¬¬ä¸€ä¸ªåˆ†ç±»', 0),
(18, 'ç¬¬äºŒä¸ªåˆ†ç±»', 0),
(19, 'ç¬¬ä¸‰ä¸ªåˆ†ç±»', 0),
(23, 'ç³»åˆ—2', 13),
(22, 'ç³»åˆ—1', 18),
(24, 'ç¬¬å››ä¸ªåˆ†ç±»', 0);

-- --------------------------------------------------------

--
-- 表的结构 `query`
--

CREATE TABLE IF NOT EXISTS `query` (
  `q_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `q_name` varchar(50) DEFAULT NULL COMMENT '询问人',
  `q_tel` varchar(20) DEFAULT NULL COMMENT '询问人电话',
  `q_email` varchar(25) DEFAULT NULL COMMENT '询问人邮件',
  `q_title` varchar(50) NOT NULL COMMENT '询问标题',
  `q_body` text NOT NULL COMMENT '询问内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`q_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='咨询表' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `query`
--

INSERT INTO `query` (`q_id`, `q_name`, `q_tel`, `q_email`, `q_title`, `q_body`, `create_time`) VALUES
(3, 'eded', '2321321', 'qw@dd.com', 'feferf', 'wfwefwef', 1396441573);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_tme` int(11) NOT NULL COMMENT '修改时间',
  `delete_fig` int(11) DEFAULT '0' COMMENT '是否删除 0:否，1：是',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `create_time`, `update_tme`, `delete_fig`) VALUES
(1, 'aa', 1396421573, 1396421573, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
