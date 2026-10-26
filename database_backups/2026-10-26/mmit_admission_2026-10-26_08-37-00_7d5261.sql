-- Database backup for mmit_admission
-- Created: 2026-10-26T08:37:00+01:00

CREATE DATABASE IF NOT EXISTS `mmit_admission`;
USE `mmit_admission`;
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `is_active` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sno`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `documents` VALUES ('1', 'hello', '1', 0x323032362d31302d32342032323a32383a3437);
INSERT INTO `documents` VALUES ('4', 'hi', '1', 0x323032362d31302d32342032323a32393a3339);
INSERT INTO `documents` VALUES ('5', 'hum', '1', 0x323032362d31302d32342032323a33323a3438);
INSERT INTO `documents` VALUES ('7', 'hula', '1', 0x323032362d31302d32342032323a33333a3239);
INSERT INTO `documents` VALUES ('8', 'humne', '1', 0x323032362d31302d32342032323a33353a3534);
INSERT INTO `documents` VALUES ('9', 'hulaaaaa', '0', 0x323032362d31302d32342032323a33363a3031);
INSERT INTO `documents` VALUES ('10', 'hume', '0', 0x323032362d31302d32342032323a33393a3333);
INSERT INTO `documents` VALUES ('11', 'halo', '0', 0x323032362d31302d32342032323a34303a3438);
INSERT INTO `documents` VALUES ('12', 'mai hu khiladi ', '0', 0x323032362d31302d32342032323a34303a3535);
INSERT INTO `documents` VALUES ('13', 'hahaha', '0', 0x323032362d31302d32342032333a35373a3438);

DROP TABLE IF EXISTS `student_documents`;
CREATE TABLE `student_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `document_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `student_documents` VALUES ('10', '14', '5', 0x323032362d31302d32362031333a30363a3035);

DROP TABLE IF EXISTS `student_education`;
CREATE TABLE `student_education` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `highschoolyear` varchar(10) DEFAULT NULL,
  `highschoolboardname` varchar(150) DEFAULT NULL,
  `highschoolmarks` varchar(30) DEFAULT NULL,
  `highschoolpercentage` varchar(20) DEFAULT NULL,
  `highschoolcategory` varchar(50) DEFAULT NULL,
  `intermediateyear` varchar(10) DEFAULT NULL,
  `intermediateboardname` varchar(150) DEFAULT NULL,
  `intermediatemarks` varchar(30) DEFAULT NULL,
  `intermediatepercentage` varchar(20) DEFAULT NULL,
  `intermediatecategory` varchar(50) DEFAULT NULL,
  `otheryear` varchar(10) DEFAULT NULL,
  `otherboardname` varchar(150) DEFAULT NULL,
  `othermarks` varchar(30) DEFAULT NULL,
  `otherpercentage` varchar(20) DEFAULT NULL,
  `education_othercategory` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `student_education` VALUES ('1', '1', '2000', 'cbse', '1000', '100', 'min', '2000', 'up', '3000', '100', 'max', 'none', 'none', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('2', '2', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('3', '3', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('4', '4', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('5', '5', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('6', '6', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('7', '7', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('8', '8', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('9', '9', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('10', '10', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'cbse', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('12', '12', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none');
INSERT INTO `student_education` VALUES ('14', '14', 'none', 'fail hai sab mai', '0', 'fail', 'none', 'none', 'hullaa', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none');

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `admission_id` varchar(30) DEFAULT NULL,
  `syllabus` varchar(100) NOT NULL,
  `academic_session` varchar(20) NOT NULL,
  `candidate_name` varchar(150) NOT NULL,
  `guardian_name` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `permanent_address` text NOT NULL,
  `mailing_address` text NOT NULL,
  `contact1` varchar(15) NOT NULL,
  `contact2` varchar(15) DEFAULT NULL,
  `category` varchar(100) NOT NULL,
  `other_category` varchar(150) NOT NULL,
  `previous_school` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_id` (`admission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `students` VALUES ('1', 'MMIT20260001', 'CSE', '2026-27', 'maya', 'aman', 0x323032362d31302d3136, 'india', 'india', '1234567890', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'विकलांग / Physically Handicapped', 'hula', 0x323032362d31302d32302031343a34323a3433);
INSERT INTO `students` VALUES ('2', 'MMIT20260002', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a32363a3232);
INSERT INTO `students` VALUES ('3', 'MMIT20260003', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33303a3331);
INSERT INTO `students` VALUES ('4', 'MMIT20260004', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33303a3432);
INSERT INTO `students` VALUES ('5', 'MMIT20260005', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33303a3434);
INSERT INTO `students` VALUES ('6', 'MMIT20260006', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33323a3037);
INSERT INTO `students` VALUES ('7', 'MMIT20260007', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33333a3534);
INSERT INTO `students` VALUES ('8', 'MMIT20260008', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33353a3133);
INSERT INTO `students` VALUES ('9', 'MMIT20260009', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33353a3535);
INSERT INTO `students` VALUES ('10', 'MMIT20260010', 'IT', '2024-25', 'aman', 'maya', 0x323032362d31302d3034, 'dsf', 'fddf', '123456780', '1234567890', 'अनुसूचित जाति / Scheduled Caste', 'सैनिक आश्रित (Freedom Fighter / Armed Forces Dependent)', 'aaaaaaaa', 0x323032362d31302d32302032333a33363a3038);
INSERT INTO `students` VALUES ('12', 'MMIT20260012', 'Electronic', '2027-28', 'rashmii', 'rashii', 0x323032362d31302d3139, 'sss', 'chorahe ke pass', '1234567890', '12345678', 'सामान्य / Specify General', 'विकलांग / Physically Handicapped', 'hula', 0x323032362d31302d32332031393a31373a3235);
INSERT INTO `students` VALUES ('14', 'MMIT20260014', 'Electronic', '2026-27', 'moti', 'moti', 0x323032362d31302d3032, 'bada gaon', 'bada gaon', '1234567890', '1234567890', 'सामान्य / Specify General', 'लागू नहीं / सामान्य (None / Not Applicable)', 'fail', 0x323032362d31302d32352031303a31313a3536);

SET FOREIGN_KEY_CHECKS=1;
