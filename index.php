<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
	<title> Web Crawler</title>
</head>
<body style="background-color: ivory;">
	<div class="container text-center mt-4">
		<h1 class="display-1"> Web Crawler </h1>
	</div>

	<div class="container text-center">
		<?php
		// Turn off warnings
		error_reporting(E_ERROR | E_PARSE);

		$seed_url = "https://archive.org/web";
		//$seed_url = "https://www.yelp.com";
		$search_string = "tools";
		$max_depth = 4;
		// creating array to store the links
		$links = array();
		$count = 0;
		$depth = 0;
		$case_sensitive_search = false;
		?>

		<!-- basic html for headings and form -->
		<form method="post" id="search-form" action="">
			<!-- Seed url -->
			<div class="row justify-content-center mt-5">
				<div class="col-8"> <h3> Seed URL </h3> </div>
			</div> 
			<div class="row justify-content-center my-3">
				<div class="col-4">
					<input class="form-control" type="text" name="seed_url" value='<?php echo htmlspecialchars($seed_url); ?>'> 
				</div>
			</div>

			<!-- String to Search -->
			<div class="row justify-content-center mt-3">
				<div class="col-4"> <h4> String To Search </h4> </div>
				<div class="col-4"> <h4> Max Depth for Search </h4> </div>
			</div>
			<div class="row justify-content-center my-3">
				<div class="col-4">
					<input class="form-control" type="text" name="search_string" id="search_string" value="<?php echo htmlspecialchars($search_string); ?>" required> 
				</div>
				<div class="col-4">
					<input class="form-control" type="number" name="max-depth" value="<?php echo htmlspecialchars($max_depth); ?>" required> 
				</div>
				<div class="row justify-content-center mt-3">
					<label for="case-sensitivity">Case Sensitive Search</label>
    				<input type="checkbox" name="case-sensitivity" id="case-sensitivity">
				</div>

			</div>
			
			<!-- Submit Button -->
			<div class="row justify-content-center">
				<div class="col-4">
					<input type="submit" name="enter" class="mt-3 btn btn-secondary shadow px-4" id="string-to-search" value="Search">
				</div>
			</div>
		</form>
		<br>

		<?php
			// recursive function for opening pages and finding links
			function open_page($page_url)
			{
				global $links;
				global $count;
				global $depth;
				global $max_depth;

				// if depth is reached
				if ($depth >= $max_depth)
					return;

				else
				{
					// Fetch HTTP headers to check for errors
					$headers = get_headers($page_url);
					// Get the HTTP status code
					$httpStatusCode = (int) substr($headers[0], 9, 3);

					// Check for a 404 status code
					if ($httpStatusCode === 404) 
					{
						//if ($count == 0)
							exit("404 - Page Not Found Error");
					    echo "<br>The page does not exist (404 error).";
					    return;
					}

					else
					{
						// Create a DOMDocument object
						$dom = new DOMDocument;

						$content = file_get_contents($page_url);

						// Load the HTML content into the DOMDocument
						@$dom->loadHTML($content);

						// Create a DOMXPath object to query the DOM
						$xpath = new DOMXPath($dom);

						// Query for all anchor tags
						$anchorTags = $xpath->query('//a[@href[starts-with(.,"https")]]');

						// get contents of the robots.txt file
						$robots_txt = $page_url."/robots.txt";
						$robotsTxtContent = file_get_contents($robots_txt);
						//echo "$robotsTxtContent<br>";

						// Fetch HTTP headers to check for errors
						$headers = get_headers($robots_txt);
						// Get the HTTP status code
						$httpStatusCode = (int) substr($headers[0], 9, 3);

						// Check for a 404 status code
						if ($httpStatusCode === 404) 
						{
							if ($count == 0)
								exit("404 - Page Not Found Error");
						    echo "<h6><br> 404 ERROR -  robots.txt does not exist for this page<br></h6>";
						    return;
						}


						$i=0;
						foreach($anchorTags as $tag)
						{
							if ($count >= 10)
								return;
							if ($i >= 5 || $i>=$anchorTags->length)
								break;
							$href = $tag->getAttribute('href');
							//echo "$page_url/<br>";
							$check_href = explode('$page_url', $href);
							// if url't already exist in array
							if (!is_null($links))
								if (in_array($href, $links))
									continue;

							//echo "CHECK $page_url - $check_href[0]<br>";

							// check if robots.txt file allows us to add that url
							if (stripos($robotsTxtContent, $check_href[0]) !== false) 
							{
								//echo "<i>$robots_txt</i> NOT ALLOWED BY ROBOTS.TXT FILE<br>";
								continue;
							}

							// adding metadata to corresponding array
							$data[$count] = $metaTags;

							$i=$i+1;
							$count = $count + 1;
							$links[$count] = $href;

							//echo $page_url."/robots.txt<br>";
							
							// recrusively calling the function again on another url
							open_page($href);
						}
						$depth = $depth + 1;
					}
				}
			}

			// when search input is submitted
			//if (isset($_POST['enter']))
			if ($_SERVER['REQUEST_METHOD'] === 'POST')
			{
				global $max_depth;
				global $links;
				global $case_sensitive_search;
				global $seed_url;

				// getting the string to search
				$string_to_find = $_POST['search_string'];
				$url = $_POST['seed_url'];
				$max_depth = $_POST['max-depth'];

				// if case-sensitive search is unset
				if (isset($_POST['case-sensitivity']))
					$case_sensitive_search = true;


				if (!filter_var($url, FILTER_VALIDATE_URL))
					exit("'<i>$url</i>' is an Invalid URL Error");

				$links[0] = $url;
				$sensitivity = $case_sensitive_search? "case-sensitive": "case-insensitive";
				echo "<h4 style='color:grey' class='mt-2'> Searching for '<i>$string_to_find</i>' in <i> $seed_url <br> </i> using <i>$sensitivity search</i> with depth </i>$max_depth</i>  </h4><br>";

			?>

			<div id="hide" style="color:grey"> This might take a minute </div>

			<script>
				setTimeout(function() 
				{
				  document.getElementById("hide").style.display = 'none';
				}, 5000);
			</script>

			<?php
				open_page($url);
				crawler($string_to_find);
			}

			// function to search all the links for the string
			function crawler($string_to_find)
			{
				global $links;
				global $case_sensitive_search;
				$path = "content.txt";

				// write date and time to top of file
				file_put_contents($path, date("F j, Y, g:i a")."\n");

				foreach($links as $link)
				{
					// Create a DOMDocument object
					$dom = new DOMDocument();

					$content = file_get_contents($link);

					// Load the HTML content into the DOMDocument
					@$dom->loadHTML($content);

					// Create a DOMXPath object to query the DOM
					$xpath = new DOMXPath($dom);

					// getting metadata
					$title = $dom->getElementsByTagName('title')->item(0)->nodeValue;
					$paragraphs = $dom->getElementsByTagName('p');
					$description .= $paragraphs[0]->nodeValue . ' ';
					// Trim excess whitespace
    				$description = trim($description);

    				// write to file
					file_put_contents($path, $title . "\n", FILE_APPEND);
					file_put_contents($path, $link . "\n", FILE_APPEND);
					file_put_contents($path, $description . "\n", FILE_APPEND);

					echo "<h3><a href=$link>$title</a></h3>
					<div style='color:grey'><i>$link</i></div>
					<p>$description</p>";


					if ($case_sensitive_search === true)
					{
						// searching for the string in the webpage (CASE SENSITIVE)
						if (strpos($content, $string_to_find) !== false) 
						{
							$occurreces = substr_count($content, $string_to_find);
						    echo "<b><i>String occurs $occurreces times.</b></i><br>";
						    file_put_contents($path, "String occurs $occurreces times." . "\n\n", FILE_APPEND);
						} 
						else 
						{
						    echo "<span style='font-weight:200px'><b><i>String NOT found</b></i></span><br>";
						    file_put_contents($path, "String NOT found" . "\n\n", FILE_APPEND);
						}
					}
					else
					{
						// searching for the string in the webpage (CASE INSENSITIVE)
						if (stripos($content, $string_to_find) !== false) 
						{
							$occurreces = substr_count(strtolower($content), strtolower($string_to_find));
						    echo "<b><i>String occurs $occurreces times.</b></i><br>";
						    file_put_contents($path, "String occurs $occurreces times." . "\n\n", FILE_APPEND);
						} 
						else 
						{
						    echo "<span style='font-weight:200px'><b><i>String NOT found</b></i></span><br>";
						    file_put_contents($path, "String NOT found" . "\n\n", FILE_APPEND);
						}
					}
					echo "<br>";
				}

			}
		?>
	</div>

</body>
</html>