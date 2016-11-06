/*  ƒобавление полнотекстового индекса д€л поиска  */
ALTER TABLE `link_lang` ADD FULLTEXT( `name`, `short_name`, `small_txt`);
ALTER TABLE `news_lang` ADD FULLTEXT( `name`, `small_txt`, `big_txt`, `title`);
