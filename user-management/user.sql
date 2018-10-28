create table user (
  id int AUTO_INCREMENT   primary key,
  username   varchar(63)  not null,
  password   varchar(63)  not null,
  email      varchar(127) not null,
  created   datetime      not null
);
