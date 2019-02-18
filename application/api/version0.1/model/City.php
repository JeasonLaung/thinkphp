<?php
namespace app\api\model;

use think\Model;

class City extends Model
{
	protected $hidden = ['province_id'];

	public function getByAlpha($value='')
	{

		$all = $this->field(['*','LEFT(pinyin,1)'=>'AtoZ'])

					->order('pinyin')
					->select();
		$return = [];
    	foreach ($all as $one) {
			$AtoZ = $one['AtoZ'];
			if (!empty($AtoZ)) {
				if (!array_key_exists($AtoZ, $return)) {
					$return[$AtoZ] = [];
				}
				unset($one['AtoZ']);
				array_push($return[$AtoZ], $one);
			}
		}
		return $return;
	}
}