<?php
	$parts = array();

	$data = $this->getData('repos');
	foreach ($data as $d) {
		// clean up description
		$description = strtolower(htmlentities($d['description']));

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

		$parts[] = $description;
	}

	$verbs = array('working on', 'forking', 'hacking', 'developing', 'bro-gramming', 'thinking about', 'dreamt up', 'confusing');
	shuffle($verbs); $key = array_rand($verbs); $verb = $verbs[$key];

?>


<div class="status">
	<div class="header">
		<a href="">
			<img src="<?php echo $this->getData('avatar'); ?>">
			<strong><?php echo $this->getData('name'); ?></strong>
			</a>

    	<small class="time">
			<span>12 may</span>
		</small>
  </div>

  <p>Just <?php echo $verb; ?> an improved <?php echo $parts[0]; ?> by combining a <?php echo $parts[1]; ?> and a <?php echo $parts[2]; ?></p>

</div>
