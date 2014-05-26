APPLICATION INSTALLATION STEPS
===============================
Clone or download this repository.
In a terminal, run php composer.phar install in the talentica.dev dir in your vagrant virtual machine. You can ssh into the vagrant machine via vagrant ssh in the git root.
Run php artisan migrate in the terminal.
If this throws a PDO:Exception for sqlite, touch app/database/production.sqlite like so:
Run php artisan db:seed in the terminal.
Done
### Response Codes

* **200:** The request was successful.
* **201:** The resource was successfully created.
* **204:** The request was successful, but we did not send any content back.
* **400:** The request failed due to an application error, such as a validation error.
* **401:** An API key was either not sent or invalid.
* **403:** The resource does not belong to the authenticated user and is forbidden.
* **404:** The resource was not found.
* **500:** A server error occurred.

## API Endpoints

### GET /api/v1/lists

Retrieve an array of the authenticated user's tasklists.

### POST /api/v1/lists

Create a new tasklist. Returns status code **201** on success. Accepts the following parameters:

* **name** &ndash; The name of the tasklist.

### GET /api/v1/lists/{id}

Retrieve the tasklist with the given ID.

### PUT /api/v1/lists/{id}

Update the tasklist with the given ID. Accepts the same parameters as **POST /v1/lists**.

### DELETE /api/v1/lists/{id}

Delete the tasklist (and all associated tasks) with the given ID. Returns status code **204** on success.

### GET /api/v1/lists/{id}/tasks

Retrieve tasks for the tasklist with the given ID.

### POST /api/v1/lists/{id}/tasks

Create a new task for the tasklist with the given ID. Returns status code **201** on success. Accepts the following parameters:

* **description** &ndash; The description of the task.
* **completed** &ndash; Whether or not the task is completed. A value of **yes**, **y**, **1**, **true**, or **t** will set the task as completed. Anything else will set the task as not completed.

### GET /api/v1/lists/{id}/tasks/{taskid}

Retrieve the task with the given ID.

### PUT /api/v1/lists/{id}/tasks/{taskid}

Update the task with the given ID. Accepts the same parameters as **POST /v1/lists/{id}/tasks**.

### DELETE /api/v1/lists/{id}/tasks/{taskid}

Delete the task with the given ID. Returns status code **204** on success.
