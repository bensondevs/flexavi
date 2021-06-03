## Authentication

-------------------------------------------------------
### 1. Login
-------------------------------------------------------

**Endpoint:** `/api/auth/login`

**Method:** `POST`

**Headers:**

Header Name | Value 
------------|--------------
Accept | application/json

**Parameters:**

 Payload name | Required | Validation | Description   
--------------|----------|------------|------------
`identity` | Required | string | Email or username of logging in user
`password` | Required | string | Password of logging in user

**Request Body Example:**
```json
{
    "email": "owner1@flexavi.com",
    "password": "owner1",
}
```

**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`data` | Object | Object of user data with `token` to authenticte request in authenticated API endpoint
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "data": {
        "id": "af044eb0-b609-11eb-9d63-3588cc84c452",
        "fullname": "Flexavi Owner 1",
        "salutation": "Mr.",
        "birth_date": "2001-05-16",
        "id_card_type": "id_card",
        "id_card_number": "892612998",
        "phone": "999999999999",
        "phone_verified_at": null,
        "address": "11, A Road Name",
        "profile_picture_url": "https://dummyimage.com/300/09f/fff.png",
        "email": "owner1@flexavi.nl",
        "email_verified_at": null,
        "created_at": "2021-05-16T05:43:47.000000Z",
        "updated_at": "2021-05-16T05:43:47.000000Z",
        "deleted_at": null,
        "token": "2|uQPVJqnVg6tlAfa0AF7ZoEbeM6f7m15jGT400DLp"
    },
    "status": "success",
    "message": "Successfully login"
}
```

-------------------------------------------------------
### 2. Register
-------------------------------------------------------

**Endpoint:** `/api/auth/register`

**Method:** `POST`

**Headers:**

 Header Name | Value
-------------|------
Accept | `application/json`

**Parameters:**

 Payload name | Required | Validation | Description    
--------------|----------|------------|-------------
`invitation_code` | optional | string | Invitation code sent by admin to register
`fullname` | Required | string | Full name of new user
`salutation` | Required | string | Salutation coule be `Mr`, `Mrs` or anything
`birth_date` | Required | date | Birth date of user, with format of `yyyy/mm/dd`
`id_card_type` | Required | string | ID card of the user, can be Personal ID Card from government or passport or driving license
`id_card_number` | Required | string | The value of the ID
`phone` | Required | string | Valid phone number with country code without `+`
`address` | Required | string | Valid address of the user
`profile_picture` | Required | file type: jpg, jpeg, svg, png | Profile picture of the user
`email` | Required | string | Unique email of user
`password` | Required | string, min: 8 characters, has uppercase, has lowercase, has numerical, has special characters | The password of the user, needs to be having minimal 8 characters, with uppercase, lowercase, numberical value and special characters
`confirm_password` | Required | string, strictly same as `password` | The password confirmation, needs to be EXACTLY the same as password value
`invitation_code` | Optional | string, exists in database | string | Invitation code for the user, this may contain certain data to be attached and claimed to newly registered user to start with given role by the owner of company.
`bank_name` | Required, if no `invitation_code` | string | Bank name of the user  
`bank_code` | Required, if no `invitation_code` | string | Code of the bank inserted by the user
`bank_account` | Required, if no `invitation_code` | string | Bank account number or ID
`bank_holder_name` | Required, if no `invitation_code` | string | The bank account holder name

**Request Body Example:**
- For Owner
```json
{
    "invitation_code": "register1",
    "fullname": "User Full Name",
    "salutation": "Mr",
    "birth_date": "1998-12-29",
    "id_card_type": "id",
    "id_card_number": "331511328312",
    "phone": "61238172212",
    "address": "Another Address Example 123",
    "profile_picture": "<FILE>",
    "email": "register1@flexavi.com",
    "password": "#Password123",
    "confirm_password": "#Password123",

    "bank_name": "Bank of Testing Purpose",
    "bic_code": "016",
    "bank_account": "087318231",
    "bank_holder_name": "John Michael Doe"
}
```

- For Invited User (Owner and Employee)
```json
{
    "invitation_code": "register1",
    "fullname": "User Full Name",
    "salutation": "Mr",
    "birth_date": "1998-12-29",
    "id_card_type": "id",
    "id_card_number": "331511328312",
    "phone": "61238172212",
    "address": "Another Address Example 123",
    "profile_picture": "<FILE>",
    "email": "register1@flexavi.com",
    "password": "#Password123",
    "confirm_password": "#Password123",
    
    "invitation_code": "c0d3EX4mpl3"
}
```

**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`data` | Object | Object of new registered user
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "data": {
        "fullname": "User Full Name",
        "salutation": "Mr",
        "birth_date": "1998-05-05",
        "id_card_type": "id",
        "id_card_number": "3315130505980002",
        "phone": "61238172212",
        "address": "Another Address Example 123",
        "email": "register1@flexavi.com",
        "id": "baea9e70-b93b-11eb-830d-37c36999c97a",
        "updated_at": "2021-05-20T07:19:35.000000Z",
        "created_at": "2021-05-20T07:19:35.000000Z"
    },
    "status": "success",
    "message": "Successfully register as user."
}
```

-------------------------------------------------------
### 3. Logout
-------------------------------------------------------

**Endpoint:** `/api/auth/logout`

**Method:** `POST`

**Headers:**

 Header Name | Value 
-------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:** N/A

**Response Attributes:**
Attribute Name  | Type  | Description   
----------------|-----------|---------------
`status` | String | Request Processing status
`message` | String | Message response for the user

**Success Response Example:**
```json
{
    "status": "success",
    "message": "Successfully logged out"
}
```