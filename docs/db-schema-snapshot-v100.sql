drop table appointment;
create table appointment
	(
	user_id int NOT NULL,
	queue_id int NOT NULL,
	position int NOT NULL,
	reference varchar(255) NOT NULL
	);
	
desc appointment;

drop table migrations;
create table migrations
	(
	migration  varchar(255) NOT NULL,
	batch int NOT NULL
	);
	
desc migrations;

drop table oauth_access_tokens;
create table oauth_access_tokens
	(
	id  varchar(100) NOT NULL,
	user_id  int ,
	client_id  int NOT NULL,
	name  varchar(255) ,
	scopes  text ,
	revoked  tinyint NOT NULL,
	created_at  TIMESTAMP NULL,
	updated_at  TIMESTAMP NULL,
	expires_at  datetime ,
	PRIMARY KEY(id),
	KEY(user_id)
	);	
desc oauth_access_tokens;

drop table oauth_auth_codes;
create table oauth_auth_codes
	(
	id  varchar(100) NOT NULL,
	user_id  int NOT NULL,
	client_id  int NOT NULL,
	scopes  text NULL,
	revoked  tinyint NOT NULL,
	expires_at  datetime ,
	PRIMARY KEY(id)
	);	
desc oauth_auth_codes;


drop table oauth_clients;
create table oauth_clients
	(
	id  int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_id  int NULL,
	name  varchar(255) NOT  NULL,
	secret  varchar(100) NOT NULL,
	redirect  text NOT NULL,
	personal_access_client  tinyint(1) NOT NULL,
	password_client  tinyint(1) NOT NULL,
	revoked  tinyint(1) NOT NULL,
	created_at  TIMESTAMP NULL,
	updated_at  TIMESTAMP NULL,
	PRIMARY KEY(id),
	KEY(user_id)
	);	
desc oauth_clients;


drop table oauth_personal_access_clients;
create table oauth_personal_access_clients
	(
	id  int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	client_id  int NOT NULL,
	created_at  TIMESTAMP NULL,
	updated_at  TIMESTAMP NULL,
	PRIMARY KEY(id),
	KEY(client_id)
	);	
desc oauth_personal_access_clients;

drop table oauth_refresh_tokens;
create table oauth_refresh_tokens
	(
	id  varchar(100) NOT NULL ,
	access_token_id  varchar(100) NOT NULL,
	revoked  tinyint(1) NOT NULL,
	expires_at  datetime ,
	PRIMARY KEY(id),
	KEY(access_token_id)
	);	
desc oauth_refresh_tokens;

drop table users;
create table users
	(
	id  int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	name  varchar(255) NOT NULL,
	email  varchar(255) NOT NULL,
	password  varchar(255) NOT NULL,
	remember_token  varchar(100)  NULL,
	created_at  TIMESTAMP NULL,
	updated_at  TIMESTAMP  NULL,
	PRIMARY KEY(id),
	UNIQUE(email)
	);	
desc users;


drop table password_resets;
create table password_resets
	(
	email  varchar(255) NOT NULL,
	token  varchar(255) NOT NULL,
	created_at  TIMESTAMP NULL,
	KEY(email),
	KEY(token)
	);	
desc password_resets;

drop table queue_admins;
create table queue_admins
	(
	id  int(10)  UNSIGNED NOT NULL ,
	user_id int(10) UNSIGNED  NOT NULL ,
	PRIMARY KEY(id,user_id)
	);	
desc queue_admins;

drop table queues;
create table queues
	(
	id  int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	name  varchar(255) NOT NULL,
	current_position  int(10) UNSIGNED NOT NULL DEFAULT 0,
	next_available_slot  int(10) UNSIGNED NOT NULL DEFAULT 1,
	accepting_appointments  int(10) UNSIGNED NOT NULL DEFAULT 0,
	initial_free_slots  int(10) UNSIGNED NOT NULL DEFAULT 0,
	recurring_free_slot  int(10) UNSIGNED NOT NULL DEFAULT 0,
	start_time  datetime NULL,
	update_time  datetime  NULL,
	PRIMARY KEY(id)
	);	
desc queues;

