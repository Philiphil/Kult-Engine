<?php
 class Syslog
	{
		var $_facility; // 0-23
		var $_severity; // 0-7
		var $_hostname; // no embedded space, no domain name, only a-z A-Z 0-9 and other authorized characters
		var $_fqdn;
		var $_ip_from;
		var $_process;
		var $_content;
		var $_msg;
		var $_server;   // Syslog destination server
		var $_port;     // Standard syslog port is 514
		var $_timeout;  // Timeout of the UDP connection (in seconds)
		
		function Syslog($facility = 1, $severity = 6, $hostname = "", $fqdn= "", $ip_from = "", $process="", $content = "")
		{
			$this->_msg      = '';
			$this->_server   = constant('ip_serveur_log');
			$this->_port     = 514;
			$this->_timeout  = 10;
			
			$this->_facility = $facility;
			
			$this->_severity = $severity;
			
			$this->_hostname = $hostname;
			if ($this->_hostname == "")
			{
				if (isset($_ENV["COMPUTERNAME"]))
				{
					$this->_hostname = $_ENV["COMPUTERNAME"];
				}
				elseif (isset($_ENV["HOSTNAME"]))
				{
					$this->_hostname = $_ENV["HOSTNAME"];
				}
				else
				{
					$this->_hostname = "WEBSERVER";
				}
			}
			$this->_hostname = substr($this->_hostname, 0, strpos($this->_hostname.".", "."));
			
			$this->_fqdn = $fqdn;
			if ($this->_fqdn == "")
			{
				if (isset($_SERVER["SERVER_NAME"]))
				{
					$this->_fqdn = $_SERVER["SERVER_NAME"];
				}
			}

			$this->_ip_from = $ip_from;
			if ($this->_ip_from == "")
			{
				if (isset($_SERVER["SERVER_ADDR"]))
				{
					$this->_ip_from = $_SERVER["SERVER_ADDR"];
				}
			}

			$this->_process = $process;
			if ($this->_process == "")
			{
				$this->_process = "PHP";
			}

			$this->_content = $content;
			if ($this->_content == "")
			{
				$this->_content = "PHP generated message";
			}
			
		}

		function SetFacility($facility)
		{
			$this->_facility = $facility;
		}
		
		
		function SetSeverity($severity)
		{
			$this->_severity = $severity;
		}
		
		
		function SetHostname($hostname)
		{
			$this->_hostname = $hostname;
		}
		
		
		function SetFqdn($fqdn)
		{
			$this->_fqdn = $fqdn;
		}
		
		
		function SetIpFrom($ip_from)
		{
			$this->_ip_from = $ip_from;
		}
		
		
		function SetProcess($process)
		{
			$this->_process = $process;
		}
		
		
		function SetContent($content)
		{
			$this->_content = $content;
		}
		
		
		function SetMsg($msg)
		{
			$this->_msg = $msg;
		}
		
		
		function SetServer($server)
		{
			$this->_server = $server;
		}
		
		
		function SetPort($port)
		{
			if ((intval($port) > 0) && (intval($port) < 65536))
			{
				$this->_port = intval($port);
			}
		}


		function SetTimeout($timeout)
		{
			if (intval($timeout) > 0)
			{
				$this->_timeout = intval($timeout);
			}
		}
		
		
		function Send($server = "", $content = "", $timeout = 0)
		{
			if ($server != "")
			{
				$this->_server = $server;
			}

			if ($content != "")
			{
				$this->_content = $content;
			}
			
			if (intval($timeout) > 0)
			{
				$this->_timeout = intval($timeout);
			}
			
			if ($this->_facility <  0) { $this->_facility =  0; }
			if ($this->_facility > 23) { $this->_facility = 23; }
			if ($this->_severity <  0) { $this->_severity =  0; }
			if ($this->_severity >  7) { $this->_severity =  7; }
			
			$this->_process = substr($this->_process, 0, 32);
			
			$actualtime = time();
			$month      = date("M", $actualtime);
			$day        = substr("  ".date("j", $actualtime), -2);
			$hhmmss     = date("H:i:s", $actualtime);
			$timestamp  = $month." ".$day." ".$hhmmss;
			
			$pri    = "<".($this->_facility*8 + $this->_severity).">";
			$header = $timestamp." ".$this->_hostname;
			
			if ($this->_msg != "")
			{
				$msg = $this->_msg;
			}
			else
			{
				$msg = $this->_process.": ".$this->_fqdn." ".$this->_ip_from." ".$this->_content;
			}
			
			$message = substr($pri.$header." ".$msg, 0, 1024);
			
			$fp = fsockopen("udp://".$this->_server, $this->_port, $errno, $errstr);
			if ($fp)
			{
				fwrite($fp, $message);
				fclose($fp);
				$result = $message;
			}
			else
			{
				$result = "ERROR: $errno - $errstr";
			}
			return $result;
		}
	}

?>