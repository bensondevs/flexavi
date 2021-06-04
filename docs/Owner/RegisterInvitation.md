## Register Invitation

-------------------------------------------------------
### 1. Invite Employee
-------------------------------------------------------

**Endpoint:** `/api/dashboard/invitations/invite_employee`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|-------
Accept | `application/json`
Authorization | `Bearer {token}`

**Parameters:**

Payload name | Required | Validation | Description
--------------|----------|------------|-------------
`employee_id` | Required | string, uuid | The ID of the employee access that will be used by the invited user
`invited_email` | Required | string, email | The email of the invited company
`expiry_time` | Optional | datetime | The expiry time of the invitation, if not filled, the invitation will be expired within 3 days

