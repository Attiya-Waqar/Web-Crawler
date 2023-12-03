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
			$links = array($url);
			$count = 0;
		
		?>

		<!-- basic html for headings and form -->
		<form method="post">
			<!-- Seed url -->
			<div class="row justify-content-center mt-5">
				<div class="col-8"> <h6 class="display-6"> Seed URL </h6> </div>
			</div> 
			<div class="row justify-content-center my-4">
				<div class="col-4">
					<input class="form-control" type="text" name="seed_url" value="https://archive.org/web/"> 
				</div>
			</div>

			<!-- String to Search -->
			<div class="row justify-content-center mt-5">
				<div class="col-8"> <h6 class="display-6"> String To Search </h6> </div>
			</div>
			<div class="row justify-content-center my-4">
				<div class="col-4">
					<input class="form-control" type="text" name="search_string" placeholder="string to search" required> 
				</div>
			</div>
			
			<!-- Submit Button -->
			<div class="row justify-content-center my-4">
				<div class="col-4">
					<input type="submit" name="enter" class="mt-3 btn btn-secondary" id="string-to-search" value="Search">
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

				// if depth is reached
				if ($count >= 10)
					return;

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

						$i=$i+1;
						$count = $count + 1;
						$links[$count] = $href;
						
						// recrusively calling the function again on another url
						open_page($href);
					}
				}
			}

			// if search input is submitted
			if (isset($_POST['enter']))
			{
				// getting the string to search
				$string_to_find = $_POST['search_string'];
				$url = $_POST['seed_url'];
				echo "<h3> Searching for '<i>$string_to_find</i>' </h3>";
				echo "This might take a minute<br>";
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
					$dom = new DOMDocument;

					$content = file_get_contents($link);

					// Load the HTML content into the DOMDocument
					@$dom->loadHTML($content);

					// Create a DOMXPath object to query the DOM
					$xpath = new DOMXPath($dom);

					// searching for the string in the webpage
					if (stripos($content, $string_to_find) !== false) 
					{
						$occurreces = substr_count($content, $string_to_find);
					    echo "String occurs $occurreces times in the web page <i>$link</i><br>";
					} 
					else 
					    echo "<span style='font-weight:200px'>String not found in the web page <i>$link</i></span><br>";
				}
			}
		?>
	</div>

</body>
</html>