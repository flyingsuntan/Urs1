create database urs;
use urs;
set names utf8;

drop table if exists urs_user;
create table urs_user
(
	id mediumint unsigned not null auto_increment comment '用户Id',
	user_name varchar(150) not null comment '用户名称',
	password varchar(150) not null comment '用户密码',
	user_pic varchar(150)  comment '用户头像',
	primary key (id),
)engine=InnoDB default charset=utf8 comment '用户表';


drop table if exists urs_friends;
create table urs_friends
(
	id mediumint unsigned not null auto_increment comment 'Id',
	mid mediumint unsigned not null  comment '用户Id',
	fid mediumint unsigned not null  comment '朋友Id',
	is_allow enum(`yes`,`no`) not null default 'no' comment '是否同意添加',
	is_delete enum(`yes`,`no`) not null default 'no' comment '是否删除',
	primary key (id),
)engine=InnoDB default charset=utf8 comment '好友表';



drop table if exists urs_message;
create table urs_message
(
	id mediumint unsigned not null auto_increment comment 'Id',
	mid mediumint unsigned not null  comment '用户Id',
	fid mediumint unsigned not null  comment '朋友Id',
	message varchar (150) comment '信息',
	status enum('yes','no') default 'no' comment '是否读取',
	sendtime varchar (45) comment '发送时间',
	readtime varchar (45) comment '读取时间',
	primary key (id),
)engine=InnoDB default charset=utf8 comment '信息表';


create table urs_system_message
(
	id mediumint unsigned not null auto_increment comment 'Id',
	title varchar (150) comment '标题',
	content text comment '内容',
	sendtime varchar (45) not null comment '发送时间',
	Clicks int not null default 0 comment '点击量',
	primary key (id),
)engine=InnoDB default charset=utf8 comment '系统信息表';




create table urs_user_system_message
(
	id mediumint unsigned not null auto_increment comment 'Id',
	uid mediumint unsigned not null comment '用户id',
	mid mediumint unsigned not null comment '通知id',
	primary key (id),
)engine=InnoDB default charset=utf8 comment '用户已读通知表';