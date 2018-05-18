<?php
// echo $twig->render('validation.html', array(''));

class Controller_download extends Controller{

	protected $memeData;
	protected $memeDb;

	public function __construct($memeData = null){
		parent::__construct();
		$this->memeData = $memeData;
		$this->memeDb = new MemeDbManager();
	}
	// @param array $memeData: topText, bottomText, textColor, textSize, url, id 
	public function genereAndDownload(){
		// constructeur attend : $id, $url, $topText, $bottomText, $fontSize, $textColor1, $textColor2
		$memeData = $this->memeData;
		$memeGen = new MemeGenerator(	$memeData['id'],
										$memeData['url'],
										$memeData['topText'],
										$memeData['bottomText'],
										$memeData['textSize'],
										$memeData['textColor1'],
										$memeData['textColor2']);

		$idImage = $memeData['id'];
		$testJpg = "/\.jpg$/";
		$testPng = "/\.png$/";
		if (preg_match($testJpg, $memeData['url'])){
			$newMemeName = $memeGen->generateMemeFromJPG();
		} elseif (preg_match($testPng, $memeData['url'])){
			$newMemeName = $memeGen->generateMemeFromPNG();
		}
		$idMeme = $this->memeDb->create($newMemeName);
		$linkToImage = new MemeByImageManager();
		$linkToImage->insert($idImage, $idMeme);
		$this->action_render($idMeme);

	}
	public function action_render($idMeme){
		echo "On y est!!";
		$memeToLayout = $this->memeDb->read($idMeme);
		var_dump($memeToLayout);
		echo $this->twig->render('validation.html', array('meme' => $memeToLayout));

	}
}
