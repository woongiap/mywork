create database ngiapco_ad DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

use ngiapco_ad; 
 
create table if not exists k_category (
category_id smallint not null primary key,
category_name varchar(30) not null,
num_post int not null default 0,
table_name char(8) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
insert into k_category (category_id, category_name, table_name) values (180, 'Food & Drink', 'k_ft_180');
insert into k_category (category_id, category_name, table_name) values (90, 'Car & Vehicle', 'k_ft_090');
insert into k_category (category_id, category_name, table_name) values (240, 'Housing & Apartment', 'k_ft_240');
insert into k_category (category_id, category_name, table_name) values (300, 'Job', 'k_ft_300');
insert into k_category (category_id, category_name, table_name) values (360, 'Land & Factory', 'k_ft_360');
insert into k_category (category_id, category_name, table_name) values (480, 'Product & Service', 'k_ft_480');
insert into k_category (category_id, category_name, table_name) values (540, 'Recreation & Travel', 'k_ft_540');
insert into k_category (category_id, category_name, table_name) values (570, 'Shop & Office', 'k_ft_570');
insert into k_category (category_id, category_name, table_name) values (999, '-- Any --', 'k_ft_999'); 
  
-- select * from k_category where table_name = 'k_ft_180'
create table if not exists k_ft_180 (
post_id bigint not null primary key,
title varchar(80) not null,
description text not null,
location varchar(100) not null
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; -- FULLTEXT works on MyISAM only 
create fulltext index idx_ft_title on k_ft_180 (title);
create fulltext index idx_ft_desc on k_ft_180 (description);
create fulltext index idx_ft_location on k_ft_180 (location); 
  
CREATE TABLE if not exists k_ft_090 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
CREATE TABLE if not exists k_ft_240 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
CREATE TABLE if not exists k_ft_300 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
CREATE TABLE if not exists k_ft_360 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 

CREATE TABLE if not exists k_ft_480 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 

CREATE TABLE if not exists k_ft_540 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 

CREATE TABLE if not exists k_ft_570 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 

CREATE TABLE if not exists k_ft_999 (
post_id bigint(20) NOT NULL,
title varchar(80) NOT NULL,
description text NOT NULL,
location varchar(100) NOT NULL,
PRIMARY KEY (`post_id`),
FULLTEXT KEY `idx_ft_desc` (`description`),
FULLTEXT KEY `idx_ft_title` (`title`),
FULLTEXT KEY `idx_ft_location` (`location`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 

create table if not exists k_post (
post_id bigint not null primary key,
title varchar(80) not null,
html_desc text not null,
create_user_id int not null,
create_date datetime not null,
location varchar(60) not null,
div_code char(6) not null,
category_id smallint not null,
num_comment smallint not null default 0,
num_like smallint not null default 0,
num_view int not null default 0,
pstate tinyint not null default 1,
expiry_date datetime,
last_modify_date datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

create table if not exists k_post_admin (
post_id bigint not null primary key,
vstate tinyint not null default 0,
adm_date datetime,
adm_userid int
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

create table if not exists k_user (
user_id int not null primary key,
user_name varchar(50) not null,
user_passwd char(40) not null,
display_name varchar(50),
gender char(1),
birth_date date,
alt_email varchar(50),
signup_date datetime not null,
num_post smallint not null default 0,
num_comment smallint not null default 0,
last_key char(32),
ustate tinyint not null default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
create unique index idx_uname on k_user (user_name); 
  
create table if not exists k_post_history (
upid bigint not null auto_increment primary key,
uptype tinyint not null,
pid bigint not null,
uid int,
remote_ip varchar(15) not null,
session_id char(30),
user_agent varchar(255),
http_referer varchar(255),
do_date datetime not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
create table if not exists k_userpost (
post_id bigint not null,
uptype tinyint not null,
user_id int not null,

primary key (post_id, user_id, uptype) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
ALTER TABLE k_userpost ADD constraint fk_user_userpost FOREIGN KEY (user_id) REFERENCES k_user (user_id);
ALTER TABLE k_userpost ADD constraint fk_post_userpost FOREIGN KEY (post_id) REFERENCES k_post (post_id); 
  
create table if not exists k_state (
div_code char(6) not null primary key,
state_name varchar(30) not null
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
  
-- iso 3166-2:MY
insert into k_state (div_code, state_name) values ('MY-01','Johor');
insert into k_state (div_code, state_name) values ('MY-02','Kedah');
insert into k_state (div_code, state_name) values ('MY-03','Kelantan');
insert into k_state (div_code, state_name) values ('MY-04','Melaka');
insert into k_state (div_code, state_name) values ('MY-05','Negeri Sembilan');
insert into k_state (div_code, state_name) values ('MY-06','Pahang');
insert into k_state (div_code, state_name) values ('MY-07','Pulau Pinang');
insert into k_state (div_code, state_name) values ('MY-08','Perak');
insert into k_state (div_code, state_name) values ('MY-09','Perlis');
insert into k_state (div_code, state_name) values ('MY-10','Selangor');
insert into k_state (div_code, state_name) values ('MY-11','Terengganu');
insert into k_state (div_code, state_name) values ('MY-12','Sabah');
insert into k_state (div_code, state_name) values ('MY-13','Sarawak');
insert into k_state (div_code, state_name) values ('MY-14','Kuala Lumpur');
insert into k_state (div_code, state_name) values ('MY-15','Labuan');
insert into k_state (div_code, state_name) values ('MY-16','Putrajaya');
 
create table if not exists k_comment (
comment_id bigint not null auto_increment primary key,
comment_text varchar(255) not null,
comment_date datetime not null,
post_id bigint not null,
comment_username varchar(50), -- NULL means anonymous
user_id int
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; 
 
create table if not exists k_feedback (
fb_id bigint not null auto_increment primary key,
sender_name varchar(50),
sender_email varchar(50),
fb_type tinyint not null default 1,
fb_msg varchar(255),
fb_date datetime not null,
post_id bigint,
rstate tinyint not null default 1,
adm_userid int,
adm_date datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

create table if not exists k_faq (
id smallint auto_increment primary key,
question varchar(100) not null,
answer varchar(255) not null,
dorder smallint not null default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

