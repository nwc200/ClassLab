drop database if exists quiz;
create database quiz;
use quiz;

CREATE TABLE IF NOT EXISTS `quiz` (
    `quizID` integer auto_increment NOT NULL,
    `classID` integer NOT NULL,
    `sectionNum` integer NOT NULL,
    `quizName` varchar(50) NOT NULL,
    `quizNum` integer NOT NULL,
    `quizDuration` integer NOT NULL,
    `type` varchar(50) NOT NULL,
    `passingMark` integer NOT NULL,
    constraint quiz_pk primary key (quizID)
);

/* Adding a foreign key classID & sectionNum referencing to Section database */
ALTER TABLE quiz.quiz
ADD foreign key quiz_fk(classID, sectionNum)
REFERENCES section.section(classID, sectionNum);

CREATE TABLE IF NOT EXISTS `quizQuestion` (
    `quizID` integer NOT NULL,
    `questionNum` integer NOT NULL,
    `question` varchar(200) NOT NULL,
    `questionType` varchar(10) NOT NULL,
    `marks` integer NOT NULL,
    constraint quizquestion_pk primary key (quizID, questionNum),
    constraint quizquestion_fk foreign key (quizID) references quiz(quizID)
);

CREATE TABLE IF NOT EXISTS `quizAnswer` (
    `answerNum` integer NOT NULL,
    `quizID` integer NOT NULL,
    `questionNum`integer NOT NULL,
    `answer` varchar(150) NOT NULL,
    `correct` boolean NOT NULL,
    constraint quizanswer_pk primary key (answerNum, quizID, questionNum),
    constraint quizanswer_fk foreign key (quizID, questionNum) references quizQuestion(quizID, questionNum)
);

CREATE TABLE IF NOT EXISTS `studentQuizAttempt` (
    `userName` varchar(50) NOT NULL,
    `quizID` integer NOT NULL,
    `attemptNo` integer NOT NULL,
    `passFail` boolean NOT NULL,
    constraint studentquizattempt_pk primary key (userName, quizID, attemptNo),
    constraint studentquizattempt_fk1 foreign key (quizID) references quiz(quizID)
);

/* Adding a foreign key userName referencing to User database */
ALTER TABLE quiz.studentQuizAttempt
ADD foreign key studentquizattempt_fk2(userName)
REFERENCES user.user(userName);

CREATE TABLE IF NOT EXISTS `studentQuizRecord` (
    `quizRecord` integer auto_increment NOT NULL,
    `userName` varchar(50) NOT NULL,
    `quizID` integer NOT NULL,
    `marks` integer NOT NULL,
    `attemptNo` integer NOT NULL,
    `questionNum` integer NOT NULL,
    `studentAnsNum` integer NOT NULL,
    constraint studentquizrecord_pk primary key (quizRecord),
    constraint studentquizrecord_fk1 foreign key (quizID, questionNum) references quizQuestion (quizID, questionNum),
    constraint studentquizrecord_fk2 foreign key (userName, quizID, attemptNo) references studentQuizAttempt(userName, quizID, attemptNo)
);

/* Quiz */
/* Ungraded */
insert into quiz
values (1, 1, 1, "Chapter 1 Quiz", 1, 20, "Ungraded", 0);


/* Graded */
insert into quiz
values (2, 1, 2, "End of Course Quiz", 1, 20, "Graded", 4);

/* Quiz Question */
insert into quizQuestion
values (1, 1, "What are the standard function of a WC7845?", "MCQ", 0);

insert into quizQuestion
values (1, 2, "WC7845 has a duty cycle up to 200000 images per month", "TF", 0);

insert into quizQuestion
values (2, 1, "WC7845 has a duty cycle up to 200000 images per month", "TF", 1);

insert into quizQuestion
values (2, 2, "What are the standard function of a WC7845?ycle up to 200000 images per month", "MCQ", 1);

insert into quizQuestion
values (2, 3, "There are difference, in terms of functionalities, between the Work Centre 7800 Series.", "TF", 1);

insert into quizQuestion
values (2, 4, "What are some of the standard security features in the 7800 Series?", "MCQ", 1);

insert into quizQuestion
values (2, 5, "Work Centre 7800 Series uses the same processor.", "TF", 1);

/* Quiz Answer */
/* Quiz Question 1 */
insert into quizAnswer
values (1, 1, 1, "Copy, email, print, scan", true);

insert into quizAnswer
values (2, 1, 1, "Copy, email, fax, scan", false);

insert into quizAnswer
values (3, 1, 1, "Copy, email, scan", false);

insert into quizAnswer
values (4, 1, 1, "Copy, print, scan", false);

/* Quiz Question 2 */
insert into quizAnswer
values (1, 1, 2, "True", true);

insert into quizAnswer
values (2, 1, 2, "False", false);

/* Quiz Question 3 */
insert into quizAnswer
values (1, 2, 1, "Copy, email, print, scan", true);

insert into quizAnswer
values (2, 2, 1, "Copy, email, fax, scan", false);

insert into quizAnswer
values (3, 2, 1, "Copy, email, scan", false);

insert into quizAnswer
values (4, 2, 1, "Copy, print, scan", false);

/* Quiz Question 4 */
insert into quizAnswer
values (1, 2, 2, "True", true);

insert into quizAnswer
values (2, 2, 2, "False", false);

/* Quiz Question 5 */
insert into quizAnswer
values (1, 2, 3, "True", false);

insert into quizAnswer
values (2, 2, 3, "False", true);

/* Quiz Question 6 */
insert into quizAnswer
values (1, 2, 4, "256-bit Encryption (FIPS 140-2 compliant)", true);

insert into quizAnswer
values (2, 2, 4, "Secure Email", true);

insert into quizAnswer
values (3, 2, 4, "Secure Print", true);

insert into quizAnswer
values (4, 2, 4, "User permissions", true);

/* Quiz Question 7 */
insert into quizAnswer
values (1, 2, 5, "True", true);

insert into quizAnswer
values (2, 2, 5, "False", false);

/* Student Quiz Attempt */
-- insert into studentQuizAttempt
-- values ("Yu Hao", 1, 1, false);

-- insert into studentQuizAttempt
-- values ("Yu Hao", 1, 2, true);

/* Student Quiz Record (!Note that student quiz record has to be inserted first before student quiz attempt) */
-- insert into studentQuizRecord
-- values (1, "Yu Hao", 1, 0, 1, 1, 2);

-- insert into studentQuizRecord
-- values (2, "Yu Hao", 1, 0, 1, 2, 2);

-- insert into studentQuizRecord
-- values (3, "Yu Hao", 1, 0, 2, 1, 1);

-- insert into studentQuizRecord
-- values (4, "Yu Hao", 1, 0, 2, 2, 1);
