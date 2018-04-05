<?php
$keyspace  = 'roopansh';
$cluster = Cassandra::cluster()
->withContactPoints('127.0.0.1')
->withPort(9042)
->build();
$session  = $cluster->connect($keyspace);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Twitter-Cassandra</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
</head>
<body>

	<nav class="navbar navbar-dark bg-dark">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="/twitter-cassandra/" style="font-family: 'Satisfy', cursive; font-size: 40px;">Twitter-Cassandra</a>
			</div>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<ul class="nav nav-tabs">
				<li class="active">
					<a data-toggle="tab" href="#q1">Query 1</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q2">Query 2</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q3">Query 3</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q4">Query 4</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q5">Query 5</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q6">Query 6</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q7">Query 7</a>
				</li>
				<li>
					<a data-toggle="tab" href="#q8">Query 8</a>
				</li>
				<li>
					<a data-toggle="tab" href="#t1">TEST 1</a>
				</li>
				<li>
					<a data-toggle="tab" href="#t2">TEST 2</a>
				</li>
			</ul>


			<!-- QUERY 1  -->

			<div class="tab-content">
				<div id="q1" class="tab-pane fade in active">
					<h3>
						Given an author name, display all tweets posted by that author sorted by decreasing order of date and time. The details of the tweet must include the tweet Id, tweet text, tweet author Id, tweet location and tweet language.
					</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q1';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="author_name">Author Name:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="author_name" placeholder="Enter Author Name" name="author_name" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q1">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-1">S.No</th>
										<th class="col-md-2">Timestamp</th>
										<th class="col-md-2">Tweet ID</th>
										<th class="col-md-4">Tweet</th>
										<th class="col-md-1">Author ID</th>
										<th class="col-md-1">Location</th>
										<th class="col-md-1">Lang</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q1" && !empty($_POST["author_name"])) {
											echo "<h1>", $_POST["author_name"], "</h1>";
											$query = "SELECT * FROM twitter1 WHERE author=$$" . $_POST["author_name"] . "$$";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['datetime'], $row['tid'], $row['tweet_text'], $row['author'], $row['location'], $row['lang']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 2  -->

				<div id="q2" class="tab-pane fade">
					<h3>
						Given a keyword, retrieve the tweets containing the keyword and sort them by their popularity in decreasing order. Popularity of a tweet is based on it's like-count. Higher the like-count, more the popularity.
					</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q2';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="keyword">Keyword:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="keyword" placeholder="Enter keyword" name="keyword" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q2">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-5">Tweet</th>
										<th class="col-md-2">Likes</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q2" && !empty($_POST["keyword"])) {
											echo "<h1>", $_POST["keyword"], "</h1>";
											$query = "SELECT * FROM twitter2 WHERE keyword=$$" . $_POST["keyword"] . "$$";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['tid'], $row['tweet_text'], $row['like_count']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 3  -->

				<div id="q3" class="tab-pane fade">
					<h3>
						Given a hashtag, retrieve all tweets containing the hashtag and sort them in decreasing order of date and time.
					</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q3';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="hashtag">Hashtag:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="hashtag" placeholder="Enter #Hashtag" name="hashtag" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q3">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-2">Timestamp</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-5">Tweet</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q3" && !empty($_POST["hashtag"])) {
											echo "<h1>", $_POST["hashtag"], "</h1>";
											$query = "SELECT * FROM twitter3 WHERE hashtag=$$" . $_POST["hashtag"] . "$$";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['datetime'], $row['tid'], $row['tweet_text']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 4  -->

				<div id="q4" class="tab-pane fade">
					<h3>
						Given an author name, retrieve all tweets that mentions the author. Sort them in decreasing order of date and time.
					</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q4';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="mention">Mentions:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="mention" placeholder="Enter author name" name="mention" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q4">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-2">Timestamp</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-5">Tweet</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q4" && !empty($_POST["mention"])) {
											echo "<h1>", $_POST["mention"], "</h1>";
											$query = "SELECT * FROM twitter4 WHERE mention=$$" . $_POST["mention"] . "$$";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['datetime'], $row['tid'], $row['tweet_text']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 5  -->

				<div id="q5" class="tab-pane fade">
					<h3>
						Retrieve all tweets of a particular date sorted in decreasing order of their popularity where popularity is based on like count of the tweet.
					</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q5';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="date">Date:</label>
								<div class="col-sm-8">
									<input type="date" class="form-control" id="date" placeholder="Enter date" name="date" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q5">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-5">Tweet</th>
										<th class="col-md-2">Likes</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q5" && !empty($_POST["date"])) {
											echo "<h1>", $_POST["date"], "</h1>";
											$query = "SELECT * FROM twitter5 WHERE date=$$" . $_POST["date"] . "$$";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['tid'], $row['tweet_text'], $row['like_count']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 6  -->

				<div id="q6" class="tab-pane fade">
					<h3>Retrieve all tweets from a given location</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q6';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="location">Location:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="location" placeholder="Enter location" name="location" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q6">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-7">Tweet</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q6" && !empty($_POST["location"])) {
											echo "<h1>", $_POST["location"], "</h1>";
											$query = "SELECT * FROM twitter6 WHERE location=$$" . $_POST["location"] . "$$";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['tid'], $row['tweet_text']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 7  -->

				<div id="q7" class="tab-pane fade">
					<h3>Given a date, retrieve top 20 popular hashtags over the last 7 days. The popularity of a hashtag is determined by its frequency of occurrence over the said period.</h3>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q7';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="date">Date:</label>
								<div class="col-sm-8">
									<input type="date" class="form-control" id="date" placeholder="Enter date" name="date" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="q7">Submit</button>
								</div>
							</div>
						</form>
						<hr>
						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-3">S.No</th>
										<th class="col-md-6">Hashtag</th>
										<th class="col-md-3">Likes</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "q7" && !empty($_POST["date"])) {
											echo "<h1>", $_POST["date"], "</h1>";
											$query = "SELECT * FROM twitter7 WHERE date=$$" . $_POST["date"] . "$$ LIMIT 20;";
											$result = $session->execute(new Cassandra\SimpleStatement ($query));
											$count = 1;
											foreach ($result as $row) {
												printf("<tr>
												       <td>%d</td>
												       <td>%s</td>
												       <td>%s</td>
												       </tr>",
												       $count, $row['hashtag'], $row['like_count']);
												$count++;
											}
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- QUERY 8  -->

				<div id="q8" class="tab-pane fade">
					<h3>Given a date, delete all tweets posted on that day.</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#q8';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="date">Date:</label>
								<div class="col-sm-8">
									<input type="date" class="form-control" id="date" placeholder="Enter date" name="date" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-danger btn-lg" value="q8">Delete</button>
								</div>
							</div>
						</form>
						<hr>
						<?php
						if($_SERVER["REQUEST_METHOD"] == "POST") {
							if($_POST["submit"] == "q8" && !empty($_POST["date"])) {
								$query = "DELETE FROM twitter5 WHERE date=$$" . $_POST["date"] . "$$";
								$result = $session->execute(new Cassandra\SimpleStatement ($query));
								echo "<h1 class=\"bg-danger col-md-offset-1 col-md-9\"> DELETED ALL TWEETS POSTED ON ", $_POST["date"], ".</h1>";
							}
						}
						?>
					</p>
				</div>




				<!-- MAIN LAB TEST QUERIES -->

				<!-- TEST 1  -->

				<div id="t1" class="tab-pane fade">
					<h3>QUESTION 1 : ...</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#t1';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="input">Input:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="input" placeholder="Enter input" name="input" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="t1">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-7">Tweet</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "t1" && !empty($_POST["input"])) {
											echo "<h1>", $_POST["input"], "</h1>";
											// $query = "SELECT * FROM twitter6 WHERE input='" . $_POST["input"] . "'";
											// $result = $session->execute(new Cassandra\SimpleStatement ($query));
											// $count = 1;
											// foreach ($result as $row) {
											// 	printf("<tr>
											// 	       <td>%d</td>
											// 	       <td>%s</td>
											// 	       <td>%s</td>
											// 	       </tr>",
											// 	       $count, $row['tid'], $row['tweet_text']);
											// 	$count++;
											// }
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>


				<!-- TEST 2  -->

				<div id="t2" class="tab-pane fade">
					<h3>QUESTION 2 : ...</h3>
					<hr>
					<p>
						<form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); echo '#t2';?>" method="post">
							<div class="form-group">
								<label class="control-label col-sm-2" for="input">Input:</label>
								<div class="col-sm-8">
									<input type="text" class="form-control" id="input" placeholder="Enter input" name="input" required="">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" name="submit" class="btn btn-success btn-lg" value="t2">Submit</button>
								</div>
							</div>
						</form>

						<hr>

						<div class="table-responsive">
							<table class="table table-striped table-bordered table-hover table-condensed">
								<thead>
									<tr>
										<th class="col-md-2">S.No</th>
										<th class="col-md-3">Tweet ID</th>
										<th class="col-md-7">Tweet</th>
									</tr>
								</thead>
								<tbody>

									<?php
									if($_SERVER["REQUEST_METHOD"] == "POST") {
										if($_POST["submit"] == "t2" && !empty($_POST["input"])) {
											echo "<h1>", $_POST["input"], "</h1>";
											// $query = "SELECT * FROM twitter6 WHERE input='" . $_POST["input"] . "'";
											// $result = $session->execute(new Cassandra\SimpleStatement ($query));
											// $count = 1;
											// foreach ($result as $row) {
											// 	printf("<tr>
											// 	       <td>%d</td>
											// 	       <td>%s</td>
											// 	       <td>%s</td>
											// 	       </tr>",
											// 	       $count, $row['tid'], $row['tweet_text']);
											// 	$count++;
											// }
										}
									}
									?>
								</tbody>
							</table>
						</div>
					</p>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(function(){
			var hash = window.location.hash;
			hash && $('ul.nav a[href="' + hash + '"]').tab('show');

			$('.nav-tabs a').click(function (e) {
				$(this).tab('show');
				var scrollmem = $('body').scrollTop();
				window.location.hash = this.hash;
				$('html,body').scrollTop(scrollmem);
			});
		});
	</script>
</body>
</html>
