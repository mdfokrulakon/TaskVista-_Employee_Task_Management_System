-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 08:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `task_management_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `chuti`
--

CREATE TABLE `chuti` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_type` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `reason` text NOT NULL,
  `contact_details` varchar(255) DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chuti`
--

INSERT INTO `chuti` (`id`, `user_id`, `leave_type`, `start_date`, `end_date`, `duration`, `reason`, `contact_details`, `attachment_path`, `status`, `requested_at`) VALUES
(1, 15, 'Vacation', '2025-08-26', '2025-08-29', 4, 'I am very much sick', '01756202157', NULL, 'approved', '2025-08-24 17:45:29'),
(2, 15, 'Sick Leave', '2025-08-15', '2025-08-17', 3, 'sadsadasdasdasd d sad sa ds das da ', '', NULL, 'rejected', '2025-08-24 17:54:06'),
(3, 15, 'Vacation', '2025-08-12', '2025-08-30', 19, 'sdadasda', '01756202157', NULL, 'approved', '2025-08-24 17:55:00'),
(4, 16, 'Vacation', '2025-08-26', '2025-08-29', 4, 'Sick leave emergency', '', NULL, 'approved', '2025-08-24 18:16:01');

-- --------------------------------------------------------

--
-- Table structure for table `leave_approvals`
--

CREATE TABLE `leave_approvals` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` enum('approved','rejected') NOT NULL,
  `action_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_approvals`
--

