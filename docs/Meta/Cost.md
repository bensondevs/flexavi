## Cost

-------------------------------------------------------
### 1. Costable Types
-------------------------------------------------------

**Endpoint:** `/meta/address/all_costable_types`

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
`search` | Optional | string | Searched keyword, will be matched through all attribute of owner
`per_page` | Optional | number | Amount of data per page, default amount is 10

**Response Attributes:**

Attribute Name  | Type  | Description   
----------------|-------|---------------
`address` | Object | The owner object, contains pagination information and array of `data`

**Success Response Example:**

```json
{
    "1": "Appointment",
    "2": "Worklist",
    "3": "Workday"
}
```