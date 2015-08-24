<?php  
/**********************************************************************/
/*This file is part of Groovel.                                       */
/*Groovel is free software: you can redistribute it and/or modify     */
/*it under the terms of the GNU General Public License as published by*/
/*the Free Software Foundation, either version 2 of the License, or   */
/*(at your option) any later version.                                 */
/*Groovel is distributed in the hope that it will be useful,          */
/*but WITHOUT ANY WARRANTY; without even the implied warranty of      */
/*MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the       */
/*GNU General Public License for more details.                        */
/*You should have received a copy of the GNU General Public License   */
/*along with Groovel.  If not, see <http://www.gnu.org/licenses/>.    */
/**********************************************************************/

namespace controllers\groovel\admin\users_roles;
use Illuminate\Database\Eloquent\Model;
use controllers\groovel\admin\common\GroovelController;
use Monolog\Logger;
use business\groovel\admin\roles\GroovelUserRoleManagerBusiness;



class GroovelUsersRolesListController extends GroovelController {

	protected $userManager;
	
	
	public function __construct( \GroovelUserRoleManagerBusinessInterface $userManager)
	{
		$this->userManager=$userManager;
		$this->beforeFilter('auth');
	}
	

	public function init(){
	     return \View::make('cmsgroovel::pages.admin_list_roles_users',['users_roles'=>$this->userManager->paginateUserRole()]);
 	}

}
