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
namespace controllers\groovel\admin\templates;
use Illuminate\Database\Eloquent\Model;
use controllers\groovel\admin\common\GroovelController;
use models;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Tests\Controller;
use business\groovel\admin\bundles\GroovelPackageManagerBusiness;
use business\groovel\admin\bundles\GroovelPackageManagerBusinessInterface;
use business\groovel\admin\routes\GroovelRoutesBusiness;
use business\groovel\admin\routes\GroovelRoutesBusinessInterface;

class GroovelTemplateManagerController extends GroovelController {
	protected $packageManager;
	
	protected $templateManager;
	
	protected $routeManager;
	
	public function __construct(\GroovelPackageManagerBusinessInterface $packageManager,\GroovelTemplateManagerBusinessInterface $templateManager,\GroovelRoutesBusinessInterface $routeManager) {
		$this->packageManager = $packageManager;
		$this->templateManager = $templateManager;
		$this->routeManager=$routeManager;
		$this->beforeFilter('auth');
	}
	
	public function init(){
		return \View::make('cmsgroovel::pages.admin_template_form',['templates'=>$this->listAllTemplates()]);
	}
	
	
	public function listAllTemplates(){
		$dir_vendor= base_path () . '/vendor/groovel/cmsgroovel/templates/layouts/*';
		$dir_workbench=base_path () . '/workbench/groovel/cmsgroovel/templates/layouts/*';
		$template_directories = glob ( $dir_vendor, GLOB_ONLYDIR );
		if(empty($template_directories)){
			$template_directories=glob (	$dir_workbench, GLOB_ONLYDIR );
		}
		$template_names=array();
		foreach ( $template_directories as $templatenamedir ) {
			$exp = explode ( "/", $templatenamedir );
			$templateName = $exp [sizeof ( $exp ) - 1];
			$template_names[$templateName ]=$templateName ;
		}
		
		return $template_names;
	}
	
	 function recurse_copy($src, $dst) {
	  $dir = opendir($src);
	  $result = ($dir === false ? false : true);
	  if ($result !== false) {
	      while(false !== ( $file = readdir($dir)) ) { 
	        if (( $file != '.' ) && ( $file != '..' ) && $result) { 
	          if ( is_dir($src . '/' . $file) ) { 
	            $result = self::recurse_copy($src . '/' . $file,$dst . '/' . $file); 
	          }     else { 
	          	if (!is_dir($dst)) {
	          		mkdir($dst, 0700,true);
	          	}
	            $result = copy($src . '/' . $file,$dst . '/' . $file); 
	          } 
	        } 
	      } 
	      closedir($dir);
	  }
	  return $result;
	}
	
	
	public function validateForm() {
		$input = \Input::all();
		if (\Request::is('*/templates/add')){
		
			$rules=array();
			$rules['url']='required|url';
				
			$url=\Input::get('url');
			$validInput=\Input::all();
			if($url!=null){
				$urlvalid='http://localhost/'.$url;
				$validInput['url']=$urlvalid;
			}
			$validation = \Validator::make($validInput, $rules);
			if($validation->passes()){
				$rules2=array();
				$rules2['url']='required|unique:routes_groovel,uri';
				$validation2 = \Validator::make(array('url'=>\Input::get('url')), $rules2);
				if($validation2->passes()){
					ini_set ('max_execution_time', 0);
					//$this->artisanCreateTemplate(\Input::get('vendor'),\Input::get('package'));//create package in workbench
					$this->copyBladeTemplatesToApp(\Input::get('template'));//copy templates from groovel to new package
					if(\Input::get('controller')!=null){
						$this->artisanCreateController(\Input::get('controller'));//create a controller
					}
					//$this-> artisanPublishAssets(\Input::get('vendor'),\Input::get('package'));//publish styles from new package in public app
					$this-> createRoute(\Input::get('url'),\Input::get('template'),\Input::get('controller'),'index','op_retrieve','base.core');
					return $this->jsonResponse(array('done'),false,true,false);
				}else if($validation2->fails()){
					$validation2->getMessageBag()->add('template', 'please check errors');
					$messages=$validation2->messages();
					$formatMess=null;
					foreach ($messages->all() as $message)
					{
						$formatMess=$message.'- '.$formatMess;
					}
					return $this->jsonResponse($formatMess,false,true,true);
				}
			
			}else if($validation->fails()){
				$validation->getMessageBag()->add('content', 'Please check errors');
				$messages=$validation->messages();
				$formatMess=null;
				foreach ($messages->all() as $message)
				{
					$formatMess=$message.'- '.$formatMess;
				}
				return $this->jsonResponse($formatMess,false,true,true);
			}
		}
	}
	
	
	public function createRoute($uri,$name,$controller,$method,$action,$view){
		$this->routeManager->addRoute(null,$uri,$name,$controller,$method,$action,$view,null,null,'default','default','0','1');
	}
	