INSERT INTO `leave_approvals` (`id`, `request_id`, `admin_id`, `action`, `action_at`) VALUES
(1, 1, 1, 'approved', '2025-08-24 17:45:47'),
(2, 2, 1, 'rejected', '2025-08-24 17:54:17'),
(3, 3, 1, 'approved', '2025-08-24 17:57:36'),
(4, 4, 1, 'approved', '2025-08-24 18:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `task_id`, `days`, `status`, `requested_at`) VALUES
(1, 15, 30, 5, 'rejected', '2025-08-23 19:31:12'),
(2, 15, 30, 5, 'rejected', '2025-08-23 19:31:43'),
(3, 15, 30, 5, 'approved', '2025-08-23 19:32:27'),
(4, 15, 30, 15, 'rejected', '2025-08-23 19:36:00'),
(5, 15, 36, 10, 'approved', '2025-08-24 16:45:13'),
(6, 15, 36, 15, 'approved', '2025-08-24 17:09:40'),
(7, 15, 36, 15, 'approved', '2025-08-24 17:14:35');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `recipient` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `message`, `recipient`, `type`, `date`, `is_read`, `user_id`) VALUES
(1, '\'Customer Feedback Survey Analysis\' has been assigned to you. Please review and start working on it.', 7, 'New Task Assigned', '2024-09-05', 1, 0),
(2, '\'test task\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '0000-00-00', 1, 0),
(3, '\'Example task 2\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2006-09-24', 1, 0),
(4, '\'test\' has been assigned to you. Please review and start working on it', 8, 'New Task Assigned', '2009-06-24', 0, 0),
(5, '\'test task 3\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 1, 0),
(6, '\'Prepare monthly sales report\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 1, 0),
(7, '\'Update client database\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 1, 0),
(8, '\'Fix server downtime issue\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2024-09-06', 0, 0),
(9, '\'Plan annual marketing strategy\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2024-09-06', 0, 0),
(10, '\'Onboard new employees\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 0, 0),
(11, '\'Design new company website\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2024-09-06', 0, 0),
(12, '\'Conduct software testing\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 0, 0),
(13, '\'Schedule team meeting\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2024-09-06', 0, 0),
(14, '\'Prepare budget for Q4\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 0, 0),
(15, '\'Write blog post on industry trend\' has been assigned to you. Please review and start working on it', 7, 'New Task Assigned', '2024-09-06', 0, 0),
(16, '\'Renew software license\' has been assigned to you. Please review and start working on it', 2, 'New Task Assigned', '2024-09-06', 0, 0),
(17, '\'1\' has been assigned to you. Please review and start working on it', 8, 'New Task Assigned', '2025-08-23', 0, 0),
(18, '\'121111111\' has been assigned to you. Please review and start working on it', 11, 'New Task Assigned', '2025-08-24', 0, 0),
(19, '\'asdvs\' has been assigned to you. Please review and start working on it', 15, 'New Task Assigned', '2025-08-24', 0, 0),
(20, '\'sad\' has been assigned to you. Please review and start working on it', 15, 'New Task Assigned', '2025-08-24', 0, 0),
(21, 'jkl updated status of \"sad\" to \"in_progress\".', 0, 'Task Status Update', '2025-08-24', 0, 1),
(22, 'jkl updated status of \"sad\" to \"in_progress\".', 0, 'Task Status Update', '2025-08-24', 0, 14),
(23, 'jkl updated status of \"sad\" to \"completed\".', 0, 'Task Status Update', '2025-08-24', 0, 1),
(24, 'jkl updated status of \"sad\" to \"completed\".', 0, 'Task Status Update', '2025-08-24', 0, 14),
(25, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(26, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(27, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(28, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(29, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(30, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(31, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(32, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(33, '\'ty\' has been assigned to you. Please review and start working on it', 15, 'New Task Assigned', '2025-08-24', 0, 0),
(34, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(35, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(36, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(37, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(38, 'jkl updated the status of task \"asdvs\" to \"in_progress\".', 1, 'Task Status Update', '2025-08-24', 0, 0),
(39, 'jkl updated the status of task \"asdvs\" to \"in_progress\".', 14, 'Task Status Update', '2025-08-24', 0, 0),
(40, '15', 0, 'New Task Assigned', '2025-08-24', 0, 0),
(41, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(42, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(43, '1', 0, 'Task Status Update', '2025-08-24', 0, 0),
(44, '14', 0, 'Task Status Update', '2025-08-24', 0, 0),
(45, 'jkl updated the status of task \"sad\" to \"completed\".', 1, 'Task Status Update', '2025-08-24', 0, 0),
(46, 'jkl updated the status of task \"sad\" to \"completed\".', 14, 'Task Status Update', '2025-08-24', 0, 0),
(47, 'You have been assigned a new task: \"1\".', 15, 'New Task Assigned', '2025-08-24', 0, 0),
(48, 'jkl updated the status of task \"1\" to \"in_progress\".', 1, 'Task Status Update', '2025-08-24', 0, 0),
(49, 'jkl updated the status of task \"1\" to \"in_progress\".', 14, 'Task Status Update', '2025-08-24', 0, 0),
(50, 'jkl updated the status of task \"sada\" to \"pending\".', 1, 'Task Status Update', '2025-08-24', 0, 0),
(51, 'jkl updated the status of task \"sada\" to \"pending\".', 14, 'Task Status Update', '2025-08-24', 0, 0),
(52, 'jkl updated the status of task \"1\" to \"completed\".', 1, 'Task Status Update', '2025-08-24', 0, 0),
(53, 'jkl updated the status of task \"1\" to \"completed\".', 14, 'Task Status Update', '2025-08-24', 0, 0),
(54, 'You have been assigned a new task: \"sad\".', 15, 'New Task Assigned', '2025-08-24', 0, 0),
(55, 'jkl updated task \"asdvs\": Status is now \'in_progress\', progress is at 25%.', 1, 'Task Progress Update', '2025-08-24', 0, 0),
(56, 'jkl updated task \"asdvs\": Status is now \'in_progress\', progress is at 25%.', 14, 'Task Progress Update', '2025-08-24', 0, 0),
(57, 'jkl updated task \"sad\": Status is now \'in_progress\', progress is at 60%.', 1, 'Task Progress Update', '2025-08-24', 0, 0),
(58, 'jkl updated task \"sad\": Status is now \'in_progress\', progress is at 60%.', 14, 'Task Progress Update', '2025-08-24', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `percentage` int(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `created_by`, `title`, `description`, `assigned_to`, `due_date`, `status`, `percentage`, `created_at`) VALUES
(1, 0, 'Task 1', 'Task Description', NULL, NULL, 'completed', 0, '2024-08-29 16:47:37'),
(4, 0, 'Monthly Financial Report Preparations', 'Prepare and review the monthly financial report, including profit and loss statements, balance sheets, and cash flow analysis.', 11, '2024-09-01', 'completed', 0, '2024-08-31 10:50:20'),
(5, 0, 'Customer Feedback Survey Analysis', 'Collect and analyze data from the latest customer feedback survey to identify areas for improvement in customer service.', NULL, '2024-09-03', 'in_progress', 0, '2024-08-31 10:50:47'),
(6, 0, 'Website Maintenance and Update', 'Perform regular maintenance on the company website, update content, and ensure all security patches are applied.', NULL, '2024-09-03', 'pending', 0, '2024-08-31 10:51:12'),
(7, 0, 'Quarterly Inventory Audit', 'Conduct a thorough audit of inventory levels across all warehouses and update the inventory management system accordingly.', NULL, '2024-09-03', 'completed', 0, '2024-08-31 10:51:45'),
(8, 0, 'Employee Training Program Development', 'Develop and implement a new training program focused on enhancing employee skills in project management and teamwork.', NULL, '2024-09-01', 'pending', 0, '2024-08-31 10:52:11'),
(17, 0, 'Prepare monthly sales report', 'Compile and analyze sales data for the previous month', NULL, '2024-09-06', 'pending', 0, '2024-09-06 08:01:48'),
(18, 0, 'Update client database', 'Ensure all client information is current and complete', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:02:27'),
(19, 0, 'Fix server downtime issue', 'Investigate and resolve the cause of recent server downtimes', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:02:59'),
(20, 0, 'Plan annual marketing strategy', 'Develop a comprehensive marketing strategy for the next year', NULL, '2024-09-04', 'pending', 0, '2024-09-06 08:03:21'),
(21, 0, 'Onboard new employees', 'Complete HR onboarding tasks for the new hires', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:03:44'),
(22, 0, 'Design new company website', 'Create wireframes and mockups for the new website design', NULL, '2024-09-06', 'pending', 0, '2024-09-06 08:04:20'),
(23, 0, 'Conduct software testing', 'Run tests on the latest software release to identify bugs', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:04:39'),
(24, 0, 'Schedule team meeting', 'Organize a meeting to discuss project updates', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:04:57'),
(25, 0, 'Prepare budget for Q4', 'Create and review the budget for the upcoming quarter', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:05:21'),
(26, 0, 'Write blog post on industry trend', 'Draft and publish a blog post about current industry trend', NULL, '2024-09-07', 'pending', 0, '2024-09-06 08:10:50'),
(27, 0, 'Renew software license', 'Ensure all software licenses are renewed and up to date', NULL, '2024-09-06', 'pending', 0, '2024-09-06 08:11:28'),
(29, 0, '121111111', 'dsadsada', 11, '2025-08-30', 'pending', 0, '2025-08-23 18:47:05'),
(30, 0, 'asdvs', 'sadvsdva', 15, '2025-08-31', 'in_progress', 25, '2025-08-23 18:59:04'),
(31, 0, 'sad', 'sads', 15, '2025-08-30', 'in_progress', 60, '2025-08-23 20:37:45'),
(32, 0, 'ty', 'ty', 15, '2025-08-30', 'pending', 0, '2025-08-23 21:01:28'),
(33, 0, 'sada', 'asds', 15, '0000-00-00', 'pending', 0, '2025-08-23 21:07:47'),
(34, 0, '1', '1', 15, '0000-00-00', 'pending', 0, '2025-08-23 21:13:31'),
(35, 1, '1', '1', 15, '2025-08-30', 'completed', 0, '2025-08-23 21:16:51'),
(36, 1, 'sad', 'asd', 15, '2025-09-29', 'pending', 0, '2025-08-23 21:37:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','employee') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remaining_leaves` int(11) NOT NULL DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `password`, `role`, `created_at`, `remaining_leaves`) VALUES
(1, 'Oliver', 'admin', '$2y$10$tHdFV7soOXASoUIbS0t67uLIKkh77Z2vaSWSlZgJgi8q/h6ucw9bq', 'admin', '2024-08-28 07:10:04', 20),
(11, 'abc', 'abc', '202cb962ac59075b964b07152d234b70', 'employee', '2025-08-23 17:43:36', 20),
(13, 'abc', 'def', '$2y$10$Dx3HpiHYBsdklvXKDVaYueFaoi84b9YL/EAoEqhOI9r8Q1SkfKmhS', 'employee', '2025-08-23 18:16:49', 20),
(14, 'ty', 'fgh', '$2y$10$oUF.NhFH2E.y194l8Rj27.2KQNzYHDTPISU7okYcHU0zAHYXnPYHC', 'admin', '2025-08-23 18:45:04', 20),
(15, 'jkl', 'jkl', '$2y$10$AqZ/c5Kw./pYfcpVyDujzeAX0g7DnaGJ42V2nLOpDS37M7Xukv5WW', 'employee', '2025-08-23 18:57:14', -3),
(16, 'joy', 'joy', '$2y$10$Nm3imkp7uC/FR54Inhx2WetRdKGH6OkY6CjDVffadxPWqc2Wk9amC', 'employee', '2025-08-24 18:11:27', 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chuti`
--
ALTER TABLE `chuti`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chuti`
--
ALTER TABLE `chuti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chuti`
--
ALTER TABLE `chuti`
  ADD CONSTRAINT `chuti_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_approvals`
--
ALTER TABLE `leave_approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `chuti` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `approvals_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
