<?php
namespace app\api\controller;
use think\Image;
class Upload
{
	public $photoRealFile = 'uploads';
	public $photoPublicName = 'photo';
	public function logo()
	{
		$file = request()->file('logo');
		if (is_null($file)) {
			return error(400,'missing file!');
		}
		if (!$file->checkExt('jpg,png,gif') || !$file->checkSize(1000000)) {
			return error(400,'图片大小或格式不符合要求');
		}
		//hash值
		$hash = $file->sha1();
		//查看是否有这张图片
		$photo = model('photo')
							 //  ->union(function ($query) {
								// 	$query->field('path')->table('photo');
								// })
							  ->field('path')
							  ->get(['hash'=>$hash]);

		if ($photo) {
			return success($photo['path']);
		}

		//创建目录
		$root = '..' . DIRECTORY_SEPARATOR . $this->photoRealFile;
		$save_path = $root . DIRECTORY_SEPARATOR . date('Ymd');
		$public_root = DIRECTORY_SEPARATOR . $this->photoPublicName;
		if (!is_dir($path)) {
			mkdir($save_path,0755,true);
		}
		
		$real_path = $save_path . DIRECTORY_SEPARATOR .md5(microtime(true)) . '.jpg';
		$abs_path = $public_root . DIRECTORY_SEPARATOR . date('Ymd');

		//保存图片
		$image = Image::open($file)
					->thumb(150,150)
					->save($real_path);

		$data = [
			'path'=>$abs_path,
			'hash'=>$hash
		];

		$pid = model('photo')->insert($data);

		if ($pid) {
			return success($abs_path);
		}
	}

	public function photo()
	{
		$file = request()->file('photo');
		if (is_null($file)) {
			return error(400,'missing file!');
		}
		if (!$file->checkExt('jpg,png,gif,webp,jpeg,bmp') || !$file->checkSize(1000000)) {
			return error(400,'图片大小或格式不符合要求');
		}
		//hash值
		$hash = $file->sha1();
		//查看是否有这张图片
		$photo = model('photo')
							 //  ->union(function ($query) {
								// 	$query->field('path')->table('logo');
								// })
							  ->field('path')
							  ->get(['hash'=>$hash]);
		if ($photo) {
			return success($photo['path']);
		}

		$info = $file->rule('date')->move('..' . DIRECTORY_SEPARATOR . $this->photoRealFile);

		$abs_path = DIRECTORY_SEPARATOR . $this->photoPublicName . DIRECTORY_SEPARATOR .$info->getSaveName();

		$data = [
			'path'=>$abs_path,
			'hash'=>$hash
		];

		$pid = model('photo')->insert($data);

		if ($pid) {
			return success($abs_path);
		}
	}

	public function file()
	{
		
	}
}