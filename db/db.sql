/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50535
Source Host           : localhost:3306
Source Database       : shoppingcart

Target Server Type    : MYSQL
Target Server Version : 50535
File Encoding         : 65001

Date: 2014-04-05 14:50:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `ad_product`
-- ----------------------------
DROP TABLE IF EXISTS `ad_product`;
CREATE TABLE `ad_product` (
  `ad_product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ad_id` int(11) NOT NULL COMMENT '广告位id FK',
  `product_id` int(11) NOT NULL COMMENT '商品id FK',
  PRIMARY KEY (`ad_product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ad_product
-- ----------------------------
INSERT INTO `ad_product` VALUES ('8', '2', '32');
INSERT INTO `ad_product` VALUES ('9', '3', '32');
INSERT INTO `ad_product` VALUES ('16', '1', '34');
INSERT INTO `ad_product` VALUES ('17', '2', '34');
INSERT INTO `ad_product` VALUES ('34', '1', '31');
INSERT INTO `ad_product` VALUES ('35', '3', '31');
INSERT INTO `ad_product` VALUES ('36', '2', '22');

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL COMMENT '管理员名',
  `pwd` varchar(50) NOT NULL COMMENT '密码',
  `leve` int(1) NOT NULL DEFAULT '1' COMMENT '1：管理员，2：超级管理员',
  `delete_flg` int(1) NOT NULL DEFAULT '0' COMMENT '0：未删除，1：已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', '4297f44b13955235245b2497399d7a93', '2', '0');

-- ----------------------------
-- Table structure for `advertisement`
-- ----------------------------
DROP TABLE IF EXISTS `advertisement`;
CREATE TABLE `advertisement` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ad_name` varchar(20) NOT NULL,
  `ad_type` varchar(20) NOT NULL COMMENT '广告类型',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of advertisement
-- ----------------------------
INSERT INTO `advertisement` VALUES ('1', 'é¦–é¡µè¼ªæ’­', 'index_top');
INSERT INTO `advertisement` VALUES ('2', 'å·¦ä¾§æ ', 'left');
INSERT INTO `advertisement` VALUES ('3', 'å³ä¾§æ ', 'right');

-- ----------------------------
-- Table structure for `cart`
-- ----------------------------
DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) NOT NULL COMMENT '用户表id FK',
  `total` int(11) NOT NULL COMMENT '总金额',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车表';

-- ----------------------------
-- Records of cart
-- ----------------------------

-- ----------------------------
-- Table structure for `cart_product`
-- ----------------------------
DROP TABLE IF EXISTS `cart_product`;
CREATE TABLE `cart_product` (
  `cproduct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `product_id` int(11) NOT NULL COMMENT '商品表id FK',
  PRIMARY KEY (`cproduct_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车和商品关联表';

-- ----------------------------
-- Records of cart_product
-- ----------------------------

-- ----------------------------
-- Table structure for `forum`
-- ----------------------------
DROP TABLE IF EXISTS `forum`;
CREATE TABLE `forum` (
  `forum_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `forum_name` varchar(50) NOT NULL COMMENT '版块名',
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forum
-- ----------------------------
INSERT INTO `forum` VALUES ('1', 'æ–°å•†å“');
INSERT INTO `forum` VALUES ('2', 'æŽ¨èå•†å“');

-- ----------------------------
-- Table structure for `forum_product`
-- ----------------------------
DROP TABLE IF EXISTS `forum_product`;
CREATE TABLE `forum_product` (
  `fp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `forum_id` int(11) NOT NULL COMMENT '版块id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`fp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of forum_product
-- ----------------------------
INSERT INTO `forum_product` VALUES ('1', '1', '22');
INSERT INTO `forum_product` VALUES ('2', '1', '31');
INSERT INTO `forum_product` VALUES ('5', '2', '34');
INSERT INTO `forum_product` VALUES ('6', '2', '31');

-- ----------------------------
-- Table structure for `image_product`
-- ----------------------------
DROP TABLE IF EXISTS `image_product`;
CREATE TABLE `image_product` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `product_id` int(11) NOT NULL COMMENT '商品表id',
  `image_path` varchar(50) NOT NULL COMMENT '图片路径',
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of image_product
-- ----------------------------
INSERT INTO `image_product` VALUES ('2', '19', 'upload/1395578769350170.jpg');
INSERT INTO `image_product` VALUES ('3', '19', 'upload/1395647496410019.jpg');
INSERT INTO `image_product` VALUES ('4', '21', 'upload/1395649323564312.jpg');
INSERT INTO `image_product` VALUES ('5', '21', 'upload/1395649323778577.jpg');
INSERT INTO `image_product` VALUES ('6', '21', 'upload/1395649323984240.jpg');
INSERT INTO `image_product` VALUES ('7', '22', 'upload/1395650387405995.jpg');
INSERT INTO `image_product` VALUES ('8', '31', 'upload/1395805941477787.jpg');
INSERT INTO `image_product` VALUES ('9', '31', 'upload/1395805941756590.jpg');
INSERT INTO `image_product` VALUES ('10', '34', 'upload/1396089508437477.jpg');

-- ----------------------------
-- Table structure for `order`
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_id` int(11) NOT NULL COMMENT '用户表id FK',
  `total` int(11) NOT NULL COMMENT '总金额',
  `status` int(11) DEFAULT '0' COMMENT '状态 0:未付款 1:已付款',
  `creat_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '修改时间',
  `delete_flg` int(11) DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单表';

-- ----------------------------
-- Records of order
-- ----------------------------
INSERT INTO `order` VALUES ('1', '1', '200', '1', '1396421573', '1396425812', '0');

-- ----------------------------
-- Table structure for `order_product`
-- ----------------------------
DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product` (
  `oproduct_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_id` int(11) NOT NULL COMMENT '订单表id FK',
  `product_id` int(11) NOT NULL COMMENT '商品表id FK',
  PRIMARY KEY (`oproduct_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='订单和商品关联表';

-- ----------------------------
-- Records of order_product
-- ----------------------------
INSERT INTO `order_product` VALUES ('1', '1', '19');
INSERT INTO `order_product` VALUES ('2', '1', '22');
INSERT INTO `order_product` VALUES ('3', '1', '21');

-- ----------------------------
-- Table structure for `page`
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `page_title` varchar(50) NOT NULL COMMENT '页面标题',
  `page_body` text NOT NULL COMMENT '页面内容',
  PRIMARY KEY (`page_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page
-- ----------------------------

-- ----------------------------
-- Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
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
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('19', 'æµ‹è¯•å•†å“1', '18', '100', '50', '10', '&lt;p&gt;æµ‹è¯•å•†å“1è¯´æ˜Ž&lt;/p&gt;', '1', '1395578769', '1395578769', '0');
INSERT INTO `product` VALUES ('22', 'æµ‹è¯•å•†å“3', '19', '250', '100', '10', '&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;æµ‹è¯•å•†å“3&lt;br/&gt;&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;', '1', '1395650387', '1396173923', '0');
INSERT INTO `product` VALUES ('21', 'æµ‹è¯•å•†å“2', '13', '200', '150', '20', '&lt;p&gt;\r\n				&lt;p&gt;æµ‹è¯•å•†å“2&lt;/p&gt;			&lt;/p&gt;', '1', '1395648844', '1395648844', '0');
INSERT INTO `product` VALUES ('31', 'æµ‹è¯•å•†å“4', '19', '400', '200', '10', '&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;\r\n				&lt;p&gt;				&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;æµ‹è¯•å•†å“4&lt;br/&gt;&lt;/p&gt;&lt;p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;			&lt;/p&gt;', '1', '1395805941', '1396173901', '0');
INSERT INTO `product` VALUES ('34', 'æµ‹è¯•å•†å“5', '0', '100', '200', '10', '&lt;p&gt;				&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;&lt;p&gt;æµ‹è¯•å•†å“5&lt;/p&gt;&lt;p&gt;			&lt;/p&gt;', '1', '1396089508', '1396172936', '0');

-- ----------------------------
-- Table structure for `product_productType`
-- ----------------------------
DROP TABLE IF EXISTS `product_productType`;
CREATE TABLE `product_productType` (
  `ppt_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `ptype_id` int(11) NOT NULL COMMENT '商品类型id',
  `product_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`ppt_id`),
  KEY `pt_id` (`ptype_id`),
  KEY `p_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_productType
-- ----------------------------
INSERT INTO `product_productType` VALUES ('7', '23', '34');
INSERT INTO `product_productType` VALUES ('8', '24', '34');
INSERT INTO `product_productType` VALUES ('10', '23', '31');
INSERT INTO `product_productType` VALUES ('11', '23', '22');
INSERT INTO `product_productType` VALUES ('12', '22', '22');

-- ----------------------------
-- Table structure for `product_type`
-- ----------------------------
DROP TABLE IF EXISTS `product_type`;
CREATE TABLE `product_type` (
  `ptype_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `name` varchar(50) NOT NULL COMMENT '分类名',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父id',
  PRIMARY KEY (`ptype_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='商品分类表';

-- ----------------------------
-- Records of product_type
-- ----------------------------
INSERT INTO `product_type` VALUES ('13', 'ç¬¬ä¸€ä¸ªåˆ†ç±»', '0');
INSERT INTO `product_type` VALUES ('18', 'ç¬¬äºŒä¸ªåˆ†ç±»', '0');
INSERT INTO `product_type` VALUES ('19', 'ç¬¬ä¸‰ä¸ªåˆ†ç±»', '0');
INSERT INTO `product_type` VALUES ('23', 'ç³»åˆ—2', '13');
INSERT INTO `product_type` VALUES ('22', 'ç³»åˆ—1', '18');
INSERT INTO `product_type` VALUES ('24', 'ç¬¬å››ä¸ªåˆ†ç±»', '0');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_tme` int(11) NOT NULL COMMENT '修改时间',
  `delete_fig` int(11) DEFAULT '0' COMMENT '是否删除 0:否，1：是',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'aa', '1396421573', '1396421573', '0');
