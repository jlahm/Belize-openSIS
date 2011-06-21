<?php
#**************************************************************************
#  openSIS is a free student information system for public and non-public 
#  schools from Open Solutions for Education, Inc. It is  web-based, 
#  open source, and comes packed with features that include student 
#  demographic info, scheduling, grade book, attendance, 
#  report cards, eligibility, transcripts, parent portal, 
#  student portal and more.   
#
#  Visit the openSIS web site at http://www.opensis.com to learn more.
#  If you have question regarding this system or the license, please send 
#  an email to info@os4ed.com.
#
#  Copyright (C) 2007-2008, Open Solutions for Education, Inc.
#
#*************************************************************************
#  This program is free software: you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation, version 2 of the License. See license.txt.
#
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
#**************************************************************************
include('../../Redirect_modules.php');

	$alllogs_RET = DBGet(DBQuery("SELECT DISTINCT FIRST_NAME,LAST_NAME,LOGIN_TIME,PROFILE,FAILLOG_COUNT,FAILLOG_TIME,USER_NAME,IP_ADDRESS,STATUS FROM LOGIN_RECORDS WHERE SCHOOL_ID=".UserSchool()." OR STATUS='Failed'  ORDER BY LOGIN_TIME DESC"));
		

		if(count($alllogs_RET))
		{
			echo '<div>';
			#ListOutput($alllogs_RET,array('LOGIN_TIME'=>'Login Time','USER_NAME'=>'User Name','FIRST_NAME'=>'First Name','LAST_NAME'=>'Last Name','FAILLOG_COUNT'=>'Failure Count','FAILLOG_TIME'=>'Failed Login Time','STATUS'=>'Status','IP_ADDRESS'=>'IP Address'),'successfull login','successfull logins',array(),array(),array('count'=>true,'save'=>true));
			
		ListOutput($alllogs_RET,array('LOGIN_TIME'=>'Login Time','USER_NAME'=>'User Name','FIRST_NAME'=>'First Name','LAST_NAME'=>'Last Name','FAILLOG_COUNT'=>'Failure Count','STATUS'=>'Status','IP_ADDRESS'=>'IP Address'),'login record','login records',array(),array(),array('count'=>true,'save'=>true));

			echo '</div>';
		}
		else
		{
		
		echo '<table border=0 width=90%><tr><td class="alert"></td><td class="alert_msg"><b>No login records were found.</b></td></tr></table>';
		
		}	

?>