	function copyBladeTemplatesToApp($templateName){
		$dir_workbench_base=base_path () . '/workbench/groovel/cmsgroovel/templates/layouts/'.$templateName.'/base';
		$dir_workbench_includes=base_path () . '/workbench/groovel/cmsgroovel/templates/layouts/'.$templateName.'/includes';
		$dir_workbench_pages=base_path () . '/workbench/groovel/cmsgroovel/templates/layouts/'.$templateName.'/pages';
		$dst_base=base_path () . '/app/views/base';
		$dst_includes=base_path () . '/app/views/includes';
		$dst_pages=base_path () . '/app/views/pages';
		$dir_workbench_styles=base_path () . '/workbench/groovel/cmsgroovel/templates/layouts/'.$templateName.'/styles';
		$pub=public_path() ;
	
	
		$this->recurse_copy($dir_workbench_base, $dst_base);
		$this->recurse_copy($dir_workbench_includes, $dst_includes);
		$this->recurse_copy($dir_workbench_pages, $dst_pages);
		$this->recurse_copy($dir_workbench_styles, $pub.'/styles');
	
	}
	
	function artisanCreateController($controller) {
		ini_set ('max_execution_time', 0);
		\Log::info('<br>init artisan::controller...');
		\Artisan::call('controller:make',array('name'=>$controller));
		\Log::info( 'done artisan::controller');
	
	}
	
	public function jsonResponse($param, $print = false, $header = true,$error=false) {
		if (is_array($param) && !$error) {
			$out = array(
					'success' => true
			);
	
			if (array_key_exists('datas',$param) && is_array($param['datas']) ) {
				$out['datas'] = $param['datas'];
				unset($param['datas']);
				$out = array_merge($out, $param);
			} else {
				$out['datas'] = $param;
			}
	
		}else if (is_bool($param) &&!$error) {
			$out = array(
					'success' => $param
			);
		} else if($error) {
			$out = array(
					'success' => false,
					'errors' => array(
							'reason' => $param
					)
			);
		}
	
		$out = json_encode($out);
	
		if ($print) {
			if ($header) header('Content-type: application/json');
	
			echo $out;
			return;
		}
	
		return $out;
	}
	
	/*function copyBladeTemplatesToWorkbench($templateName,$vendor,$package){
		$dir_workbench=base_path () . '/workbench/groovel/cmsgroovel/templates/layouts/'.$templateName;
		$dst=base_path () . '/workbench/'.$vendor.'/'.$package.'/src/views';
		$pub=base_path () . '/workbench/'.$vendor.'/'.$package.'/public';
		if(!is_dir($dst.'/base')){
			mkdir($dst.'/base');
		}if(!is_dir($dst.'/includes')){
			mkdir($dst.'/includes');
		}
		if(!is_dir($dst.'/pages')){
			mkdir($dst.'/pages');
		}
		if(!is_dir($pub.'/styles')){
			mkdir($pub.'/styles');
		}
		
		$this->recurse_copy($dir_workbench, $dst);
	}*/
	
	
	/*function artisanPublishAssets($vendor,$package) {
		ini_set ('max_execution_time', 0);
		\Log::info('<br>init artisan::publish assets...');
		$name=$vendor.'/'.$package;
		\Artisan::call('asset:publish', array('--bench' => ''.$name.''));
	    \Log::info( 'done artisan::publish assets');
	}*/

	/*function artisanCreateTemplate($vendor,$package) {
		ini_set ('max_execution_time', 0);
		\Log::info('<br>init artisan::workbench...');
		$name=$vendor.'/'.$package;
		//php artisan workbench vendor/package --resources
		\Artisan::call('workbench',array('package'=>''.$name.'','--resources'=>'resources'));
		\Log::info( 'done artisan::workbench');
	
	}*/
	
	/*function artisanCreateController($vendor,$packageName,$controller) {
		ini_set ('max_execution_time', 0);
		\Log::info('<br>init artisan::controller...');
		//php artisan workbench vendor/package --resources
		\Artisan::call('controller:make',array('name'=>$controller,'--bench'=>''.$vendor.'/'.$packageName.''));
		\Log::info( 'done artisan::controller');
	
	}*/
	
	
	
    

}
