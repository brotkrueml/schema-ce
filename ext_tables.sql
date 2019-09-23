CREATE TABLE tx_schemarecords_domain_model_type (
	schema_type varchar(255) DEFAULT '' NOT NULL,
	schema_id varchar(255) DEFAULT '' NOT NULL,
	webpage_mainentity tinyint(1) unsigned DEFAULT '0' NOT NULL,
	properties int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_schemarecords_domain_model_property (
	parent int(11) unsigned DEFAULT '0' NOT NULL,

	variant smallint(5) unsigned DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	single_value varchar(255) DEFAULT '' NOT NULL,
	url varchar(1024) DEFAULT '' NOT NULL,
	image int(11) unsigned DEFAULT '0' NOT NULL,
	flag tinyint(1) unsigned DEFAULT '0' NOT NULL,
	type_reference int(11) unsigned DEFAULT '0',
	reference_only tinyint(1) unsigned DEFAULT '0' NOT NULL,
	timestamp int(11) unsigned DEFAULT '0' NOT NULL,

	KEY parent_type (parent)
);
