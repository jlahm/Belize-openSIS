<?php
#**************************************************************************
#  openSIS is a free student information system for public and non-public 
#  schools from Open Solutions for Education, Inc. web: www.os4ed.com
#
#  openSIS is  web-based, open source, and comes packed with features that 
#  include student demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding this system or the license, please send 
#  an email to info@os4ed.com.
#
#  This program is released under the terms of the GNU General Public License as  
#  published by the Free Software Foundation, version 2 of the License. 
#  See license.txt.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#
#***************************************************************************************
function GetStaffList(& $extra)
{	global $profiles_RET;
	$functions = array('PROFILE'=>'makeProfile');
	switch(User('PROFILE'))
	{
		case 'admin':
		$profiles_RET = DBGet(DBQuery("SELECT * FROM USER_PROFILES"),array(),array('ID'));
		
  	#$sql = "SELECT CONCAT(s.LAST_NAME,  ' ' ,s.FIRST_NAME, ' ' ,s.MIDDLE_NAME) AS FULL_NAME,
	$sql = "SELECT CONCAT(s.LAST_NAME,  ' ' ,s.FIRST_NAME) AS FULL_NAME,
					s.PROFILE,s.PROFILE_ID,s.STAFF_ID,s.SCHOOLS ".$extra['SELECT']."
                FROM
					STAFF s ".$extra['FROM']."
				WHERE
					s.SYEAR='".UserSyear()."'";
		if($_REQUEST['_search_all_schools']!='Y')
			$sql .= " AND (s.SCHOOLS LIKE '%,".UserSchool().",%' OR s.SCHOOLS IS NULL OR s.SCHOOLS='') ";
		if($_REQUEST['_dis_user']=='Y')
		{
			$sql .= " OR s.IS_DISABLE='Y' ";
		#	$extra['columns_after'] = array('IS_DISABLE'=>'Disable');
		}
		if($_REQUEST['_dis_user']!='Y')
			$sql .= " AND s.IS_DISABLE IS NULL ";
		if($_REQUEST['username'])
			$sql .= "AND UPPER(s.USERNAME) LIKE '".strtoupper($_REQUEST['username'])."%' ";
		if($_REQUEST['last'])
			$sql .= "AND UPPER(s.LAST_NAME) LIKE '".strtoupper($_REQUEST['last'])."%' ";
		if($_REQUEST['first'])
			$sql .= "AND UPPER(s.FIRST_NAME) LIKE '".strtoupper($_REQUEST['first'])."%' ";
		if($_REQUEST['profile'])
			$sql .= "AND s.PROFILE='".$_REQUEST['profile']."' ";

		$sql .= $extra['WHERE'].' ';
		$sql .= "ORDER BY FULL_NAME";
	#	echo $sql;
/**************************************for Back to User*************************************************************/
            if($_SESSION['staf_search']['sql'] && $_REQUEST['return_session']) {
               $sql= $_SESSION['staf_search']['sql'];
            }
            else
            {
                if ($_REQUEST['sql_save_session_staf'])
                    $_SESSION['staf_search']['sql'] = $sql;
            }
/***************************************************************************************************/
		if ($extra['functions'])
			$functions += $extra['functions'];

		return DBGet(DBQuery($sql),$functions);
		break;
	}
}

function makeProfile($value)
{	global $THIS_RET,$profiles_RET;

	if($THIS_RET['PROFILE_ID'])
		$return = $profiles_RET[$THIS_RET['PROFILE_ID']][1]['TITLE'];
	elseif($value=='admin')
		$return = 'Administrator w/Custom';
	elseif($value=='teacher')
		$return = 'Teacher w/Custom';
	elseif($value=='parent')
		$return = 'Parent w/Custom';
	elseif($value=='none')
		$return = 'No Access';
	else $return = $value;

	return $return;
}



# ---------------------------------------- For Missing attn ------------------------------------------------- #

