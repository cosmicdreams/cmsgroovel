<?php namespace dao;


class ContentTypeDao implements \ContentTypeDaoInterface{

	public function find($contentTypeid){
		return \AllContentTypes::find($contentTypeid);
		
	}
	
	public function findAllContentTypeByName($contentTypeName){
		$type=\AllContentTypes::where('name', '=', $contentTypeName)->first();
		return $type;
	}
	
	public function createType($tableName){
		$type=new \AllContentTypes();
		$type->name=$tableName;
		$type->type='content';
		$type->author_id=\Auth::id();
		$type->save();
		return $type;
	}
	
	
	public function create($tableName,$fieldNames,$descriptions,$types,$type,$widget,$isnullable,$required){
	 		$contentType= new \ContentTypes();
            $contentType->tableName=$tableName;
            $contentType->fieldName=$fieldNames;
            $contentType->description=$descriptions;
            $contentType->type=$types;
            $contentType->widget=$widget;
            $contentType->content_type=$type;
            $contentType->isnullable=$isnullable;
            $contentType->required=$required;
            $contentType->save();
            return $contentType;
	}
	
	public function findContentTypeByName($tableName){
		$contentType= new \ContentTypes();
		//$result=$contentType->find($tableName);
		$result=\ContentTypes::where('tableName', '=', $tableName)->get();
		return $result;
	}
	
	public function findAllContentType(){
		$lists =\AllContentTypes::where('type','!=','system')->get();
		return $lists;
	}
	
	public function getContentTypeNameById($id){
		return \AllContentTypes::where('id','=',$id)->first();
	}
	
	public function findAllSystemContentType(){
		$lists =\AllContentTypes::where('type','=','system')->get();
		return $lists;
	}
	
	public function findContentTypeById($id){
		return  \ContentTypes::where('content_type', '=', $id)->get();
  	}
  	
  	public function deleteField($fieldName){
  		$contentType= \ContentTypes::where('fieldName', '=', $fieldName);
  		$contentType->delete();
  	}

  	public function paginateContentType(){
  		return \AllContentTypes::where('type','!=','system')->paginate(15);
  	}
  	
  	public function findContentTypeByFieldId($id){
  		return \ContentTypes::find($id);
  	}
  	
  	public function updateContentType($contentType,$fieldName,$typeSelected,$widgetSelected,$description,$isnullable,$required){
  		$contentType->fieldName=$fieldName;
  		$contentType->type=$typeSelected;
  		$contentType->widget=$widgetSelected;
  		$contentType->description=$description;
  		$contentType->isnullable=$isnullable;
  		$contentType->required=$required;
  		$contentType->save();
  		return $contentType;
  	}
  	
  	public function getAllContentTypes($name){
  		$contentType= \AllContentTypes::where('name', '=', $name);
  		return $contentType;
  	}
  	
  	public function findContentsByType($name){
  		$contentType= \AllContentTypes::where('name', '=', $name)->first();
  		if($contentType!=null){
  		return \Contents::where('type_id', '=', $contentType->getId())->get();
  		}else return null;
  	}
  
}