<?php
require_once('config/setenv.php');

$navItems = array("home" => "../part1/", "documentation" => "documentation", "about" => "about");
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$base = "wai/part1/";
if (strpos($path, $base)) {
	$path = substr($path, strlen($base));
}


$path = explode("/", $path);

if (isset($path[0])) {
	$options['action'] = $path[0];

	if (isset($path[1])) {
		$options['subject'] = $path[1];

		if (isset($path[2])) {
			$options['param1'] = $path[2];

			if (isset($path[3])) {
				$options['param2'] = $path[3];

				if (isset($path[4])) {
					$options['param3'] = $path[4];

					if (isset($path[5])) {
						$options['param4'] = $path[5];
					}
				}
			}
		}
	}
}


$email = '';
$password = '';

switch ($options['subject']) {
	case '':
		$page = new WebPageWithNav('', 'Welcome to the API', $navItems, 'footer');
		$page->addToBody("<h4>Welcome to the API, please continue to the documentation page for further details.</h4>");

		echo $page->getPage();
		break;
	case 'documentation':
		$page = new SectionedWebpage('documentation', 'Documentation Page', $navItems, 'footer');
		$page->addToBody("<br>This is the documentation page describing each endpoint");
		$page->addApi("http://localhost/wai/part1/api", "Main API entry point", "This give details regarding the api");
		$page->addApi("http://localhost/wai/part1/api/schedule", "Schedule information", "This gives the schedule information for the conference");
		$page->addApi("http://localhost/wai/part1/api/schedule/Monday", "Session day information", "This gives detailed information regarding the day");
		$page->addApi("http://localhost/wai/part1/api/schedule/Monday/paper", "Session day and paper information", "This gives detailed information regarding the session type");
		$page->addApi("http://localhost/wai/part1/api/slot", "Presentations for the conference", "This gives details regarding the listed presentations within the conference");
		$page->addApi("http://localhost/wai/part1/api/slot/12", "Search function", "This function returns the presentations that contain an id");
		$page->addApi("http://localhost/wai/part1/api/days", "Days search", "This will return the days.");
		$page->addApi("http://localhost/wai/part1/api/types", "Category search", "This will return the session types.");
		$page->addApi("http://localhost/wai/part1/api/login/Param1/Param2", "Login", "Login API 
		Where Param1 is username and param2 is password");
		$page->addApi("http://localhost/wai/part1/api/update/Param1/Param2", "Update", "Update API 
		Where Param1 is id and param2 is chair");
		echo $page->getPage();
		break;
	case 'about':
		$page = new WebPageWithNav('about', 'About Page', $navItems, 'footer');
		$page->addToBody("Lewis Jackson, W16018797");
		echo $page->getPage();
		break;
	case 'api';

		if ((($_SERVER['REQUEST_METHOD'] == 'POST') ||
				($_SERVER['REQUEST_METHOD'] == 'PUT') ||
				($_SERVER['REQUEST_METHOD'] == 'DELETE')) &&
			(strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)
		) {
		}

		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: GET, POST");

		$data = json_decode(file_get_contents("php://input"));

		switch ($options['param1']) {
			case 'schedule';

				$sql = "SELECT slots.day,
                     sessions.id, 
                     sessions.title, 
                     sessions.room, 
					 sessions.description,
                     slotsID, 
                     slots.time, 
                     sessions.type,
                     sessions.chair 
                     FROM sessions 
                     JOIN slots ON slotsID = slots.id 
					 ORDER BY slots.time";
				$response = new JSONRecordSet();
				$response = $response->getJSONRecordSet($sql);
		
				if (isset($options['param2'])) {
					$sql = "SELECT slots.day, 
                    sessions.id, 
                    sessions.title, -
                    sessions.room, 
					sessions.description,
					slotsID,
                    slots.time, 
                    sessions.type,
                    sessions.chair 
                    FROM sessions 
                    JOIN slots ON slotsID = slots.id
					WHERE slots.day = :day
					ORDER BY slotsID";
					$response = new JSONRecordSet();
					$response = $response->getJSONRecordSet($sql, array("day" => $options['param2']));
				}

				if (isset($options['param3'])) {
					$sql = "SELECT slots.day, 
						sessions.id, 
						sessions.title, 
						sessions.room, 
						sessions.description,
						slotsID,
						slots.time, 
						sessions.type,
						sessions.chair 
						FROM sessions 
						JOIN slots ON slotsID = slots.id
						WHERE sessions.type = :type 
						ORDER BY slotsID";
					$response = new JSONRecordSet();
					$response = $response->getJSONRecordSet($sql, array("type" => $options['param3']));
				}				 
				echo $response;
				break;

			case 'slot';
				$response = new JSONRecordSet();

				if (isset($options['param2'])) {
					$sql = "SELECT slots.day, 
					sessions.id, 
					sessions.title, 
					activities.sessionsID,
					activities.title,
					activities.abstract, 
					sessions.room, 
					slotsID, 
					slots.time, 
					sessions.chair,
					authors.*
					FROM sessions 
					JOIN slots ON slotsID = slots.id 
					JOIN activities ON sessions.id = activities.sessionsID 
					JOIN papers_authors ON activities.id = papers_authors.activitiesID
					JOIN authors ON papers_authors.authorID = authors.authorID
					WHERE sessions.id = :id";
					$response = new JSONRecordSet();
					$response = $response->getJSONRecordSet($sql, array("id" => $options['param2']));
				} else {

					$sql = "SELECT slots.day, 
					sessions.id, 
					sessions.title, 
					activities.sessionsID,
					activities.title,
					activities.abstract, 
					sessions.room, 
					slotsID, 
					slots.time, 
					sessions.chair,
					authors.*
					FROM sessions 
					JOIN slots ON slotsID = slots.id 
					JOIN activities ON sessions.id = activities.sessionsID 
					JOIN papers_authors ON activities.id = papers_authors.activitiesID
					JOIN authors ON papers_authors.authorID = authors.authorID
					LIMIT 100";
					$response = new JSONRecordSet();
					$response = $response->getJSONRecordSet($sql, "");
				}
				echo $response;

				break;

			case 'login';
				$email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;
				$password = isset($data->password) ? filter_var($data->password, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;

				if (!is_null($email)) {

					$sqlQuery = "SELECT email, password FROM users WHERE email LIKE :email";
					$params = array("email" => $email);
					$dbConn = pdoDB::getConnection();
					$queryResult = $dbConn->prepare($sqlQuery);
					$queryResult->execute($params);
					$rows = $queryResult->fetchAll(PDO::FETCH_ASSOC);
					$dbConn = null;

					if (count($rows) > 0) {
						if (password_verify($password, $rows[0]['password'])) {
							$token = array();
							$token['email'] = $email;
							$token['exp'] = "hello";
							$encodedToken = JWT::encode($token, 'secret_server_key');

							http_response_code(201);
							echo json_encode(array("message" => "User Logged in.", "token" => $encodedToken));
						} else {
							http_response_code(201);
							echo json_encode(array("message" => "Invalid password."));
						}
					} else {
						http_response_code(201);
						echo json_encode(array("message" => "Account not found."));
					}
				} else {
					http_response_code(400);
					echo json_encode(array("message" => "Error: Data is incomplete."));
				}
				break;

			case 'test':

				$name = isset($data->name) ? filter_var($data->name, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;


				if (!empty($name)) {
					http_response_code(200);;
					echo json_encode(array("message" => "hello $name"));
				} else {

					http_response_code(400);
					echo json_encode(array("message" => "Unable to say hello. Data is incomplete."));
				}

				break;

			case 'days':

				$response = new JSONRecordSet();

				$sql = "SELECT DISTINCT slots.day
                 FROM slots";

				$response = $response->getJSONRecordSet($sql);

				echo $response;
				break;

			case 'types':

				$response = new JSONRecordSet();

				$sql = "SELECT DISTINCT sessions.type
					 FROM sessions";

				$response = $response->getJSONRecordSet($sql);

				echo $response;
				break;

			case 'update':
				$email = isset($data->email) ? filter_var($data->email, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;
				$id = isset($data->id) ? filter_var($data->id, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;
				$description = isset($data->description) ? filter_var($data->description, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;
				$token = isset($data->token) ? filter_var($data->token, FILTER_SANITIZE_STRING, FILTER_NULL_ON_FAILURE) : null;


				if (!is_null($token) && !is_null($email) && !is_null($id)) {

					$tokenDecoded = JWT::decode($token, 'secret_server_key');

					if ($email == $tokenDecoded->email) {

						$sqlUpdate = "UPDATE sessions SET chair = :chair WHERE id = :id";

						$params = array("id" => $id, "chair" => $description);
						$dbConn = pdoDB::getConnection();
						$queryResult = $dbConn->prepare($sqlUpdate);

						// $success will just tell us if the query was executred
						$success = $queryResult->execute($params);

						// $wasupdated will tell us if anything was updated
						$wasupdated = ($queryResult->rowCount() > 0 ? true : false);


						$dbConn = null;

						http_response_code(201);
						echo json_encode(array("message" => "database updated", "success" => $success, "updated" => $wasupdated));
					} else {
						http_response_code(403);
						echo json_encode(array("message" => "authentiaction required", "success" => false));
					}
				} else {
					http_response_code(400);
					echo json_encode(array("message" => "invalid data", "success" => false));
				}
				break;
				
			default:
				echo json_encode([
					"message" => "Welcome to the api"
				]);
		}
		break;
	
	default:
		header("Content-type: applicaton/json", true, 404);
		break;
}
