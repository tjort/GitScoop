<?php

	require_once('vendor/autoload.php');
	$app = new \Slim\Slim();

	// ====================================
	// index
	// ====================================

	$app->get('/', function() use ($app) {
		$view = new \Slim\View(); $view->setTemplatesDirectory('templates/');
		$data = array(
			'content' => $view->render('search.php')
		);
		// render
		$app->render('base.php', $data);
	});

	// ====================================
	// report
	// ====================================

	$app->get('/report/:hash', function($hash) use ($app) {
		$safename = preg_replace('/[^A-Za-z0-9-]+/', '-', $hash);
		if (file_exists('reports/' . $safename . '.html')) {
			$view = new \Slim\View(); $view->setTemplatesDirectory('reports/');
			$data = array(
				'content' => $view->render($safename . '.html')
			);
			// render
			$app->render('base.php', $data);
		} else {
			$app->redirect('/');
		}
	});

	// ====================================
	// search
	// ====================================

	$app->map('/investigate/(:name)', function($name = '') use ($app) {
		if (isset($_POST['name'])) $name = (string)$_POST['name'];
		$safename = preg_replace('/[^A-Za-z0-9-]+/', '-', $name);

		if (strlen($name)<2) $app->redirect('/error/' . $safename);

		// get user details
		require_once('github-api/github-api.php');
		$githubApi = new GithubApi\GithubApi();
		$user = $githubApi->get('/users/' . $name);
		if (empty($user)) $app->redirect('/error/' . $safename);

		// get events
		$events = $githubApi->get('/users/' . $name . '/events');
	    if (empty($events)) $app->redirect('/error/' . $safename);

	    // from events, get repos
		require_once('github-explorer/github-explorer.php');
	    $explorer = new GithubExplorer\GithubExplorer();
	    $explorer->initWithEvents($events);
	    $repos = $explorer->repos();

	    if (empty($repos) || count($repos)<3) $app->redirect('/error/' . $safename);
	    shuffle($repos);

	    // pick three repos at random
	    $result = array();
	    foreach ($repos as $repo) {
			$repoInfo = $githubApi->get('/repos/' . $repo['name']);
			if (!empty($repoInfo) && (strlen($repoInfo['description']) >= 10)) {
				$result[] = array(
					'name' 			=> $repo['name'],
					'description'	=> $repoInfo['description'],
					'url' 			=> 'http://www.github.com/' . $repoInfo['full_name']
				);
			}
			if (count($result) == 3) break;
		}

		// write the info file
		$data = array(
			'name' 		=> $user['login'],
			'avatar'	=> $user['avatar_url'],
			'repos' 	=> $result,
		);

		$serial = serialize($data);
		$date = date('Ymd');
		$hash = $date . md5($serial);

		$data['hash'] = $hash;

		$view = new \Slim\View(); $view->setTemplatesDirectory('templates/');
		$view->setData($data);
		file_put_contents('reports/' . $hash . '.html', $view->render('report.php'));

		$data['message'] = $view->getData('message');
		$data['title'] = $view->getData('title');
		file_put_contents('reports/' . $hash . '.json', json_encode($data));

		$app->redirect('/report/' . $hash);

	})->via('GET', 'POST');

	// ====================================
	// error
	// ====================================

	$app->map('/error/(:name)', function($name = '') use ($app) {
		$view = new \Slim\View(); $view->setTemplatesDirectory('templates/');
		$data = array(
			'content' => $view->render('error.php')
		);
		// render
		$app->render('base.php', $data);
	})->via('GET', 'POST');

	// ====================================
	// run application
	// ====================================

	$app->run();