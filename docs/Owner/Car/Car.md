## Car

-------------------------------------------------------
### 0. About
-------------------------------------------------------

This section is going to describe you about the flow endpoints of the cars.

- Each `companies` possibly has many cars in their warehouse. This feature will allow the user (including but not limited to `Owner`) to manage their fleet.

- Car can have image and its uploadable

- This feature rely on the database table of `cars` which contains

Column Name | Data Type | Description
-------------------------------------
`id` | char(36) | Represents the ID of the car
`company_id` | char(36) | Represents the ID of company where car belong.
`car_image_path` | varchar(255) | The column to store path where uploaded image for car.
`brand` | varchar(255) | Represents the brand of the car.
`model` | varchar(255) | Represents the model of the car.
`year` | int | Represents the year when car is produced by the manufacturer.
`car_name` | varchar(255) | Represents the name given by the company for the car.
`car_license` | varchar(255) | represents the license number of the car.
`insured` | tinyint(1) | Represents the status of insurance of the car.
`status` | tinyint | represents the status of the car, to see the list of statuses available for the car, please see [Documentation](/docs/Meta/Car.md).
`created_at` | timestamp, nullable | The time when the record created.
`updated_at` | timestamp, nullable | The time when the record lastly edited.
`deleted_at` | timestamp, nullable | This column will be filled with the time when the row is soft-deleted.

- Car can be registered to certain time by the administrative. This will create new instance called `CarRegisterTime` with relationship with tables of `companies`, `cars` and `worklists` in certain condition

- This feature has ability to do soft delete and hard delete. If we do soft delete, the record will not be destroyed immediately, instead, deleted row's `deleted_at` will be set and eliminated from the normal query or search result. 

