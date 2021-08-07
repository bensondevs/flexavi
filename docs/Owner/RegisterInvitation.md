## Register Invitation

-------------------------------------------------------
### 1. Invite Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/invitations/invite_employee`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description
-------------|----------|------------|-------------
`invited_email` | Required | string, email | The email of the invited company
`expiry_time` | Optional | datetime | The expiry time of the invitation, if not filled, the invitation will be expired within 3 days
`title` | Required | string | The title of employee
`employee_type` | Required | integer | Type of employee, for detail see the meta documentation (section of Employee Types) at [Employee Meta](/docs/Meta/Employee.md)

**Request Body Example:**

```json
{
	"invited_email": "test@email.com",
	"expiry_time": "2021-06-11",
    "title": "Roof Cleaner Specialist",
    "employee_type": 2,
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`invitation` | Object | Invitation data of invited employee
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "invitation": {
        "invited_email": "test@email.com",
        "attachments": {
            "model": "App\\Models\\Employee",
            "model_id": "439f7bc0-c82d-11eb-bd70-e1fef1016ac9",
            "related_column": "user_id",
            "role": "employee",
            "registration_code": "1pVD43"
        },
        "registration_code": "1pVD43",
        "expiry_time": "2021-06-11T07:44:01.947642Z",
        "updated_at": "2021-06-08T07:44:01.000000Z",
        "created_at": "2021-06-08T07:44:01.000000Z"
    }
}
```

-------------------------------------------------------
### 2. Invite Owner
-------------------------------------------------------

**Endpoint:** `/api/dashboard/invitations/invite_owner`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description
-------------|----------|------------|-------------
`owner_id` | Required | string | The ID of the employee access that will be used by the invited user
`invited_email` | Required | string, email | The email of the invited company
`expiry_time` | Optional | datetime | The expiry time of the invitation, if not filled, the invitation will be expired within 3 days

**Request Body Example:**

```json
{
    "owner_id": "439f7bc0-c82d-11eb-bd70-e1fef1016ac9",
	"invited_email": "test@email.com",
	"expiry_time": "2021-06-11",
}
```

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-----------|---------------
`invitation` | Object | Invitation data of invited employee
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**

```json
{
    "invitation": {
        "invited_email": "test@email.com",
        "attachments": {
            "model": "App\\Models\\Owner",
            "model_id": "77e48ce0-c82e-11eb-ba37-c990286d2c1c",
            "related_column": "user_id",
            "role": "owner",
            "registration_code": "sED2Xq"
        },
        "registration_code": "sED2Xq",
        "expiry_time": "2021-06-11T07:53:39.451083Z",
        "updated_at": "2021-06-08T07:53:39.000000Z",
        "created_at": "2021-06-08T07:53:39.000000Z"
    }
}
```