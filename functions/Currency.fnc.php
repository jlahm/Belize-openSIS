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
function Currency($num,$sign='before',$red=false)
{
	$original = $num;
	if($sign=='before' && $num<0)
	{
		$negative = true;
		$num *= -1;
	}
	elseif($sign=='CR' && $num<0)
	{
		$cr = true;
		$num *= -1;
	}
	
	$num = "\$".number_format($num,2,'.',',');
	if($negative)
		$num = '-'.$num;
	elseif($cr)
		$num = $num.'CR';
	if($red && $original<0)
		$num = '<font color=red>'.$num.'</font>';

	return '<!-- '.$original.' -->'.$num;
}
?>