-------------------------------------------------------
### 1. Populate Company Cars
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of customer
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`cars` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "cars": {
        "current_page": 1,
        "data": [
            {
                "id": "c943dd00-c922-11eb-882b-0d7727f8a252",
                "brand": "Toyota",
                "model": "B",
                "year": 2010,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "317748",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c944b580-c922-11eb-ad4c-093488db0322",
                "brand": "Mercedes",
                "model": "D",
                "year": 2013,
                "car_name": "Company 1 Car Mercedes D",
                "car_license": "588024",
                "insured": 1,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c945a8e0-c922-11eb-a465-b114545c2dc4",
                "brand": "Mercedes",
                "model": "B",
                "year": 2009,
                "car_name": "Company 1 Car Mercedes B",
                "car_license": "912330",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c94694c0-c922-11eb-bc28-19dd040fc96d",
                "brand": "Toyota",
                "model": "B",
                "year": 2006,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "671708",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c9474100-c922-11eb-b314-43758310b806",
                "brand": "Mercedes",
                "model": "C",
                "year": 2010,
                "car_name": "Company 1 Car Mercedes C",
                "car_license": "904168",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 2. Populate Free Company Cars
-------------------------------------------------------


**Endpoint:** `/api/dashboard/companies/cars`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of customer
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`cars` | Object | The customer object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "cars": {
        "current_page": 1,
        "data": [
            {
                "id": "c943dd00-c922-11eb-882b-0d7727f8a252",
                "brand": "Toyota",
                "model": "B",
                "year": 2010,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "317748",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c944b580-c922-11eb-ad4c-093488db0322",
                "brand": "Mercedes",
                "model": "D",
                "year": 2013,
                "car_name": "Company 1 Car Mercedes D",
                "car_license": "588024",
                "insured": 1,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c945a8e0-c922-11eb-a465-b114545c2dc4",
                "brand": "Mercedes",
                "model": "B",
                "year": 2009,
                "car_name": "Company 1 Car Mercedes B",
                "car_license": "912330",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c94694c0-c922-11eb-bc28-19dd040fc96d",
                "brand": "Toyota",
                "model": "B",
                "year": 2006,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "671708",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c9474100-c922-11eb-b314-43758310b806",
                "brand": "Mercedes",
                "model": "C",
                "year": 2010,
                "car_name": "Company 1 Car Mercedes C",
                "car_license": "904168",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 3. Store Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|---9-----------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`brand` | Required | string | Brand of car
`model` | Required | string | Model of car
`year` | Required | integer | Year of car issued
`car_name` | Required | string, unique | Name of the car 
`car_license` | Required | string | Car license number, the legal identification of the car
`insured` | Required | boolean | Insurance status, if not insured return false

**Request Body Example:**

```json
{
	"brand" : "Toyota",
    "model" : "Carry",
    "year" : "1998",
    "car_name" : "Suzuki BX 2012 Bulldozer",
    "car_license" : "BM 14386",
    "insured" : true,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`car` | Object | Object of car data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "car": {
        "brand": "Suzuki",
        "model": "BX",
        "year": "2012",
        "car_name": "Suzuki BX 2012 Bulldozer",
        "car_license": "143862813",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "id": "a5d81460-c92a-11eb-b35f-2b0ee72781d0"
    },
    "status": "success",
    "message": "Successfully save car data."
}
```

-------------------------------------------------------
### 4. View Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/view`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|---9-----------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` or `car_id` | Required | string | ID of selected car
`with_company` | Optional | Boolean, string boolean | Set this to `true` will load the car relationship with company data, default value `false`
`with_worklists` | Optional | Boolean, string boolean | Set this to `true` will load the car relationship with worklists data, default value `false`
`with_registered_times` | Optional | boolean, boolean string | Set this to `true` to load car with it's registered times. The default value is `true`
`with_registered_employees` | Optional | boolean, boolean string | Set this to `true` to load car with it's registered employees. The default value is `true`

**Success Response Example:**

```json
{
    "car": {
        "id": "c87ae9c0-651f-11ec-9ab7-857fce5acd23",
        "brand": "Fleet Brand",
        "model": "Fleet Model",
        "year": 2019,
        "car_name": "Seeder Car Name",
        "car_license": "SEEDER_LICENSE_DATA",
        "insured": 0,
        "status": 1,
        "status_description": "Free",
        "car_image_url": "http://localhost:8000/storage/uploads/cars/9812378123.jpeg",
        "worklists": [],
        "registered_times": [
            {
                "id": "f048c390-6520-11ec-9791-557285e49af9",
                "worklist_id": null,
                "car_id": "c87ae9c0-651f-11ec-9ab7-857fce5acd23",
                "should_out_at": "2021-12-22 02:21:08",
                "should_return_at": "2021-12-28 02:21:08",
                "marked_out_at": "2021-12-23 02:21:08",
                "marked_return_at": null,
                "out_late": true,
                "late_out_difference_minute": 1440
            }
        ],
        "registered_employees": []
    }
}
```

-------------------------------------------------------
### 5. Set Car Image
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/set_image`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of target car
`car_image` | Required | file, Image (PNG, JPEG, JPG, SVG) | Image of car

**Request Body Example:**

```json
{
    "id": "ffa7c790-ab59-11eb-b742-c328c1e0f2ee",
    "brand" : "<FILE>"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`car` | Object | Object of car data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "car": {
        "id": "a5d81460-c92a-11eb-b35f-2b0ee72781d0",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "car_image_url": "http://localhost:8000/storage/uploads/cars/20210609161619pp.jpeg",
        "brand": "Suzuki",
        "model": "BX",
        "year": 2012,
        "car_name": "Suzuki BX 2012 Bulldozer",
        "car_license": "143862813",
        "insured": 0,
        "status": "free"
    },
    "status": "success",
    "message": "Successfully set car image."
}
```

-------------------------------------------------------
### 6. Update Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/store`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`brand` | Required | string | Brand of car
`model` | Required | string | Model of car
`year` | Required | integer | Year of car issued
`car_name` | Required | string, unique | Name of the car 
`car_license` | Required | string | Car license number, the legal identification of the car
`insured` | Required | boolean | Insurance status, if not insured return false

**Request Body Example:**

```json
{
	"id": "ffa7c790-ab59-11eb-b742-c328c1e0f2ee",
	"brand" : "Toyota",
    "model" : "Carry",
    "year" : "1998",
    "car_name" : "Suzuki BX 2012 Bulldozer",
    "car_license" : "BM 14386",
    "insured" : true,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`car` | Object | Object of car data
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "car": {
        "brand": "Suzuki",
        "model": "BX",
        "year": "2012",
        "car_name": "Suzuki BX 2012 Bulldozer",
        "car_license": "143862813",
        "company_id": "c8844320-c922-11eb-8153-c71677def74d",
        "id": "a5d81460-c92a-11eb-b35f-2b0ee72781d0"
    },
    "status": "success",
    "message": "Successfully save car data."
}
```

-------------------------------------------------------
### 7. Delete Car
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/update`

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
`id` | Required | string | ID of deleted car
`force` | Optional | boolean | Force status, set to `true` to delete permanently

**Request Body Example:**

```json
{
    "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successully delete car."
}
```

-------------------------------------------------------
### 8. Company Trashed Cars
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/trasheds`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`page` | Optional | number | Page of pagination
`search` | Optional | string | Searched keyword, will be matched through all attribute of customer
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`cars` | Object | The customer object, contains pagination information and array of `data`


**Success Response Example:**

```json
{
    "cars": {
        "current_page": 1,
        "data": [
            {
                "id": "c943dd00-c922-11eb-882b-0d7727f8a252",
                "brand": "Toyota",
                "model": "B",
                "year": 2010,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "317748",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c944b580-c922-11eb-ad4c-093488db0322",
                "brand": "Mercedes",
                "model": "D",
                "year": 2013,
                "car_name": "Company 1 Car Mercedes D",
                "car_license": "588024",
                "insured": 1,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c945a8e0-c922-11eb-a465-b114545c2dc4",
                "brand": "Mercedes",
                "model": "B",
                "year": 2009,
                "car_name": "Company 1 Car Mercedes B",
                "car_license": "912330",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c94694c0-c922-11eb-bc28-19dd040fc96d",
                "brand": "Toyota",
                "model": "B",
                "year": 2006,
                "car_name": "Company 1 Car Toyota B",
                "car_license": "671708",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
            },
            {
                "id": "c9474100-c922-11eb-b314-43758310b806",
                "brand": "Mercedes",
                "model": "C",
                "year": 2010,
                "car_name": "Company 1 Car Mercedes C",
                "car_license": "904168",
                "insured": 0,
                "status": 1,
                "status_description": "Free"
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
        "to": 5,
        "total": 5
    }
}
```

-------------------------------------------------------
### 9. Restore Cars
-------------------------------------------------------

**Endpoint:** `/api/dashboard/companies/cars/restore`

**Method:** `PATCH`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`
Authorization | `Bearer {token}`
Content-Type | `application/x-www-form-urlencoded`

**Parameters:**

Payload name | Required | Validation | Description    
-------------|----------|------------|-------------
`id` | Required | string | ID of deleted car

**Request Body Example:**

```json
{
    "id": "8c6f56a0-c82a-11eb-9b4d-6d85f55227a6"
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "status": "success",
    "message": "Successully restore car."
}
```