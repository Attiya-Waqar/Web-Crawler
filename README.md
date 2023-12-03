# Assignment02 - Web-Crawler

<b>Name</b> : Attiya Waqar <br>
<b>CMS ID</b> : 369841 <br>
<b>Class</b> : BESE-12A <br>

<h1> Working </h1>
<h3> Technologies </h3>
<ul>
  <li> Github for Version Control </li>
  <li> Bootstrap for basic styling </li>
  <li> HTML for basic structure </li>
  <li> PHP for server-side operations </li>
  <li> World Wide Web for searching</li>
  <li> PHP for file handling</li>
</ul>

<h3> Concepts </h3>
<ul>
  <li> Linking content in the world wide web</li>
  <li> Input Validation </li>
  <li> Error Handling </li>
  <li> Recursive Search </li>
  <li> String matching </li>
  <li> Retrieving and displaying specific content from remote web pages</li>
  <li> Following guidelines of the <i>robots.txt</i> files</li>
  <li> Storing data in flat file</li>
  
</ul>

<h3> Implementation </h3>

<h1>User Input</h1> 
<p> 
  The interface provides simple and self-expanatory input fields. Where<br>
  <b> Seed URL </b> : The seed url to start the crawling at <br>
  <b> String to Search </b> : The string to look for while crawling<br>
  <b> Max Depth for Search </b> : Setting a maximum depth level for the crawling<br><br>
  <h3> Default Values </h3>
  <b> Seed URL </b> : https://archive.org/web/ <br>
  <b> String to Search </b> : "tools" <br>
  <b> Max Depth for Search </b> : 4<br><br><br>
  <img width="1059" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/9c3b5d8e-1c64-4226-8d42-c474eb65b2a1">
</p><br>

<h1> Validating User Input</h1>
<p>
  <h3> The script checks for whether the url entered by user is valid or not </h3>
  <img width="582" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/e6901786-a9c8-42f5-a27a-093e4013b68c">
  <img width="659" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/ace6d11a-e626-4414-8a82-33f38e582395">
<br>
<h3> The script ensures an input string is entered for the search </h3>
  <img width="479" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/6c0e0a3d-7151-4467-ac47-66b2064a9fa9">

<br>
<h3> The "depth" field ensures only numbers are entered </h3>
  <img width="445" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/e2f9e097-4614-4d30-9893-b98c8081d148">
</p><br>

<h1> Writing Results to a Flat File</h1>
<img width="1229" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/98c24e9a-7a42-4b0c-a82e-67508f5bbd8a">
<img width="1420" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/98296415-dd8b-4d1d-97d7-931333543772">




<br>
<h1> Search Results For Default Input</h1>
<img width="1440" alt="image" src="https://github.com/Attiya-Waqar/Web-Crawler/assets/107126273/02203ece-d669-483a-bb0e-d5e46499b633">
