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
			//$url = "https://en.wikipedia.org/wiki/Microsoft";
			$url = "https://archive.org/web/";
			// creating array to store the links
			$links = array();
			$count = 0;
			$depth = 0;
		?>

		<!-- basic html for headings and form -->
		<form method="post">
			<!-- Seed url -->
			<div class="row justify-content-center mt-5">
				<div class="col-8"> <h3> Seed URL </h3> </div>
			</div> 
			<div class="row justify-content-center my-3">
				<div class="col-4">
					<input class="form-control" type="text" name="seed_url" value="https://archive.org/web/"> 
				</div>
			</div>

			<!-- String to Search -->
			<div class="row justify-content-center mt-3">
				<div class="col-4"> <h4> String To Search </h4> </div>
				<div class="col-4"> <h4> Max Depth for Search </h4> </div>
			</div>
			<div class="row justify-content-center my-3">
				<div class="col-4">
					<input class="form-control" type="text" name="search_string" value="tools" required> 
				</div>
				<div class="col-4">
					<input class="form-control" type="number" name="max-depth" value=4 required> 
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
						if ($count == 0)
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


						$i=0;
						foreach($anchorTags as $tag)
						{
							if ($count >= 10)
								return;
							if ($i >= 5 || $i>=$anchorTags->length)
								break;
							$href = $tag->getAttribute('href');
							// if url't already exist in array
							if (!is_null($links))
								if (in_array($href, $links))
									continue;

							// adding metadata to corresponding array
							$data[$count] = $metaTags;

							$i=$i+1;
							$count = $count + 1;
							$links[$count] = $href;
							
							// recrusively calling the function again on another url
							open_page($href);
						}
						$depth = $depth + 1;
					}
				}
			}

			// when search input is submitted
			if (isset($_POST['enter']))
			{
				global $max_depth;
				global $links;

				// getting the string to search
				$string_to_find = $_POST['search_string'];
				$url = $_POST['seed_url'];
				$max_depth = $_POST['max-depth'];

				echo "$url<br>";
				if (!filter_var($url, FILTER_VALIDATE_URL))
					exit("Invalid URL Error");

				$links[0] = $url;
				echo "<h4 style='color:grey' class='mt-2'> Searching for '<i>$string_to_find</i>' </h4>";

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
					

					echo "<h3><a href=$link>$title</a></h3>
					<div style='color:grey'><i>$link</i></div>
					<p>$description</p>";


					// searching for the string in the webpage
					if (stripos($content, $string_to_find) !== false) 
					{
						$occurreces = substr_count($content, $string_to_find);
					    echo "<b><i>String occurs $occurreces times.</b></i><br>";
					} 
					else 
					    echo "<span style='font-weight:200px'><b><i>String NOT found</b></i></span><br>";
					echo "<br>";
				}
			}
		?>
	</div>

</body>
</html>