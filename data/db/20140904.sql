-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2014-09-04 11:27:06
-- 服务器版本: 5.5.38-0ubuntu0.14.04.1
-- PHP 版本: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
(1, '首页顶部广告位', 'index_top'),
(2, '左侧广告位', 'left'),
(3, '右侧广告位', 'right');

-- --------------------------------------------------------

--
-- 表的结构 `ad_product`
--

CREATE TABLE IF NOT EXISTS `ad_product` (
  `ad_product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ad_id` int(11) NOT NULL COMMENT '广告位id FK',
  `product_id` int(11) NOT NULL COMMENT '商品id FK',
  PRIMARY KEY (`ad_product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

--
-- 转存表中的数据 `ad_product`
--

INSERT INTO `ad_product` (`ad_product_id`, `ad_id`, `product_id`) VALUES
(50, 1, 42),
(51, 2, 42),
(52, 3, 42),
(55, 2, 43),
(56, 3, 44),
(57, 2, 45),
(58, 2, 46),
(65, 1, 37),
(66, 2, 37),
(67, 3, 37);

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
-- 表的结构 `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `currency` varchar(32) NOT NULL,
  `currency_code` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- 转存表中的数据 `country`
--

INSERT INTO `country` (`id`, `name`, `currency`, `currency_code`) VALUES
(1, 'Afghanistan', 'Afghani', 'AFN'),
(2, 'Åland Islands', 'Euro', 'EUR'),
(3, 'Albania', 'Lek', 'ALL'),
(4, 'Algeria', 'Algerian Dinar', 'DZD'),
(5, 'American Samoa', 'US Dollar', 'USD'),
(6, 'Andorra', 'Euro', 'EUR'),
(7, 'Angola', 'Kwanza', 'AOA'),
(8, 'Anguilla', 'East Caribbean Dollar', 'XCD'),
(9, 'Antarctica', 'No universal currency', ''),
(10, 'Antigua and Barbuda', 'East Caribbean Dollar', 'XCD'),
(11, 'Argentina', 'Argentine Peso', 'ARS'),
(12, 'Armenia', 'Armenian Dram', 'AMD'),
(13, 'Aruba', 'Aruban Florin', 'AWG'),
(14, 'Australia', 'Australian Dollar', 'AUD'),
(15, 'Austria', 'Euro', 'EUR'),
(16, 'Azerbaijan', 'Azerbaijanian Manat', 'AZN'),
(17, 'Bahamas', 'Bahamian Dollar', 'BSD'),
(18, 'Bahrain', 'Bahraini Dinar', 'BHD'),
(19, 'Bangladesh', 'Taka', 'BDT'),
(20, 'Barbados', 'Barbados Dollar', 'BBD'),
(21, 'Belarus', 'Belarussian Ruble', 'BYR'),
(22, 'Belgium', 'Euro', 'EUR'),
(23, 'Belize', 'Belize Dollar', 'BZD'),
(24, 'Benin', 'CFA Franc BCEAO †', 'XOF'),
(25, 'Bermuda', 'Bermudian Dollar', 'BMD'),
(26, 'Bhutan', 'Ngultrum', 'BTN'),
(27, 'Bolivia, Plurinational State of', 'Boliviano', 'BOB'),
(28, 'Bonaire, Sint Eustatius and Saba', 'US Dollar', 'USD'),
(29, 'Bosnia and Herzegovina', 'Convertible Marks', 'BAM'),
(30, 'Botswana', 'Pula', 'BWP');

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
(1, '第一个版块'),
(2, '第二个版块');

-- --------------------------------------------------------

--
-- 表的结构 `forum_product`
--

CREATE TABLE IF NOT EXISTS `forum_product` (
  `fp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `forum_id` int(11) NOT NULL COMMENT '版块id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`fp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `forum_product`
--

INSERT INTO `forum_product` (`fp_id`, `forum_id`, `product_id`) VALUES
(1, 1, 37),
(2, 2, 37),
(3, 2, 42);

-- --------------------------------------------------------

--
-- 表的结构 `image_product`
--

CREATE TABLE IF NOT EXISTS `image_product` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `product_id` int(11) NOT NULL COMMENT '商品表id',
  `image_path` varchar(50) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `image_product`
--

INSERT INTO `image_product` (`image_id`, `product_id`, `image_path`) VALUES
(13, 37, 'upload/1408951362249766.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `news_title` varchar(100) NOT NULL COMMENT '新闻标题',
  `news_body` text NOT NULL COMMENT '新闻内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `delete_flg` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- 转存表中的数据 `news`
--

INSERT INTO `news` (`news_id`, `news_title`, `news_body`, `create_time`, `update_time`, `delete_flg`) VALUES
(4, 'afafafggggggggggg', '<p>				</p><p>sdfsdfdsfdsf<br/></p><p>sfsdfsdfsdf<br/></p><p>gggggggggggg<br/></p><p>asfasaaf</p><p>sssssssssss<br/></p><p>   			</p><p>   			</p>', 1409306168, 1409306197, 0),
(6, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(8, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(9, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(10, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(11, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(12, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(13, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(14, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(15, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(16, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(17, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(18, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(19, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(20, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(21, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(22, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(23, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(24, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(25, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(26, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(27, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0),
(30, 'j', '<p>				</p><p>gggggggggggg<br/></p><p>asfasaaf<br/></p>', 0, 1409305938, 0);

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) NOT NULL COMMENT '用户表id FK',
  `total` int(11) NOT NULL COMMENT '总金额',
  `point` int(11) NOT NULL DEFAULT '0' COMMENT '点数',
  `status` int(11) DEFAULT '0' COMMENT '状态 0:未付款 1:已付款',
  `creat_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `delete_flg` int(11) DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`order_id`, `user_id`, `total`, `point`, `status`, `creat_time`, `update_time`, `delete_flg`) VALUES
(1, 1, 200, 100, 1, 1396421573, 1396425812, 0);

-- --------------------------------------------------------

--
-- 表的结构 `order_product`
--

CREATE TABLE IF NOT EXISTS `order_product` (
  `oproduct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) NOT NULL COMMENT '订单表id FK',
  `product_id` int(11) NOT NULL COMMENT '商品表id FK',
  `product_count` int(11) NOT NULL COMMENT '商品数量',
  PRIMARY KEY (`oproduct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单和商品关联表' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- 转存表中的数据 `page`
--

INSERT INTO `page` (`page_id`, `page_title`, `page_body`, `create_time`, `update_time`) VALUES
(4, '第一个页面', '								第一个页面内容', 1406624351, 1406624532),
(5, 'sgg', 'sggrgr', 1406624351, 1406624351),
(6, 'erter', 'erer', 1406624351, 1406624351),
(7, 'sgg', 'sggrgr', 1406624351, 1406624351),
(8, 'erter', 'erer', 1406624351, 1406624351),
(9, 'sgg', 'sggrgr', 1406624351, 1406624351),
(10, 'erter', 'erer', 1406624351, 1406624351),
(11, 'sgg', 'sggrgr', 1406624351, 1406624351),
(12, 'erter', 'erer', 1406624351, 1406624351),
(13, 'sgg', 'sggrgr', 1406624351, 1406624351),
(14, 'erter', 'erer', 1406624351, 1406624351),
(15, 'sgg', 'sggrgr', 1406624351, 1406624351),
(16, 'erter', 'erer', 1406624351, 1406624351),
(17, 'sgg', 'sggrgr', 1406624351, 1406624351),
(18, 'erter', 'erer', 1406624351, 1406624351),
(19, 'sgg', 'sggrgr', 1406624351, 1406624351),
(20, 'erter', 'erer', 1406624351, 1406624351),
(21, 'sgg', 'sggrgr', 1406624351, 1406624351),
(22, 'erter', 'erer', 1406624351, 1406624351),
(23, 'sgg', 'sggrgr', 1406624351, 1406624351),
(24, 'erter', 'erer', 1406624351, 1406624351),
(25, 'sgg', 'sggrgr', 1406624351, 1406624351),
(26, 'erter', 'erer', 1406624351, 1406624351),
(27, 'sgg', 'sggrgr', 1406624351, 1406624351),
(28, 'erter', 'erer', 1406624351, 1406624351),
(29, 'sgg', 'sggrgr', 1406624351, 1406624351),
(30, 'erter', 'erer', 1406624351, 1406624351),
(31, 'sgg', 'sggrgr', 1406624351, 1406624351),
(32, 'erter', 'erer', 1406624351, 1406624351),
(33, 'sgg', 'sggrgr', 1406624351, 1406624351),
(34, 'erter', 'erer', 1406624351, 1406624351),
(35, 'sgg', 'sggrgr', 1406624351, 1406624351),
(36, 'erter', 'erer', 1406624351, 1406624351),
(37, 'sgg', 'sggrgr', 1406624351, 1406624351),
(38, 'erter', 'erer', 1406624351, 1406624351),
(39, 'sgg', 'sggrgr', 1406624351, 1406624351),
(40, 'erter', 'erer', 1406624351, 1406624351);

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
  `point` int(11) NOT NULL DEFAULT '0',
  `description` text COMMENT '介绍',
  `is_add` int(1) NOT NULL DEFAULT '0' COMMENT '是否上架 0:未上架 1:已上架',
  `creat_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL,
  `delete_flg` int(11) DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品表' AUTO_INCREMENT=47 ;

--
-- 转存表中的数据 `product`
--

INSERT INTO `product` (`product_id`, `name`, `ptype_id`, `original_price`, `price`, `stock`, `point`, `description`, `is_add`, `creat_time`, `update_time`, `delete_flg`) VALUES
(45, 'xfxf', 0, 100, 2, 3, 1, '222', 1, 1408700133, 1408700133, 0),
(46, 'sgdsgds', 0, 2, 2, 3, 1, 'sdsd', 1, 1408700695, 1408700695, 0),
(44, 'jkj', 0, 23, 3, 2, 1, 'xcvc', 1, 1408699573, 1408699573, 0),
(37, '第一个商品2', 0, 200, 100, 10, 100, '																				第一个商品第一个商品		 &nbsp; &nbsp; &nbsp; &nbsp;		 &nbsp; &nbsp; &nbsp; &nbsp;		 &nbsp; &nbsp; &nbsp; &nbsp;		 &nbsp; &nbsp; &nbsp; &nbsp;		 &nbsp; &nbsp; &nbsp; &nbsp;', 1, 1406687556, 1408952849, 0),
(43, 'hhhhhhhh', 0, 100, 2, 3, 2, '								dsgdsgdsg		 &nbsp; &nbsp; &nbsp; &nbsp;		 &nbsp; &nbsp; &nbsp; &nbsp;', 1, 1408695739, 1408695808, 0),
(42, '第二个商品', 0, 100, 100, 10, 10, '第二个商品内容', 1, 1406879072, 1406879072, 0);

-- --------------------------------------------------------

--
-- 表的结构 `product_productType`
--

CREATE TABLE IF NOT EXISTS `product_productType` (
  `ppt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ptype_id` int(11) NOT NULL COMMENT '商品类型id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`ppt_id`),
  KEY `pt_id` (`ptype_id`),
  KEY `p_id` (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `product_productType`
--

INSERT INTO `product_productType` (`ppt_id`, `ptype_id`, `product_id`) VALUES
(10, 42, 42),
(12, 42, 43),
(13, 42, 44),
(14, 42, 45),
(15, 42, 46),
(25, 40, 37),
(26, 41, 37),
(27, 42, 37);

-- --------------------------------------------------------

--
-- 表的结构 `product_type`
--

CREATE TABLE IF NOT EXISTS `product_type` (
  `ptype_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '分类名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  PRIMARY KEY (`ptype_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品分类表' AUTO_INCREMENT=43 ;

--
-- 转存表中的数据 `product_type`
--

INSERT INTO `product_type` (`ptype_id`, `name`, `parent_id`) VALUES
(42, '第三个子分类', 39),
(41, '第二个子分类', 38),
(40, '第一个子分类', 37),
(39, '第三个分类', 0),
(38, '第二个分类', 0),
(37, '第一个分类', 0);

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
-- 表的结构 `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` char(32) NOT NULL DEFAULT '',
  `name` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
