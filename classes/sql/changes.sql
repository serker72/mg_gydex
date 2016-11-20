/*  ƒобавление полнотекстового индекса д€л поиска  */
ALTER TABLE `link_lang` ADD FULLTEXT( `name`, `short_name`, `small_txt`);
ALTER TABLE `news_lang` ADD FULLTEXT( `name`, `small_txt`, `big_txt`, `title`);

/*  ƒобавление отсутствующих полей дл€ товаров  */
ALTER TABLE `price_item` ADD `external_id` BIGINT UNSIGNED NULL DEFAULT '0';
ALTER TABLE `price_item` ADD `is_main` TINYINT UNSIGNED NULL DEFAULT '0';

ALTER TABLE `price_lang` ADD `txt_cost` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_video` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_features` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_attributes` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_tools` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_materials` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_add_eq` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_service` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;
ALTER TABLE `price_lang` ADD `txt_presentation` TEXT CHARACTER SET cp1251 COLLATE cp1251_general_ci NULL;

UPDATE `allmenu` SET `is_price`=1, `is_links`=0 WHERE `parent_id`=3;
