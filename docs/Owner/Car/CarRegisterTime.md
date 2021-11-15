## Car Register Time

-------------------------------------------------------
### 0. About
-------------------------------------------------------

This section is going to describe you about the flow endpoints of the car's registered times. This section is the child section of [Car Section](docs/Car/Car.md). To understand the flow of this section, you need to have understanding about the [Car Section](docs/Car/Car.md).

This section has purpose to serve the cases of car timing. Sometimes, company need to take care and control of their fleets, because the fleet is important infrastucture to help the employees to work through the schedule. Fleet can be under the control of the assigned employees. This condition needs the responsibility shifting from the company to the assigned employees. Those assigned employees will be responsible of the car as long as the registered time.

- To assign the car with certain time is to set the value of `should_out_at` as time when the car should be out from the warehouse or garage and `should_return_at` as time when the car should be returned to warehouse. By system, the defined time is not strict time to follow, instead, the administrative implementation of it may be so.

- This feature rely on the database table of `car_register_times` which contains

Column Name | Data Type | Description
-------------------------------------
`id` | char(36), primary | Represents the ID of Car Register Time.
`company_id` | char(36) | Represents the Company ID where this record is belong
`worklist_id` | char(36), nullable | Represents the Worklist ID which this record is attached.
`car_id` | char(36) | Represents the Car ID which this record is attached to.
`should_out_at` | timestamp | Represents the time when the car should be out from warehouse/garage.
`should_return_at` | timestamp | Represents the time when the car should returned to the warehouse/garage
`marked_out_at` | timestamp | Represents the time car is marked out from the warehouse/garage.
`marked_return_at` | timestamp | Represents the time car is marked returned to the warehouse/garage.
`created_at` | timestamp | Represents the time when the time is registered (created).
`updated_at` | timestamp | Represents the time when the last time time is updated.
`deleted_at` | timestamp | Represents the time when the time record is deleted. If not deleted, this column will be empty or null.

- User of the company can mark the car as out and the system will set the value for column of `marked_out_at`.

- As well as marking car out, the user can also mark the car as returned and the system will set the value for column of `marked_return_at`

- The value of `marked_out_at` can be different with `should_out_at`. It's impossible to be EXACTLY perfect at right time to set the car out from the warehouse. This system do understand the dynamics of the business. This is applied to `marked_return_at` with `should_return_at` as well.

- Car can be registered to worklist. This will set the value in `worklist_id` in the model.

- User (Administrative Employees and Owner) can add employees to this registered time. The relation of database for this ability is one `car_register_times` has many `car_register_time_employees`. To see detail about the car register time employee please see the [Documentation](./CarRegisterTimeEmployee.md)

-------------------------------------------------------
### 1. Populate Car Register Time
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/register_times`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`car_id` | Required | string, uuid string | The target car ID
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`car` | Object | The selected target car
`car_register_times` | Object | The car register times object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "car": {
        "id": "c6b49ed0-3fdc-11ec-b5c8-87249ddf9053",
        "company_id": "c54c2b70-3fdc-11ec-af00-69b3fcfd3622",
        "car_image_path": "uploads/cars/9812378123.jpeg",
        "brand": "Fleet Brand",
        "model": "Fleet Model",
        "year": 2017,
        "car_name": "Seeder Car Name",
        "car_license": "SEEDER_LICENSE_DATA",
        "insured": 1,
        "status": 1,
        "created_at": null,
        "updated_at": null,
        "deleted_at": null
    },
    "car_register_times": {
        "current_page": 1,
        "data": [
            {
                "id": "6b0bf620-3fdd-11ec-802c-4dfcc55caa0e",
                "worklist_id": null,
                "car_id": "c6b49ed0-3fdc-11ec-b5c8-87249ddf9053",
                "should_out_at": "2021-11-06 16:14:35",
                "should_return_at": "2021-11-10 16:14:35",
                "marked_out_at": "2021-11-05 16:14:35",
                "marked_return_at": null
            }
        ],
        "first_page_url": "/?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "/?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "/?page=1",
                "label": "1",
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "/",
        "per_page": 10,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```

-------------------------------------------------------
### 2. Register Car Time
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/register_times/register`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`car_id` | Required | string, uuid string, exists in `cars` | The target car ID.
`should_out_at` | Required | string, datetime string | The time when car should be out
`should_return_at` | Required | string, datetime string | The time when car should be returned.

**Request Body Example:**

```json
{
    "car_id": "c6b49ed0-3fdc-11ec-b5c8-87249ddf9053",
    "should_out_at": "2021-10-09 08:00:00",
    "should_return_at": "2021-10-09 16:00:00",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Registering status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully register time to a car."
}
```

-------------------------------------------------------
### 2. Register Car to Worklist Time
-------------------------------------------------------

**Description:**

This endpoint will allow the company owner to register time according to worklist time from start to the end of the worklist time.

Worklist has many appointments. The start time of this register car time will be started from the earliest appointment assigned in the worklist and the end time of this register time will be started until the latest appointment assigned in the worklist.

**Endpoint:** `/api/dashboard/companies/cars/register_times/register`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`car_id` | Required | string, uuid string, exists in `cars` | The target car ID.
`should_out_at` | Required | string, datetime string | The time when car should be out
`should_return_at` | Required | string, datetime string | The time when car should be returned.

**Request Body Example:**

```json
{
    "car_id": "c6b49ed0-3fdc-11ec-b5c8-87249ddf9053",
    "worklist_id": "c76f1a00-3fdc-11ec-b324-2ff3f4334b8f"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Registering status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully register car to a worklist."
}
```

-------------------------------------------------------
### 3. Mark Out Car Time
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/register_times/mark_out`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `car_id` | Required | string | ID of deleted carregistertime

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete carregistertime status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully mark car as out."
}
```

-------------------------------------------------------
### 4. Mark Return Car Time
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/register_times/mark_return`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `car_id` | Required | string | ID of deleted carregistertime

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete carregistertime status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully mark car as returned."
}
```

-------------------------------------------------------
### 6. Unregister Car Time
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/register_times/unregister`

**Method:** `DELETE`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `car_id` | Required | string | ID of deleted car register time

**Request Body Example:**

```json
{
    "id": "ecaf9900-c5d5-11eb-8078-b52a94774856"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Execution of delete car register time status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successfully unregister car register time."
}
```