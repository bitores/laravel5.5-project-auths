<?php
// 1000 需求未提交（已保存/未提交）
// 1001 需求审核中（已提交，禁止编辑）
// 1002 需求审核未通过（开放编辑）
// 1003 需求审核已通过（开放编辑）
// 1004 已删除（禁止编辑）
// 1005 等待接单（新需求/已取消订单）
// 1006 模型制作中（接单成功/开始制作）
// 1007 模型审核中（模型已上传，禁止上传）
// 1008 模型审核未通过（开放上传）
// 1009 模型审核已通过（禁止上传，制作已完成）
// 1010 模型已入库（完成制作） 

//  所有产品状态码
define('PRODUCT_DEMAND_UNSUBMIT', 1000);
define('PRODUCT_DEMAND_UNDER_REVIEW', 1001);
define('PRODUCT_DEMAND_NOT_PASSED', 1002);
define('PRODUCT_DEMAND_PASSED', 1003);
define('PRODUCT_DEMAND_DELETED', 1004);
define('PRODUCT_WAITING_ORDERS', 1005);
define('PRODUCT_MODEL_MAKING', 1006);
define('PRODUCT_MODEL_REVIEW', 1007);
define('PRODUCT_MODEL_NOT_PASSED', 1008);
define('PRODUCT_MODEL_PASSED', 1009);
define('PRODUCT_MODEL_STORED', 1010);


// 所有操作返回状态码