function GetStaffList_Miss_Atn(& $extra)
{

		global $profiles_RET;
		$functions = array('PROFILE'=>'makeProfile');
		switch(User('PROFILE'))
		{
			case 'admin':
			$profiles_RET = mysql_query("SELECT * FROM USER_PROFILES");
			$sql = "SELECT CONCAT(s.LAST_NAME, ' ', s.FIRST_NAME) AS FULL_NAME,
						s.PROFILE,s.PROFILE_ID,s.STAFF_ID,s.SCHOOLS ".$extra['SELECT']."
					FROM
						STAFF s ".$extra['FROM']."
					WHERE
						s.SYEAR='".UserSyear()."'";
			if($_REQUEST['_search_all_schools']!='Y')
				$sql .= " AND (s.SCHOOLS LIKE '%,".UserSchool().",%' OR s.SCHOOLS IS NULL OR s.SCHOOLS='') ";
			if($_REQUEST['username'])
				$sql .= "AND UPPER(s.USERNAME) LIKE '".strtoupper($_REQUEST['username'])."%' ";
			if($_REQUEST['last'])
				$sql .= "AND UPPER(s.LAST_NAME) LIKE '".strtoupper($_REQUEST['last'])."%' ";
			if($_REQUEST['first'])
				$sql .= "AND UPPER(s.FIRST_NAME) LIKE '".strtoupper($_REQUEST['first'])."%' ";
			if($_REQUEST['profile'])
				$sql .= "AND s.PROFILE='".$_REQUEST['profile']."' ";

			$sql .= "AND (";
 $sql_st = "SELECT staff_id FROM STAFF where profile='teacher' ";
$res_st = mysql_query($sql_st);

$a = 0;

while($row_st = mysql_fetch_array($res_st))
 {



    /* $RET = DBGET(DBQuery("SELECT DISTINCT s.TITLE AS SCHOOL,acc.SCHOOL_DATE,cp.TITLE FROM ATTENDANCE_CALENDAR acc,COURSE_PERIODS cp,SCHOOL_PERIODS sp,SCHOOLS s,STAFF st,SCHEDULE sch WHERE acc.SYEAR='".UserSyear()."' AND (acc.MINUTES IS NOT NULL AND acc.MINUTES>0) AND st.STAFF_ID='".User('STAFF_ID')."' AND cp.TEACHER_ID='".$row_st['staff_id']."' AND (st.SCHOOLS IS NULL OR position(acc.SCHOOL_ID IN st.SCHOOLS)>0) AND cp.SCHOOL_ID=acc.SCHOOL_ID AND cp.SYEAR=acc.SYEAR AND cp.CALENDAR_ID=acc.CALENDAR_ID AND cp.FILLED_SEATS<>0  AND sch.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID AND acc.SCHOOL_DATE>=sch.START_DATE AND acc.SCHOOL_DATE<'".DBDate()."'
		AND cp.MARKING_PERIOD_ID IN (SELECT MARKING_PERIOD_ID FROM SCHOOL_YEARS WHERE SCHOOL_ID=acc.SCHOOL_ID AND acc.SCHOOL_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM SCHOOL_SEMESTERS WHERE SCHOOL_ID=acc.SCHOOL_ID AND acc.SCHOOL_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM SCHOOL_QUARTERS WHERE SCHOOL_ID=acc.SCHOOL_ID AND acc.SCHOOL_DATE BETWEEN START_DATE AND END_DATE)
		AND sp.PERIOD_ID=cp.PERIOD_ID AND (sp.BLOCK IS NULL AND position(substring('UMTWHFS' FROM DAYOFWEEK(acc.SCHOOL_DATE) FOR 1) IN cp.DAYS)>0
			OR sp.BLOCK IS NOT NULL AND acc.BLOCK IS NOT NULL AND sp.BLOCK=acc.BLOCK)
		AND NOT EXISTS(SELECT '' FROM ATTENDANCE_COMPLETED ac WHERE ac.SCHOOL_DATE=acc.SCHOOL_DATE AND ac.STAFF_ID=cp.TEACHER_ID AND ac.PERIOD_ID=cp.PERIOD_ID) AND cp.DOES_ATTENDANCE='Y' AND s.ID=acc.SCHOOL_ID ORDER BY cp.TITLE,acc.SCHOOL_DATE"),array('SCHOOL_DATE'=>'ProperDate'));
*/
       
	   $RET = DBGET(DBQuery("SELECT DISTINCT s.TITLE AS SCHOOL,acc.SCHOOL_DATE,cp.TITLE FROM ATTENDANCE_CALENDAR acc,COURSE_PERIODS cp,SCHOOL_PERIODS sp,SCHOOLS s,STAFF st,SCHEDULE sch WHERE acc.SYEAR='".UserSyear()."' AND (acc.MINUTES IS NOT NULL AND acc.MINUTES>0) AND st.STAFF_ID='".User('STAFF_ID')."' AND (cp.TEACHER_ID='".$row_st['staff_id']."' OR cp.SECONDARY_TEACHER_ID='".$row_st['staff_id']."') AND (st.SCHOOLS IS NULL OR position(acc.SCHOOL_ID IN st.SCHOOLS)>0) AND cp.SCHOOL_ID=acc.SCHOOL_ID AND cp.SYEAR=acc.SYEAR AND cp.CALENDAR_ID=acc.CALENDAR_ID AND cp.FILLED_SEATS<>0  AND sch.COURSE_PERIOD_ID=cp.COURSE_PERIOD_ID ".$extra['WHERE1']."
		AND cp.MARKING_PERIOD_ID IN (SELECT MARKING_PERIOD_ID FROM SCHOOL_YEARS WHERE SCHOOL_ID=acc.SCHOOL_ID AND acc.SCHOOL_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM SCHOOL_SEMESTERS WHERE SCHOOL_ID=acc.SCHOOL_ID AND acc.SCHOOL_DATE BETWEEN START_DATE AND END_DATE UNION SELECT MARKING_PERIOD_ID FROM SCHOOL_QUARTERS WHERE SCHOOL_ID=acc.SCHOOL_ID AND acc.SCHOOL_DATE BETWEEN START_DATE AND END_DATE)
		AND sp.PERIOD_ID=cp.PERIOD_ID AND (sp.BLOCK IS NULL AND position(substring('UMTWHFS' FROM DAYOFWEEK(acc.SCHOOL_DATE) FOR 1) IN cp.DAYS)>0
			OR sp.BLOCK IS NOT NULL AND acc.BLOCK IS NOT NULL AND sp.BLOCK=acc.BLOCK)
		AND NOT EXISTS(SELECT '' FROM ATTENDANCE_COMPLETED ac WHERE ac.SCHOOL_DATE=acc.SCHOOL_DATE AND ac.STAFF_ID=cp.TEACHER_ID AND ac.PERIOD_ID=cp.PERIOD_ID) AND cp.DOES_ATTENDANCE='Y' AND s.ID=acc.SCHOOL_ID AND s.ID='".UserSchool()."' ORDER BY cp.TITLE,acc.SCHOOL_DATE"),array('SCHOOL_DATE'=>'ProperDate'));


	 if (count($RET))
	 {
		$sql .= "s.STAFF_ID='".$row_st['staff_id']."' OR ";
		$a++;
	 }
 }
		if($a != 0)
		{
			$sql = substr($sql, 0, -4);
			$sql .= ")";
		}
		else
		{
			$sql = substr($sql, 0, -5);
		}

			$sql .= $extra['WHERE'].' ';
			$sql .= "ORDER BY FULL_NAME";


			if ($extra['functions'])
				$functions += $extra['functions'];


		if($a != 0)
			return DBGet(DBQuery($sql),$functions);

			break;
	}

}








?>
