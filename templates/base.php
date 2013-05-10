<!DOCTYPE html>
<html>
<head>
	<title>GitHub leaks</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="stylesheet" type="text/css" href="/assets/kube/kube.min.css" />
	<link rel="stylesheet" type="text/css" href="/assets/app/style.css" />
	<link rel="stylesheet" type="text/css" href="/assets/source-sans/style.css" />
	<link rel="stylesheet" type="text/css" href="/assets/icomoon/style.css" />
</head>
<body>
	<div id="header">
		<div class="page">
			<div class="padded">
				<div class="row">
					<h1><a href="/"><i class="icon-eye"></i> GitScoop</a></h1>
				</div>
			</div>
		</div>
	</div>

	<?php echo $this->getData('content'); ?>

	<div id="colofon">
		<div class="page">
			<div class="row">
				<div class="third">
					<div class="padded">
						<h2>About</h2>
						<p>
							GitScoop uses the <a href="http://developer.github.com/v3/">GitHub API</a> to investigate the various projects a user follows or clones on GitHub. Then, amazingly, we deduce what they are using all that code for. Probably.
						</p>
						<br />
						<p>
							Check it out for yourself! Type someone's GitHub-username in the <a href="/">search box</a> and find out what they are plotting.
						</p>
					</div>
				</div>
				<div class="third">
					<div class="padded">
						<h2>Who made this?</h2>
						<p>
							Martijn (who works at <a href="">Stylishmedia</a>) created this app with help from the <a href="http://developer.github.com/v3/">Github API</a>, <a href="http://www.slimframework.com/">Slim PHP Framework</a>, <a href="http://guzzlephp.org/">Guzzle</a>, <a href="http://imperavi.com/kube/">Kube CSS</a> and various others.
						</p>
						<br />
						<p>
							You can reach me <a href="mailto:gus@stylishmedia.com">here</a>.
						</p>
						<br /><br />
						<p>
							<ul class="social">
							    <li><a href="http://www.github.com/mgussekloo"><em class="icon-github"></em> <span>Github</span></a></li>
							    <li><a href="http://www.twitter.com/mgussekloo"><em class="icon-twitter"></em> <span>Twitter</span></a></li>
							    <li><a href="http://www.linkedin.com/in/mgussekloo"><em class="icon-linkedin"></em> <span>LinkedIn</span></a></li>
							</ul>
						</p>
					</div>
				</div>
				<div class="third">
					<div class="padded">
						<h2>Recent reports</h2>
						<ul>
							<?php
								if (!file_exists('cache/recent.html') || (filemtime('cache/recent.html') < strtotime('-10 minutes'))) {
									// get the 3 most recent json files
									$html = ''; $dir = 'reports';

							    	if ($dh = opendir($dir)) {
										$files = array();
        								while ($file = readdir($dh)) {
            								if ($file != "." && $file != ".." && $file[0] != '.') {
            									if (is_file($dir . '/' . $file)) {
            										$filetime = filemtime($dir . '/' . $file);
            										if ($filetime < strtotime('-1 week')) {
            											unlink($dir . '/' . $file);
            										} else {
            											if (substr($file, -4) == 'json') {
            												while (isset($files[$filetime])) {
            													$filetime++;
            												}
            												$files[$filetime] = $file;
            											}
            										}
            									}
            								}
            							}
            							if (!empty($files)) {
	            							krsort($files);
	            							if (count($files) > 10) $files = array_splice($files, 0, 10);
	            							foreach ($files as $f) {
	            								$json = json_decode(file_get_contents($dir . '/' . $f), true);
	            								$html .= '<li><i class="icon-eye"></i> <a href="/report/' . $json['hash'] . '">' . $json['title'] . ' ' . $json['name'] . '</a></li>';
	            							}
	            							file_put_contents('cache/recent.html', $html);
	            						}
            						}
								}
								if (file_exists('cache/recent.html')) echo file_get_contents('cache/recent.html');
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="/assets/jquery/jquery.min.js"></script>
	<script src="/assets/app/init.js"></script>
</body>
</html>