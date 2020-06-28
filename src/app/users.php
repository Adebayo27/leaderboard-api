<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \slim\App;
class User
{
    public $nickname;
    public $track;
    public $level;
    public $score;

    function __construct($nickname,$track,$level,$score,$email){
	    $this->nickname = $nickname;
	    $this->track = $track;
	    $this->level = $level;
	    $this->score = $score;
	    $this->email = $email;
    }
}

// $app->get('/', 'UserController:index');

$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Welcome to homepage");
    return $response;
});

//The version one of the API
$app->group('/api/v1', function(){

	//register
	$this->post('/register', function (Request $request, Response $response, array $args)
	{
		$first = $request->getParam('first_name');
		$last = $request->getParam('last_name');
		$nickname = $request->getParam('nickname');
		$email = $request->getParam('email');
		$password = $request->getParam('password');
		$password =  password_hash($password, PASSWORD_DEFAULT);
		$phone = $request->getParam('phone');

		// var_dump($password);

		try {
			$db = new db();
			$pdo = $db->connect();
			$sql = "SELECT `email` FROM `user` WHERE `email` = '$email'";
			$stmt = $pdo->query($sql);
			$registered = $stmt->fetchAll(PDO::FETCH_OBJ);
			if(count($registered) < 1){
				$sql = "INSERT INTO user(first_name, last_name, nickname, email, password, phone) VALUES(?,?,?,?,?,?)";
				$pdo->prepare($sql)->execute([$first, $last, $nickname, $email, $password, $phone]);
				$data = "{'notice': {'status': 'Registration complete'}}";
				$pdo = null;
				return $data;
			}else{
				$data = "{'notice': {'status': 'User with this email already exist'}}";
				return $data;
			}

		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}


	});
	//login endpoint
	$this->post('/login', function(Request $request, Response $response, array $args){
		$email = $request->getParam('email');
		$password = $request->getParam('password');

		try {
			$db = new db();
			$pdo = $db->connect();

	        $sql = "SELECT * FROM user WHERE email = '$email'";
	        $stmt = $pdo->query($sql);
			$registered = $stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($registered as $key) {
				$hashed_pass = $key['password'];
				$isAdmin = $key['isAdmin'];
			}
			if(count($registered) == 1 && password_verify($password, $hashed_pass)){
				if ($isAdmin == 2) {
					$data = "{'notice': {'status': 'Super Admin login'}}";
				} elseif($isAdmin == 1) {
					$data = "{'notice': {'status': 'Admin login'}}";
				}else{
					$data = "{'notice': {'status': 'User login'}}";
				}
			}else{
				$data = "{'notice': {'status': 'Invalid email or password'}}";
			}
			
			return $data;

		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get all the data in a table
	$this->get('/table/{table}', function (Request $request, Response $response)
	{
		$table = $request->getAttribute('table');
		$sql = "SELECT * FROM $table";

		try {
			$db = new db();
			$pdo = $db->connect();

			$stmt = $pdo->query($sql);
			$data  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			return json_encode($data);
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to get a particular data from a table
	$this->get('/{table}/{id}', function (Request $request, Response $response)
	{
		$id = $request->getAttribute('id');
		$table = $request->getAttribute('table');
		$sql = "SELECT * FROM $table WHERE id = $id";

		try {
			$db = new db();
			$pdo = $db->connect();
			$stmt = $pdo->query($sql);
			$data  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			return json_encode($data);
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
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
			return '{message: {"resp": '.$e->getMessage().'}}';
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
			return '{message: {"resp": '.$e->getMessage().'}}';
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

	//To get tasks for a particular track in decending order
	$this->get('/task/{cohort}/{track}', function (Request $request, Response $response)
	{
		$cohort = $request->getAttribute('cohort');
		$track = $request->getAttribute('track');
		$sql = "SELECT * FROM task WHERE track = '$track' AND cohort = '$cohort' ORDER BY task_day DESC";

		try {
			$db = new db();
			$pdo = $db->connect();
			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//To get tasks for a particular track and level
	$this->get('/task/{cohort}/{track}/{level}', function (Request $request, Response $response)
	{
		$cohort = $request->getAttribute('cohort');
		$track = $request->getAttribute('track');
		$level = $request->getAttribute('level');
		$sql = "SELECT * FROM task WHERE track = '$track' AND cohort = '$cohort' AND level = '$level' ORDER BY task_day";

		try {
			$db = new db();
			$pdo = $db->connect();
			$stmt = $pdo->query($sql);
			$submissions  = $stmt->fetchAll(PDO::FETCH_OBJ);
			$pdo = null;
			$data = json_encode($submissions);
			return $data;
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to upload task
	$this->post('/task/upload', function (Request $request, Response $response, $args)
	{
		$day = $request->getParam('task_day');
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

	/** 
		uSERS ENDPOINTS
		
	**/
	//to get the leaderboard
	$this->get('/leaderboard', function(Request $request, Response $response, array $args){
		$usersRanking = [];

        try {
        	
			$db = new db();
			$pdo = $db->connect();
    		$sql = "SELECT * FROM leaderboard ORDER BY `score` DESC";
        	$stmt = $pdo->query($sql);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$rank = 0;
			foreach ($result as $key) {
				$nickname = $key['nickname'];
		        $track = $key['track'];
		        $nt = $nickname . $track;
		        $avatar = 'https://robohash.org/'.$nt;
		        $level = $key['level'];
		        $score = $key['score'];
		        $email = $key['email'];
				$user = new User($nickname,$track,$level,$score,$email);
        		array_push($usersRanking,$user);	
			}
			$data = json_encode(['notice' => ['status' => 1, 'data' => $usersRanking]], JSON_PRETTY_PRINT);

			return $data;

        } catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
        }

	});

	//to get the rank for a user for different tracks 
	$this->get('/leaderboard/{user}', function(Request $request, Response $response, array $args){

		$user = $request->getAttribute('user');
		$userr = $user . ".com";
		$usersRanking = [];
		$userRanking = [];
		$rank;

        try {
        	
			$db = new db();
			$pdo = $db->connect();
    		$sql = "SELECT * FROM leaderboard ORDER BY `score` DESC";
        	$stmt = $pdo->query($sql);
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$rank = 0;
			foreach ($result as $key) {
				$nickname = $key['nickname'];
		        $track = $key['track'];
		        $level = $key['level'];
		        $score = $key['score'];
		        $email = $key['email'];
				$user = new User($nickname,$track,$level,$score,$email);
        		array_push($usersRanking,$user);	
			}
			foreach ($usersRanking as $key) {
				$rank++;
				if ($key->email == $userr) {
					array_push($userRanking, ['track' => $key->track, 'score' =>$key->score, 'position' =>$rank]);
				}
			}
			return $userRanking;

        } catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
        }
	});

	$this->put('/user/update', function (Request $request, Response $response, $args)
	{
		$first = $request->getParam('first_name');
		$last = $request->getParam('last_name');
		$email = $request->getParam('email');
		$nick = $request->getParam('nick');
				
		try {
			$db = new db();
			$pdo = $db->connect();

			$sql = "UPDATE user SET first_name = '$first', last_name = '$last', nickname = '$nick' WHERE email = '$email' ";
			$pdo->prepare($sql)->execute();
			$data = "{'notice': {'status': 'User with'. '$email' . 'Successfully marked'}}";
			$pdo = null;
			return json_encode($data);
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});

	//to submit task
	$this->post('/submit', function (Request $request, Response $response, $args)
	{
		$user = $request->getParam('user');
		$day = $request->getParam('task_day');
		$track = $request->getParam('track');
		$url = $request->getParam('url');
		$level = $request->getParam('level');
		$cohort = $request->getParam('cohort');
		$comment = $request->getParam('comment');
		$sub_date = $request->getParam('sub_date');
				
		try {
			$db = new db();
			$pdo = $db->connect();

			$sql = "SELECT * FROM submissions WHERE user = '$user' AND task_day = '$day' AND track = '$track' AND level = '$level' AND cohort = '$cohort'";
			$stmt = $pdo->query($sql);
			$submitted = $stmt->fetchAll(PDO::FETCH_OBJ);
			if(count($submitted) < 1){
				$sql = "INSERT INTO submissions(user, track, url, task_day, comments, sub_date, cohort, level) VALUES(?,?,?,?,?,?,?,?)";
				$pdo->prepare($sql)->execute([$user, $track, $url, $day, $comment, $sub_date, $cohort, $level]);
				$data = "{'notice': {'status': 'Task submitted successfully'}}";
				$pdo = null;
				echo $data;
			}else{
				$data = "{'notice': {'status': 'Task already submitted for today, wait for next task'}}";
				echo $data;
			}
			
		} catch (PDOException $e) {
			return '{message: {"resp": '.$e->getMessage().'}}';
		}
	});


});

$app->run();
