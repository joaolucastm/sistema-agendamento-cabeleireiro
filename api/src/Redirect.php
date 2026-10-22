<?php

class Redirect
{	
	public static function to(string $url): void
	{
		header("Location: $url");
		exit;
	}
	
	public static function back(): void
	{
		header("Location: {$_SERVER['HTTP_REFERER']}");
		exit;
	}
	
	public static function self(): void
	{
		header("Location: {$_SERVER['REQUEST_URI']}");
		exit;
	}
}
