## Register Invitation Meta

-------------------------------------------------------
### 1. All Register Invitation Status
-------------------------------------------------------

**Endpoint:** `/api/meta/register_invitation/all_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Active",
    "2": "Used",
    "3": "Expired"
}
```