<?php

	require_once('vendor/autoload.php');
	$app = new \Slim\Slim();

	$app->get('/:name', function ($name) use ($app) {
		require_once('github-api/github-api.php');
		$githubApi = new GithubApi\GithubApi();

		$safename = preg_replace('/[^A-Za-z0-9-]+/', '-', $name);

		$events = $githubApi->get('/users/' . $name . '/events');
	    if (empty($events)) $app->redirect('/error/' . $safename);

		require_once('github-explorer/github-explorer.php');
	    $explorer = new GithubExplorer\GithubExplorer();
	    $explorer->initWithEvents($events);
	    $repos = $explorer->repos();

	    if (empty($repos) || count($repos)<3) $app->redirect('/error/' . $safename);
	    shuffle($repos);
	    $interesting = array_rand($repos, 3);

	    $result = array();
	    foreach ($interesting as $key) {
	    	$repo = $repos[$key];
			$repoInfo = $githubApi->get('/repos/' . $repo['name']);
			$result[] = array(
				'name' 			=> $repo['name'],
				'description'	=> $repoInfo['description']
			);
		}

		$app->render('status.php', array('information'=>$result));
	});

	$app->run();