CREATE TABLE `seed_jobs` (
  `jobId` int NOT NULL,
  `jobType` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jobAction` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `repetitionType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Values: daily, once',
  `repetitionDay` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `repetitionValue` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastRun` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seed_jobs`
--

INSERT INTO `seed_jobs` (`jobId`, `jobType`, `jobAction`, `repetitionType`, `repetitionDay`, `repetitionValue`, `lastRun`, `active`) VALUES
(1, 'cmd', 'test', 'daily', '6', '11:00', NULL, 1);



CREATE TABLE `seed_jobs_log` (
  `id` int NOT NULL,
  `jobId` int NOT NULL,
  `logDateTime` datetime NOT NULL,
  `message` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `runType` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





CREATE TABLE `seed_job_type` (
  `jobType` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jobTypeName` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Job types: rest_pipe, script (php), cmd, api_get, rest_pipe';

--
-- Dumping data for table `seed_job_type`
--

INSERT INTO `seed_job_type` (`jobType`, `jobTypeName`) VALUES
('api_get', 'Not implementd yet'),
('cmd', 'Example: \r\npython C:/Users/aione/PY/curs_bnr_xml.py'),
('link', 'External link'),
('rest_pipe', 'rest_pipe'),
('script', 'script');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `seed_jobs`
--
ALTER TABLE `seed_jobs`
  ADD PRIMARY KEY (`jobId`),
  ADD KEY `jobType` (`jobType`);

--
-- Indexes for table `seed_jobs_log`
--
ALTER TABLE `seed_jobs_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seed_job_type`
--
ALTER TABLE `seed_job_type`
  ADD PRIMARY KEY (`jobType`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `seed_jobs_log`
--
ALTER TABLE `seed_jobs_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seed_jobs`
--
ALTER TABLE `seed_jobs`
  ADD CONSTRAINT `seed_jobs_ibfk_1` FOREIGN KEY (`jobType`) REFERENCES `seed_job_type` (`jobType`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;
