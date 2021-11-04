drop database if exists user;
create database user;
use user;

CREATE TABLE IF NOT EXISTS `user` (
    `userName` varchar(50) NOT NULL,    
    `name` varchar(150) NOT NULL,
    `emailAddr` varchar(150) NOT NULL, 
    `password` varchar(50) NOT NULL,
    `department` varchar(50) NOT NULL,
    `designation` varchar(50) NOT NULL, 
    `roles` varchar(50) NOT NULL,
    constraint user_pk primary key (userName)
);

CREATE TABLE IF NOT EXISTS `permissions` (
    `userName` varchar(50) NOT NULL,    
    `courseID` integer NOT NULL,
    `userType` varchar(50) NOT NULL,
    constraint permissions_pk primary key (userName, courseID)
);

/* Adding a foreign key userName referencing to Course database*/
ALTER TABLE user.permissions
ADD foreign key permissions_fk(courseID)
REFERENCES course.course(courseID);

/* Learner */
insert into user 
values ("Yu Hao", "Neo Yu Hao", "yuhaon@aio.com", "password", "Operations", "Roving Service Engineer - Junior", "Learner");

insert into user 
values ("Mei Lan", "Chew Mei Lan", "meilanc@aio.com", "password", "Operations", "Roving Service Engineer - Junior", "Learner");

insert into user 
values ("Kai Hao", "Tan Kai Hao", "kaihaot@aio.com", "password", "Operations", "Roving Service Engineer - Junior", "Learner");

insert into user 
values ("Mei Xin", "Chin Mei Xin", "meixinc@aio.com", "password", "Operations", "Roving Service Engineer - Junior", "Learner");

/* Trainer */
insert into user 
values ("Wei Cheng", "Ng Wei Cheng", "weichengng@aio.com", "password", "Operations", "Roving Service Engineer - Senior", "Trainer");

insert into user 
values ("Alexandra", "Alexandra Ni", "alexandran@aio.com", "password", "Operations", "Roving Service Engineer - Senior", "Trainer");

insert into user 
values ("Jun Xiang", "Teo Jun Xiang", "junxiangt@aio.com", "password", "Operations", "Repair Engineer - Senior", "Trainer");

/* Administrator */
insert into user 
values ("Xi Hwee", "Wee Xi Hwee", "xihweew@aio.com", "password", "HR and Admin", "Admin and Call Centre", "Administrator");

insert into user 
values ("Sean", "Sean Ong", "seano@aio.com", "password", "HR and Admin", "Admin and Call Centre", "Administrator");


/* Trainer #1 teaches course 1 & 3 */
insert into permissions
values ("Wei Cheng", 1, "Trainer");
insert into permissions
values ("Wei Cheng", 3, "Trainer");

/* Trainer #2 teaches course 1 but learner for course 3 */
insert into permissions
values ("Alexandra", 1, "Trainer");
insert into permissions
values ("Alexandra", 3, "Learner");

/* Adding Learner Data */
insert into permissions
values ("Yu Hao", 1, "Learner");
insert into permissions
values ("Yu Hao", 3, "Learner");
insert into permissions
values ("Yu Hao", 5, "Learner");

insert into permissions
values ("Mei Lan", 1, "Learner");
insert into permissions
values ("Mei Lan", 3, "Learner");
insert into permissions
values ("Mei Lan", 5, "Learner");

insert into permissions
values ("Kai Hao", 1, "Learner");
insert into permissions
values ("Kai Hao", 3, "Learner");
insert into permissions
values ("Kai Hao", 5, "Learner");

insert into permissions
values ("Mei Xin", 1, "Learner");
insert into permissions
values ("Mei Xin", 2, "Learner");
insert into permissions
values ("Mei Xin", 3, "Learner");
insert into permissions
values ("Mei Xin", 5, "Learner");




