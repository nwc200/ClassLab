drop database if exists enrolment;
create database enrolment;
use enrolment;

CREATE TABLE IF NOT EXISTS `enrolment` (
    `enrolmentID` integer auto_increment NOT NULL,
    `enrolmentStatus` varchar(10) NOT NULL,
    `selfEnrol` boolean NOT NULL,
    `dateTimeEnrolled` timestamp NOT NULL,
    `courseID` integer NOT NULL,
    `classID` integer NOT NULL,
    `completed` boolean NOT NULL,
    `userName` varchar(50) NOT NULL,
    constraint enrolment_pk primary key (enrolmentID)
);

/* Adding a foreign key userName referencing to User database*/
ALTER TABLE enrolment.enrolment
ADD foreign key enrolment_fk1(userName)
REFERENCES user.user(userName);

/* Adding a foreign key courseID & classID referencing to Class database*/
ALTER TABLE enrolment.enrolment
ADD foreign key enrolment_fk2(courseID, classID)
REFERENCES class.class(courseID, classID);

/* Enrolment */
insert into enrolment
values (1, "None", false, "2021-11-22 00:00:01", 1, 1, false, "Yu Hao");

insert into enrolment
values (2, "None", false, "2021-03-22 00:00:01", 3, 3, false, "Yu Hao");

insert into enrolment
values (3, "Approved", true, "2021-03-22 00:00:01", 1, 1, false, "Mei Lan");
