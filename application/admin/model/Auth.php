<?php
namespace app\admin\model;
use think\Model;
class Auth extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "auth_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//在模型类的init方法里面统一注册模型事件
	protected static function init(){
		
		//新增前钩子
        Auth::event('before_insert', function ($auth) {
        	//在完成新增前先判断权限是否为顶级,如果为顶级则不需要添加控制器与方法
        	if($auth['pid']==0){
        		//删除控制器与方法字段
        		unset($auth['auth_c']);
				unset($auth['auth_a']);
        	}
        });
	}
	
	//递归重组排序查询出来的权限方法
	public function recursionAuth($auth,$pid=0,$level=0){
		static $newAuth = [];   //定义一个静态变量用于保存重组的权限
		foreach($auth as $v){
			if($v['pid']==$pid){
				$v['level']=$level;
				$newAuth[]=$v;
				//递归重组
				$this->recursionAuth($auth,$v['auth_id'],$level+1);
			}
		}
		//返回重组后的数据
		return $newAuth;
	}
}
?>