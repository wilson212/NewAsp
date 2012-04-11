<?php
class Home
{
	public function Init() 
	{
		$Template = load_class('Template');
		$Template->set('contents', htmlspecialchars( file_get_contents( ROOT . DS . '_readme.txt' )) );
		$Template->render('home');
	}
}
?>