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


namespace business\groovel\admin\templates;
use Illuminate\Database\Eloquent\Model;
use models;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Connection;
use Monolog\Logger;
use business\groovel\admin\bundles\GroovelPackageManagerBusinessInterface;
use business\groovel\admin\bundles\DTD_COMPOSER;
use Composer\Composer\Console\Application;
use Composer\Composer\Command\UpdateCommand;
use Symfony\Component\Console\Input\ArrayInput;

class GroovelTemplateManagerBusiness implements \GroovelTemplateManagerBusinessInterface{

	
}