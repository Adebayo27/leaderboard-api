<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \slim\App;

// $app->get('/', 'UserController:index');

$app->get('/', function (Request $request, Response $response, array $args) {

    $response->getBody()->write("Welcome to homepage");
    return $response;
});

//The version one of the API
$app->group('/api/v1', function(){
	//to get all the users
	$this->get('/users', function (Request $request, Response $response)
	{
		$sql = "SELECT * FROM user";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$users  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			echo json_encode($users);
			
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get a particular user
	$this->get('/users/{id}', function (Request $request, Response $response)
	{
		$id = $request->getAttribute('id');
		$sql = "SELECT * FROM user WHERE id = $id";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$users  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			echo json_encode($users);
			
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get all submissions
	$this->get('/submissions', function (Request $request, Response $response)
	{
		$sql = "SELECT * FROM submissions";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get a particular submission
	$this->get('/submissions/{id}', function (Request $request, Response $response)
	{
		$id = $request->getAttribute('id');
		$sql = "SELECT * FROM submissions WHERE id = $id";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get submissions for a particular track for the current day
	$this->get('/submissions/track/{track}/{cohort}/{taskday}', function (Request $request, Response $response)
	{
		$track = $request->getAttribute('track');
		$cohort = $request->getAttribute('cohort');
		$taskday = $request->getAttribute('taskday');
		$sql = "SELECT * FROM submissions WHERE track = '$track' AND `points` = 0 AND `cohort` = '$cohort' AND `task_day` = '$taskday'";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get submissions for a particular track for the previous days
	$this->get('/submissions/old/track/{track}/{cohort}/{taskday}', function (Request $request, Response $response)
	{
		$track = $request->getAttribute('track');
		$cohort = $request->getAttribute('cohort');
		$taskday = $request->getAttribute('taskday');
		$sql = "SELECT * FROM submissions WHERE track = '$track' AND `points` = 0 AND `cohort` = '$cohort' AND `task_day` < '$taskday'  ORDER BY `task_day` DESC";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});


	//to update score
	$this->put('/submissions/update/{id}', function (Request $request, Response $response, $args)
	{
		$id = $request->getAttribute('id');

		$point = $request->getParam('point');
		$feedback = $request->getParam('feedback');

		try {
			$db = new db();
			$pdo = $db->connect();
			$sql = "UPDATE submissions SET `points` =?, feedback =? WHERE id =?";
			$pdo->prepare($sql)->execute([$point, $feedback, $id]);
			$data = "{'notice': {'status': 'Successfully marked'}}";
			$pdo = null;
			return $data;
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//To get tasks for a particular track 
	$this->get('/task/{cohort}/{track}', function (Request $request, Response $response)
	{
		$cohort = $request->getAttribute('cohort');
		$track = $request->getAttribute('track');
		$sql = "SELECT * FROM task WHERE track = '$track' AND cohort = '$cohort'";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//To get tasks for a particular track and level
	$this->get('/task/{cohort}/{track}/{level}', function (Request $request, Response $response)
	{
		$cohort = $request->getAttribute('cohort');
		$track = $request->getAttribute('track');
		$level = $request->getAttribute('level');
		$sql = "SELECT * FROM task WHERE track = '$track' AND cohort = '$cohort' AND level = '$level'";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			echo '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to upload task
	$this->post('/task/upload', function (Request $request, Response $response, $args)
	{
		$day = $request->getParam('day');
		$track = $request->getParam('track');
		$task = $request->getParam('task');
		$level = $request->getParam('level');
		$cohort = $request->getParam('cohort');
				
		try {
			$db = new db();
			$pdo = $db->connect();

			$sql = "SELECT * FROM task WHERE task_day = '$day' AND track = '$track' AND level = '$level' AND cohort = '$cohort'";
			$stmt = $pdo->query($sql);
			$uploaded = $stmt->fetchAll(PDO::FETCH_OBJ);
			if(count($uploaded) < 1){
				$sql = "INSERT INTO task(task_day, track, task, level, cohort) VALUES(?,?,?,?,?)";
				$pdo->prepare($sql)->execute([$day, $track, $task, $level, $cohort]);
				$data = "{'notice': {'status': 'Task uploaded successfully'}}";
				$pdo = null;
				return $data;
			}else{
				$data = "{'notice': {'status': 'Task already uploaded, try editing it'}}";
				return $data;
			}
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});
	//to edit task
	$this->put('/task/edit/{id}', function (Request $request, Response $response, $args)
	{
		$id = $request->getAttribute('id');
		$day = $request->getParam('task_day');
		$track = $request->getParam('track');
		$task = $request->getParam('task');
		$level = $request->getParam('level');
				
		try {
			$db = new db();
			$pdo = $db->connect();

			$sql = "UPDATE task SET task_day =?, track =?, task =?, level =? WHERE id =?";
			$pdo->prepare($sql)->execute([$day, $track, $task, $level, $id]);
			$data = "{'notice': {'status': 'Task edited successfully'}}";
			$pdo = null;
			return $data;
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//the delete endpoint
	$this->delete('/delete/{table}/{id}', function (Request $request, Response $response, $args)
	{
		$table = $request->getAttribute('table');
		$id = $request->getAttribute('id');
				
		try {
			$db = new db();
			$pdo = $db->connect();

			$sql = "DELETE FROM `$table` WHERE id =?";
			$pdo->prepare($sql)->execute([$id]);
			$pdo = null;

			$data = '{"notice": {"status": "'. $table . ' with id of ' . $id . ' deleted successfully"}}';
			return $data;
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});




});



$app->run();
