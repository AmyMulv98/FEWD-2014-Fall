<?

	session_start();

	// Figure out www_root
	$url = "http" . (($_SERVER["HTTPS"]) ? "s://" : "://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$cwd = end(explode("/", strtolower(getcwd())));
	$www_root = substr($url, 0, strpos($url, $cwd) + strlen($cwd)) . "/";

	// Get requested URL
	$current_url = $_GET["request_url"];
	$path = cleanURL(str_ireplace($www_root, "", $current_url));

	// Compute route
	$route = routeRecursive("templates/", explode("/", $path));

	// Build the page
	ob_start();

	if ($route["commands"][0] == "") {
		unset($route["commands"]);
	}

	if (file_exists("templates/" . $route["path"] . ".php") && count($route["commands"]) == 0) {
		include "templates/" . $route["path"] . ".php";
	} else {
		include "templates/_404.php";
	}

	$content = ob_get_clean();

	include "templates/_header.php";
	echo $content;
	include "templates/_footer.php";


	// Functions
	function routeRecursive($base = "templates/", $route, $level = 0) {
		$router = "";
		for ($i = 0; $i < $level; $i++) {
			$router .= $route[$i] . "/";
		}

		$return = array();

		if (is_file($base . $router . $route[$level] . ".php")) {
			$return["path"] = $router . $route[$level];
			$return["commands"] = array_slice($route, $level + 1);
		} else if (is_dir($base . $router)) {
			if (isset($route[$level])) {
				$return = routeRecursive($base, $route, $level+1);
				if (!$return && is_file($base . $router . "default.php")) {
					$return["path"] = $router . "default";
					$return["commands"] = array_slice($route, $level);
				}
			} else if (is_file($base . $router . "default.php")) {
				$return["path"] = $router . "default";
				$return["commands"] = array_slice($route, $level);
			}
		}

		if (!$return && $level == 0) {
			$return["path"] = "home";
			$return["commands"] = array_slice($route, $level);
		}

		return $return;
	}

	function cleanURL($url) {
		if (substr($url, strlen($url) - 1, strlen($url)) == '/') {
			$url = substr($url, 0, strlen($url) - 1);
		}

		return $url;
	}

?>