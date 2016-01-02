<?php

class Util {
	static $base32chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";

	static function GenerateRandomSecret() {
		$secret = "";
		for ($idx = 0; $idx < 16; $idx++) {
			$secret .= self::$base32chars[ rand(0,strlen(self::$base32chars) - 1) ];
		}
		return $secret;
	}

	static function LeftPad($str, $len, $pad) {
	    if ($len + 1 >= strlen($str)) {
	    	$padding = array_fill(0, $len + 1 - strlen($str), "");
	    	$str = implode($pad, $padding) . $str;
	    }
	    return $str;
	}

	static function Base32ToHex($base32) {
	    $bits = "";
	    $hex = "";
	    $n = strlen($base32);
	    for ($idx = 0; $idx < $n; $idx++) {
	    	$val = strpos(self::$base32chars, strtoupper($base32[$idx]));
	        $bits .= self::LeftPad(decbin($val), 5, '0');
	    }
	    for ($idx = 0; $idx + 4 <= strlen($bits); $idx += 4) {
	        $chunk = substr($bits, $idx, 4);
	        $hex = $hex . dechex(bindec($chunk));
	    }
	    return $hex;
	} 

	static function GenerateOTP($secret) {
		$key = self::Base32ToHex($secret);
	    $epoch = time();
	    $time = self::LeftPad(dechex(floor($epoch / 30)), 16, '0');
	    $hmac = hash_hmac('sha1', hex2bin($time), hex2bin($key));
	    $offset = hexdec( substr($hmac, strlen($hmac) - 1) );
	    $otp = hexdec(substr($hmac, $offset * 2, 8)) & hexdec('7fffffff');
	    $sOtp = "" . $otp;
	    return substr($sOtp, strlen($sOtp) - 6, 6);
	}

	static function ValidUsername($username) {
		global $db;
		//Max length 20 chars
		if (mb_strlen($username) > 20) {
			throw new Exception("Username must be 20 characters or less", 1);
		}
		//Check unique username
		if ($db->has('users', ['username' => $username])) {
			throw new Exception("Username already used", 2);	
		}
		return true;
	}

	static function CreateUser($username, $password) {
		global $db;
		$hash = password_hash($password, PASSWORD_BCRYPT);
		$db->insert('users', ['username' => $username, 'password' => $hash]);
	}

	static function Login($username, $password) {
		global $db;
		$user = $db->get('users', ['password', 'secret'], ['username' => $username]);
		if ($user == false) {
			throw new Exception("User not found", 1);
		}
		$ok = password_verify($password, $user['password']);
		if (!$ok) {
			throw new Exception("Wrong password", 1);
		}
		if ($user['secret'] == null) {
			return false;
		} else {
			return true;
		}
	}

	static function User() {
		global $db;
		if ($_SESSION['username'] == null) {
			return false;
		}
		return $db->get('users', '*', ['username' => $_SESSION['username']]);
	}
}