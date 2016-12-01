select young_person.id, young_person.global_id, global.forename, global.surname  from young_person inner join global on young_person.global_id = global.id;
