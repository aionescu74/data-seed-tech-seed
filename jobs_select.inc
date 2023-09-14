<?php
$jobs_select = "SELECT seed_jobs.jobId, jobType, jobAction, repetitionType, repetitionDay, repetitionValue, seed_jobs.lastRun, 
    (CASE WHEN (repetitionType = 'daily')
    THEN
         (case when (seed_jobs.lastRun is null)
         then
            STR_TO_DATE(CONCAT(DATE_ADD(DATE(now()), INTERVAL 1 DAY), ' ', repetitionValue), '%Y-%m-%d %H:%i:%s')
         else
            STR_TO_DATE(CONCAT(DATE_ADD(DATE(seed_jobs.lastRun), INTERVAL 1 DAY), ' ', repetitionValue), '%Y-%m-%d %H:%i:%s')
         end)
    WHEN (repetitionType = 'once')
    THEN
         STR_TO_DATE(CONCAT(repetitionDay, ' ', repetitionValue), '%Y-%m-%d %H:%i:%s')
    ELSE
         '???'
    END) as nextRun,

    (CASE WHEN (repetitionType = 'daily')
    THEN
         (case when (seed_jobs.lastRun is null)
         then
            time_to_sec((TIMEDIFF(STR_TO_DATE(CONCAT(DATE(now()), ' ', repetitionValue), '%Y-%m-%d %H:%i:%s'), NOW())))
         else
            time_to_sec((TIMEDIFF(STR_TO_DATE(CONCAT(DATE_ADD(DATE(seed_jobs.lastRun), INTERVAL 1 DAY), ' ', repetitionValue), '%Y-%m-%d %H:%i:%s'), NOW())))
         end)
    WHEN (repetitionType = 'once')
    THEN
    	time_to_sec(TIMEDIFF(STR_TO_DATE(CONCAT(repetitionDay, ' ', repetitionValue), '%Y-%m-%d %H:%i:%s'), NOW()))
    ELSE
         999999
    END) as secondsToNextRun

    FROM seed_jobs
    LEFT JOIN
    (SELECT jobId, MAX(logDateTime) as lastRun
    FROM seed_jobs_log
    GROUP BY jobId) LG
            ON seed_jobs.jobId = LG.jobId
    WHERE active = 1";

?>