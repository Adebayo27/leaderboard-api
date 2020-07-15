# leaderboard_API_with_SlimFramework3
Leaderboard API with slim 3 framework

DOcumentation

Endpoints

1. Visiting the '/' endpoint returns the home page....yunno "Welcome to homepage" stuff
2. Visiting the '/api/v1/table/{table}' endpoint returns all the data in the table
3. Visiting the '/api/v1/t/{table}/{id}' endpoint returns all the data in the table of the set id
	```Request https://30daysofcode.xyz/api/api/v1/t/table_name/id
	Response 
	 {
	    "response": {
	        "status": 1,
	        "message": [
	            {
	                "id": "100",
	                "user_id": "13cc65",
	                "first_name": "John",
	                "last_name": "Doe",
	                "nickname": "DevJohn",
	                "email": "johndoe@gmail.com",
	                "password": "thththt",
	                "phone": "+123456789",
	                "track": "python",
	                "score": "38273",
	                "isAdmin": "0",
	                "university": "unilag"
	            }
	        ]
	    }
	}
	```
4. Visiting the '/api/v1/submissions/track/{track}/{cohort}/{taskday}' endpoint returns submissions for a particular track for the current day
5. Visiting the '/api/v1/submissions/old/track/{track}/{cohort}/{taskday}' endpoint returns submissions for a particular track for the previous days
6. Visiting the '/api/v1/submissions/update/{id}' endpoint updates the submission for a specified {id}. This is the endpoint for marking submissions 
	To access this endpoint, a PUT request should be made with the following params.
	```{
		points: the point scored,
		feedback: the feedback from mentors
	}```
7. Visiting the '/api/v1/task/{cohort}/{track}' endpoint returns tasks for a particular track in a specified cohort in decending order
8. Visiting the '/api/v1/task/{cohort}/{track}/{level}' endpoint returns tasks for a particular track and level in a specified cohort in decending order
9. Visiting the '/api/v1/task/upload' endpoint upload tasks. To access this, a POST request should be made with the following params
	```{
		task_day: the task day,
		track: the prefferd track,
		task: the task,
		level: the level of the task e.g beginner, intermediate
		cohort: current cohort
	}```
10. Visiting the '/api/v1/task/edit/{id}' endpoint updates the task for a specified {id}. This is the endpoint for updating task
	To access this endpoint, a PUT request should be made with the following params.
		```{
			task_day: the task day,
			track: the prefferd track,
			task: the task,
			level: the level of the task e.g beginner, intermediate
			cohort: current cohort
		}```

11. Visiting the '/api/v1/delete/{table}/{id}' endpoint deletes data of the specified id from the table.


										//					//
										//		FOR USERS 	//
										//					//
12. Visiting the '/api/v1/leaderboard' endpoint gets the leaderboad ranking (general ranking including the avatars). A get request is made
	The response looks like this
	
	
	```{
	    "notice": {
	        "status": 1,
	        "data": [
	            {
	                "nickname": "Codon27",
	                "track": "mobile",
	                "level": "Beginner",
	                "score": "1000",
	                "email": "xxxxx@gmail.com"
	            },
	            {
	                "nickname": "Blaze",
	                "track": "backend",
	                "level": "beginner",
	                "score": "500",
	                "email": "xxxxx@gmail.com"
	            },
	            {
	                "nickname": "Codon27",
	                "track": "frontend",
	                "level": "beginner",
	                "score": "250",
	                "email": "xxxxxx@gmail.com"
	            },
	            {
	                "nickname": "Blaze",
	                "track": "backend",
	                "level": "intermediate",
	                "score": "100",
	                "email": "xxxxx@gmail.com"
	            }
	        ]
	    }
	}```
	
	

13. Visiting the '/api/v1/ranking/{user}' endpoint gets the leaderboad ranking (general) of a particular user.
	Request https://30daysofcode.xyz/api/api/v1/ranking/{user email without the '.com' e.g john@gmail}	
	This is the resulting response
	
	
	```{
	    "response": {
	        "status": "x",
	        "message": [
	            {
	                "track": "mobile",
	                "score": "1000",
	                "position": 1
	            },
	            {
	                "track": "frontend",
	                "score": "250",
	                "position": 3
	            }
	        ]
	    }
	}```			

