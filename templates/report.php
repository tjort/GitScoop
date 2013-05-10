<?php
	$parts = array();

	$data = $this->getData('repos');
	foreach ($data as $d) {
		// clean up description
		$description = strtolower(htmlentities($d['description']));

		$arr = explode(' ', $description);
		if (isset($arr[1]) && $arr[1] == 'is') {
			$arr = array_splice($arr, 2);
			$description = implode(' ', $arr);
		}

		$endSymbols = array('.',',','!','?');
		foreach ($endSymbols as $symbol) {
			while ($symbol == substr($description, (-1*strlen($symbol)))) {
				$description = trim(substr($description, 0, (-1*strlen($symbol))));
			}
		}
		$startSymbols = array('generate', 'build', 'create', 'the', 'a', 'an', 'this is the', 'this is a', 'this is an');
		foreach ($startSymbols as $symbol) {
			while ($symbol == substr($description, 0, strlen($symbol))) {
				$description = trim(substr($description, strlen($symbol)));
			}
		}

		$description = '<a href="' . $d['url'] . '">' . $description . '</a>';
		$parts[] = $description;
	}

	// verb
	$verbs = array('is working on', 'is forking', 'is hacking', 'has developed', 'is bro-gramming', 'thinks about', 'probably contemplates creating', 'is searching for', 'really wants', 'desires');
	shuffle($verbs); $key = array_rand($verbs); $verb = $verbs[$key];

	// subverb
	$subverbs = array('combining', 'mixing', 'intertwining', 'fusing together', 'staring at', 'taking ideas from', 'binding', 'cloning', 'shouting at people about', 'totally disregarding');
	shuffle($subverbs); $key = array_rand($subverbs); $subverb = $subverbs[$key];

	// title
	$titles = array('the secret life of', 'the evil plans of', 'scooping', 'the truth about', 'exposing the plans of', 'what we know about', 'reporting on', 'regarding', 'spying on', 'secrets of', 'a closer look at', 'the dirt on');
	shuffle($titles); $key = array_rand($titles); $title = $titles[$key];

	// intro
	$intros = array('According to our sources, ', 'Apparently, ', 'Our investigations indicate that ', 'We found out ', 'Our people learned that ', 'Obviously, ', 'Reportedly ', 'The latest gossip: ');
	shuffle($intros); $key = array_rand($intros); $intro = $intros[$key];

	$message = $intro . ' ' . $this->getData('name') . ' ' . $verb . ' a  ' . $parts[0] . ' by ' . $subverb . ' a ' . $parts[1] . ' and a ' . $parts[2] . '. Dangerous stuff.';
	$this->setData('message', $message);
	$this->setData('title', $title);
?>

<div id="banner">
	<div class="page">
		<div class="padded">
			<div class="row">
				<div id="report">
					<div id="report-shade">

						<div class="padded">
							<div class="head">
								<h2><?php echo $title; ?> <a href="http://www.github.com/<?php echo $this->getData('name'); ?>"><?php echo $this->getData('name'); ?></a></h2>

							</div>
							<div class="body twothird centered">
								<div class="row">
									<div class="half">

										<img class="avatar" src="<?php echo $this->getData('avatar'); ?>" />
										<ul class="details">
											<li><span>Name</span> <a href=""><?php echo $this->getData('name'); ?></a></li>
											<li><span>Github</span> <a href="http://www.github.com/<?php echo $this->getData('name'); ?>">Link</a></li>
											<li><a href="/">Investigate someone else</a></li>
										</ul>
										<br clear="both" />

									</div>
									<div class="half">
										<blockquote>
											<p>
												<?php echo $message; ?>
											</p>
											<small class="pull-right">GitScoop - we scoop what you fork</small>
										</blockquote>
									</div>
								</div>
							</div>
							<div class="footer">
								<a href="/report/<?php echo $this->getData('hash'); ?>">permalink</a>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>