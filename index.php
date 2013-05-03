<?php

	require_once('vendor/autoload.php');
	$app = new \Slim\Slim();

	$app->get('/:name', function ($name) use ($app) {
		require_once('github-api/github-api.php');
		$githubApi = new GithubApi\GithubApi();

		$safename = preg_replace('/[^A-Za-z0-9-]+/', '-', $name);

		$user = $githubApi->get('/users/' . $name);
		if (empty($user)) $app->redirect('/error/' . $safename);

		$events = $githubApi->get('/users/' . $name . '/events');
	    if (empty($events)) $app->redirect('/error/' . $safename);

		require_once('github-explorer/github-explorer.php');
	    $explorer = new GithubExplorer\GithubExplorer();
	    $explorer->initWithEvents($events);
	    $repos = $explorer->repos();

	    if (empty($repos) || count($repos)<3) $app->redirect('/error/' . $safename);
	    shuffle($repos);

	    $result = array();
	    foreach ($repos as $repo) {
			$repoInfo = $githubApi->get('/repos/' . $repo['name']);
			if (!empty($repoInfo)) {
				$result[] = array(
					'name' 			=> $repo['name'],
					'description'	=> $repoInfo['description']
				);
			}
			if (count($result) == 3) break;
		}

		$info = array(
			'name' 		=> $user['login'],
			'avatar'	=> $user['avatar_url'],
			'repos' 	=> $result,
		);
		$app->render('status.php', $info);
	});

	$app->run();