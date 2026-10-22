<?php

class Notification
{	
	public static function set(string $message, string $status): void
	{
		$_SESSION['notification'] = [
			'message' => $message,
			'status' => $status
		];
	}
	
	public static function show(): void
	{
		if (isset($_SESSION['notification']))
		{
			echo "<div class='notification {$_SESSION['notification']['status']}'>{$_SESSION['notification']['message']}</div>";
			unset($_SESSION['notification']);
		}
	}
}
