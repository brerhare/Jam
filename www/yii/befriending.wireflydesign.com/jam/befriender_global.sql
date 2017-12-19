select befriender.id, befriender.global_id, global.forename, global.surname  from befriender inner join global on befriender.global_id = global.id;
