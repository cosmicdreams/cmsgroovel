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
namespace handlers;
use commons\ModelConstants;
use dao\RepositoryIndexDao;
use dao\RepositoryIndexDaoInterface;

class DatabaseSearchHandler
{
	private $reposearch;
	
	public function __construct(\RepositoryIndexDaoInterface $reposearch)
	{
		$this->reposearch =$reposearch;
	}
	
	public function create($job, $data)
	{
		$index=null;
		if(ModelConstants::$contents==$data['type']){
			$index='contents';
		}else if(ModelConstants::$user==$data['type']){
			$index='users';
		}
		$this->reposearch->create($data['type'],$data['data']['id'],$data['data']['grooveldescription'],$data['data']['title'],$data['data']['url']);
	}
	
	public function update($job, $data)
	{
		$index=null;
		if(ModelConstants::$contents==$data['type']){
			$index='contents';
		}else if(ModelConstants::$user==$data['type']){
			$index='users';
		}
		$this->reposearch->update($data['type'],$data['data']['id'],$data['data']['grooveldescription'],$data['data']['title'],$data['data']['url']);
		
	}
	
	
	public function delete($job, $data)
	{
		$index=null;
		if(ModelConstants::$contents==$data['type']){
			$index='contents';
		}else if(ModelConstants::$user==$data['type']){
			$index='users';
		}
		
		$this->reposearch->delete($data['data']['id']);
	}
	
	
	
	
	
}