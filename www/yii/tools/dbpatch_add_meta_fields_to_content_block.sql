ALTER TABLE content_block ADD meta_title TEXT AFTER home;
ALTER TABLE content_block ADD meta_description TEXT AFTER meta_title;
ALTER TABLE content_block ADD meta_keywords TEXT AFTER meta_description;
