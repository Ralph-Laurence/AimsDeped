<?php

/**
 * STORE CONSTANTS (READONLY VALUES) HERE .. 
 */

class Constants
{
    // We alias cookie names for obfuscation (protection from harmful users)
    public static string $COOKIE_USERNAME_ALIAS = "HaoNtd25HY";
    public static string $COOKIE_PASSWORD_ALIAS = "prUTxekriY";
    public static string $COOKIE_AUTH_TOKEN_ALIAS = "cLIZ7AAkp2";
    public static string $COOKIE_AUTH_USERID_ALIAS = "leNAJ7Go19";

    // We alias cookie names for obfuscation (protection from harmful users)
    public static string $SESSION_AUTH_USERNAME = "username";
    public static string $SESSION_AUTH_PASSWORD = "password";
    public static string $SESSION_AUTH_TOKEN = "token";
    public static string $SESSION_AUTH_USERID = "userid";
    public static string $SESSION_AUTH_USER_LEVEL = "userlevel";

    // Table names
    public static string $SDO_TABLE = "sdo_admins";
    public static string $COORD_TABLE = "coordinators";
    public static string $TEACHERS_TABLE = "teachers";
    public static string $STUDENTS_TABLE = "students";
    public static string $EXAM_RECORDS_TABLE = "exam_record_sheet";
    public static string $TEACHER_HANDLES_TABLE = "teacher_student_handles";
    public static string $SCHOOLS_TABLE = "schools"; 

    // User Levels

    /**
     * SDO = 0
     */
    public static int $USER_LVL_SDO = 0;
    
    /**
     * COORDINATOR = 1
     */
    public static int $USER_LVL_COORDINATOR = 1;
    
    /**
     * TEACHER = 2
     */
    public static int $USER_LVL_TEACHER = 2;

    /**
     * STUDENT = 3
     */
    public static int $USER_LVL_STUDENT = 3;
}

?>