14. Visiting the '/api/v1/user/update' endpoint updates the details of a user. To access the is endpoint, send a PUT request with the following params
	```{
		first_name: the first name,
		last_name: the last name,
		email: email,
		nickname: the nickname	
	}```
	The resulting response 
	```{
    		"response": {
        		"status": true, //false if not successful
        		"message": "Updated successfully" //error if not successful
    		}
	}```

15. Visiting the '/api/v1/submit' endpoint submits tasks. To access this, a POST request should be made with the following params
	```{
		user: the users's email,
		task_day: the task day,
		track: the prefferd track,
		url: the link to the task done,
		level: the level of the task e.g beginner, intermediate
		cohort: current cohort
		comment: the comments of the user
		sub_date: the submission date
	}

	The response 
	{
    		"response": {
        		"status": 1, //0 if not successful
        		"message": "Task submitted successfully" //error if not successful
    		}
	}```


16. Visiting the "api/v1/login" endpoint verifies users. A post request should be made to access this endpoint
	```{
		email: the email of the user,
		password: the user password
	}

	The resulting response looks like this
	{
	    "response": {
	        "status": 1,
	        "message": "User login"  //for user login
	    }
	}```

17. Visiting the '/api/v1/register' endpoint registers users. To access this, a POST request should be made with the following params
	```{
		first_name: the users's first name,
		last_name: the user's last name,
		nickname: the user's nickname,
		email: user's email,
		password: user's password
		phone: user's phone number
	}

	The resulting response looks like this
	{
	    "response": {
	        "status": 1, //if registation isn't successful, status will be 0
	        "message": "Registration complete"
	    }
	}```	

18. To get the submissions for a particular user,  fetch '/api/vi/user/submissions/{user email without the '.com'}'
	Request example http://xyz.com/api/v1/user/submissions/johndoe@gmail
	Response looks like this
	```{
	    "response": {
	        "status": 1, 
	        "message": [
	            {
	                "id": "226",
	                "user": "johndoe@gmail.com",
	                "track": "GIS",
	                "url": "https://task.com",
	                "task_day": "1",
	                "comments": "Testing GIS",
	                "points": "0",
	                "sub_date": "2020-06-08",
	                "feedback": "",
	                "cohort": "2",
	                "level": ""
	            },
	            {
	                "id": "225",
	                "user": "johndoe@gmail.com",
	                "track": "python",
	                "url": "http://localhost/black/index.php",
	                "task_day": "1",
	                "comments": "Sdf"
	                "points": "1",
	                "sub_date": "2020-05-31",
	                "feedback": "Marked by AutoGrader V2",
	                "cohort": "2",
	                "level": "Beginner"
	            },
	            {
	                "id": "224",
	                "user": "johndoe@gmail.com",
	                "track": "frontend",
	                "url": "http://localhost/black/blog.php",
	                "task_day": "9",
	                "comments": null,
	                "points": "13",
	                "sub_date": "2020-05-10",
	                "feedback": "Marked by AutoGrader V2 ",
	                "cohort": "2",
	                "level": "Intermediate"
	            }
	        ]
	    }
	}``` 
	PLEASE NOTE: YOU CAN'T EDIT A TASK THAT HAS BEEN MARKED

	19. To edit unmarked submissions,  fetch '/api/v1/users/submissions/update/{id of the submission}' with a PUT method with thw following params
		```{
			track: the track 
			level: the level
			url: the url of the submission
			comment: the comments from the user
		}
		The resulting response should be
		{
		    "response": {
		        "status": 1,
		        "message": "Update successfull"
		    }
		}```

	20. To get the task for the day, fetch the 'api/v1/task' by making a post request withe the following params
		```{
			cohort: the current cohort
			track: the preffered track
			level: the difficulty
			day: current day
		}
		The resulting response is 
		{
		    "response": {
		        "status": 1,
		        "message": [
		            {
		                "task": "This is the task for today....."
		            }
		        ]
		    }
		}```

	21. To view a particular submission,  fetch the '/api/v1/submissions/view/{the id of the submission}'
		Request example http://localhost:3000/api/v1/submissions/view/{id e.g 20}
		Response
		```{
		    "response": {
		        "status": 1,
		        "message": [
		            {
		                "id": "29",
		                "user": "johndoe@gmail.com",
		                "track": "backend",
		                "url": "local",
		                "task_day": "13",
		                "comments": "I worked",
		                "points": "100",
		                "sub_date": "2020-03-26",
		                "feedback": "You're doing well :)",
		                "cohort": 2,
		                "level": "intermediate"
		            }
		        ]
		    }
		}```
