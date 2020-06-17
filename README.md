# leaderboard_API_with_SlimFramework3
Leaderboard API with slim 3 framework

DOcumentation

Endpoints

1. Visiting the '/' endpoint returns the home page....yunno "Welcome to homepage" stuff
2. Visiting the '/{table}' endpoint returns all the data in the table
3. Visiting the '/{table}/{id}' endpoint returns all the data in the table of the set id
4. Visiting the '/submissions/track/{track}/{cohort}/{taskday}' endpoint returns submissions for a particular track for the current day
5. Visiting the '/submissions/old/track/{track}/{cohort}/{taskday}' endpoint returns submissions for a particular track for the previous days
6. Visiting the '/submissions/update/{id}' endpoint updates the submission for a specified {id}. This is the endpoint for marking submissions 
	To access this endpoint, a PUT request should be made with the following params.
	{
		points: the point scored,
		feedback: the feedback from mentors
	}
7. Visiting the '/task/{cohort}/{track}' endpoint returns tasks for a particular track in a specified cohort in decending order
8. Visiting the '/task/{cohort}/{track}/{level}' endpoint returns tasks for a particular track and level in a specified cohort in decending order
9. Visiting the '/task/upload' endpoint upload tasks. To access this, a POST request should be made with the following params
	{
		task_day: the task day,
		track: the prefferd track,
		task: the task,
		level: the level of the task e.g beginner, intermediate
		cohort: current cohort
	}
10. Visiting the '/task/edit/{id}' endpoint updates the task for a specified {id}. This is the endpoint for updating task
	To access this endpoint, a PUT request should be made with the following params.
		{
			task_day: the task day,
			track: the prefferd track,
			task: the task,
			level: the level of the task e.g beginner, intermediate
			cohort: current cohort
		}

11. Visiting the '/delete/{table}/{id}' endpoint deletes data of the specified id from the table.
12. Visiting the '/leaderboard' endpoint gets the leaderboad ranking (general ranking).
13. Visiting the '/leaderboard/{user}' endpoint gets the leaderboad ranking (general) of a particular user.
14. Visiting the '/user/update' endpoint updates the details of a user. To access the is endpoint, send a PUT request with the following params
	{
		first_name: the first name,
		last_name: the last name,
		email: email,
		nick: the nickname	
	}
15. Visiting the '/submit' endpoint submits tasks. To access this, a POST request should be made with the following params
	{
		user: the users's email,
		task_day: the task day,
		track: the prefferd track,
		url: the link to the task done,
		level: the level of the task e.g beginner, intermediate
		cohort: current cohort
		comment: the comments of the user
		sub_date: the submission date
	}
	
