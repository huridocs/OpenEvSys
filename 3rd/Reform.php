<?php

/* 
 * Copyright (c) 2005-2006 Michael Eddington
 * Copyright (c) 2004 IOActive Inc. 
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy  
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights  
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell  
 * copies of the Software, and to permit persons to whom the Software is  
 * furnished to do so, subject to the following conditions: 
 * 
 * The above copyright notice and this permission notice shall be included in  
 * all copies or substantial portions of the Software. 
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR  
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,  
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE  
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER  
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
 * SOFTWARE. 
 * 
 * Authors:
 *   Michael Eddington (meddington@phed.org)
 *
 * $Id$
 */

class Reform
{
	static $haveUnicode = false;
	
	function unichr($u)
	{
		if(Reform::$haveUnicode == true)
		{
			return mb_convert_encoding(pack("N",$u), 'UTF-8', 'UCS-4BE');
		}
		
		return chr($u);
	}
	
	function uniord($u)
	{
		if(Reform::$haveUnicode == true)
		{
			$c = unpack("N", mb_convert_encoding($u, 'UCS-4BE', 'UTF-8'));
			return $c[1];
		}
		
		return ord($u);
	}
	
	function unicharat($str, $cnt)
	{
		if(Reform::$haveUnicode == true)
		{
			return mb_substr($str, $cnt, 1);
		}
		
		return substr($str, $cnt, 1);
	}
	
	function HtmlEncode($str, $default = '')
	{
		if(empty($str))
		{
			$str = $default;
		}
		
	 	settype($str, 'string');
		
		$out = '';
		$len = mb_strlen($str);
		
		// Allow: a-z A-Z 0-9 SPACE , .
		// Allow (dec): 97-122 65-90 48-57 32 44 46
		
		for($cnt = 0; $cnt < $len; $cnt++)
		{
			$c = Reform::uniord(Reform::unicharat($str, $cnt));
			if( ($c >= 97 && $c <= 122) ||
				($c >= 65 && $c <= 90 ) ||
				($c >= 48 && $c <= 57 ) ||
				$c == 32 || $c == 44 || $c == 46 )
			{
				$out .= Reform::unicharat($str, $cnt);
			}
			else
			{
				$out .= "&#$c;";
			}
		}
		
		return $out;
	}

	function HtmlAttributeEncode($str, $default = '')
	{
		if(empty($str))
		{
			$str = $default;
		}
		
	 	settype($str, 'string');
		
		$out = '';
		$len = mb_strlen($str);
		
		// Allow: a-z A-Z 0-9
		// Allow (dec): 97-122 65-90 48-57
		
		for($cnt = 0; $cnt < $len; $cnt++)
		{
			$c = Reform::uniord(Reform::unicharat($str, $cnt));
			if( ($c >= 97 && $c <= 122) ||
				($c >= 65 && $c <= 90 ) ||
				($c >= 48 && $c <= 57 ) )
			{
				$out .= Reform::unicharat($str, $cnt);
			}
			else
			{
				$out .= "&#$c;";
			}
		}
		
		return $out;
	}

	function XmlEncode($str, $default = '')
	{
		if(empty($str))
		{
			$str = $default;
		}
		
	 	settype($str, 'string');
		
		$out = '';
		$len = mb_strlen($str);
		
		// Allow: a-z A-Z 0-9 SPACE , .
		// Allow (dec): 97-122 65-90 48-57 32 44 46
		
		for($cnt = 0; $cnt < $len; $cnt++)
		{
			$c = Reform::uniord(Reform::unicharat($str, $cnt));
			if( ($c >= 97 && $c <= 122) ||
				($c >= 65 && $c <= 90 ) ||
				($c >= 48 && $c <= 57 ) ||
				$c == 32 || $c == 44 || $c == 46 )
			{
				$out .= Reform::unicharat($str, $cnt);
			}
			else
			{
				$out .= "&#$c;";
			}
		}
		
		return $out;
	}

	function XmlAttributeEncode($str, $default = '')
	{
		if(empty($str))
		{
			$str = $default;
		}
		
	 	settype($str, 'string');
		
		$out = '';
		$len = mb_strlen($str);
		
		// Allow: a-z A-Z 0-9
		// Allow (dec): 97-122 65-90 48-57
		
		for($cnt = 0; $cnt < $len; $cnt++)
		{
			$c = Reform::uniord(Reform::unicharat($str, $cnt));
			if( ($c >= 97 && $c <= 122) ||
				($c >= 65 && $c <= 90 ) ||
				($c >= 48 && $c <= 57 ) )
			{
				$out .= Reform::unicharat($str, $cnt);
			}
			else
			{
				$out .= "&#$c;";
			}
		}
		
		return $out;
	}
	
	function JsString($str, $default = '')
	{
		if(empty($str))
		{
			$str = $default;
			
			if(empty($str))
			{
				return "''";
			}
		}
		
	 	settype($str, 'string');
		
		$out = "'";
		$len = mb_strlen($str);
		
		// Allow: a-z A-Z 0-9 SPACE , .
		// Allow (dec): 97-122 65-90 48-57 32 44 46
		
		for($cnt = 0; $cnt < $len; $cnt++)
		{
			$c = Reform::uniord(Reform::unicharat($str, $cnt));
			if( ($c >= 97 && $c <= 122) ||
				($c >= 65 && $c <= 90 ) ||
				($c >= 48 && $c <= 57 ) ||
				$c == 32 || $c == 44 || $c == 46 )
			{
				$out .= Reform::unicharat($str, $cnt);
			}
			elseif( $c <= 127 )
			{
				$out .= sprintf('\x%02X', $c);
			}
			else
			{
				$out .= sprintf('\u%04X', $c);
			}
		}
		
		return $out . "'";
	}
	
	function VbsString($str, $default = '')
	{
		if(empty($str))
		{
			$str = $default;
			
			if(empty($str))
			{
				return '""';
			}
		}
		
	 	settype($str, 'string');
		
		$out = '';
		$inStr = false;
		$len = mb_strlen($str);
		
		// Allow: a-z A-Z 0-9 SPACE , .
		// Allow (dec): 97-122 65-90 48-57 32 44 46
		
		for($cnt = 0; $cnt < $len; $cnt++)
		{
			$c = Reform::uniord(Reform::unicharat($str, $cnt));
			if( ($c >= 97 && $c <= 122) ||
				($c >= 65 && $c <= 90 ) ||
				($c >= 48 && $c <= 57 ) ||
				$c == 32 || $c == 44 || $c == 46 )
			{
				if(! $inStr)
				{
					$inStr = true;
					$out .= '&"';
				}
				
				$out .= Reform::unicharat($str, $cnt);
			}
			else
			{
				if(! $inStr)
				{
					$out .= sprintf('&chrw(%d)', $c);
				}
				else
				{
					$out .= sprintf('"&chrw(%d)', $c);
					$inStr = false;
				}
			}
		}
		
		return ltrim($out, '&') . ($inStr ? '"' : '');
	}
}

Reform::$haveUnicode = false;
if(function_exists('mb_convert_encoding'))
{
	if(mb_internal_encoding() == "UTF-8")
	{
		Reform::$haveUnicode = true;
	}
	else
	{
		trigger_error("Reform requires UTF-8 internal encoding, disabling unicode support", E_USER_WARNING);
	}
}
else
{
	trigger_error("Reform unicode support requires multibute string module, disabling unicode support", E_USER_WARNING);
}


// end
?>