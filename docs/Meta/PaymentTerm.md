## Payment Term Meta

-------------------------------------------------------
### 1. All Payment Term Statuses
-------------------------------------------------------

**Endpoint:** `/api/meta/payment_term/all_statuses`

**Method:** `GET`

**Headers:**

Header Name | Value 
------------|--------------
Accept | `application/json`

**Success Response Example:**

```json
{
    "1": "Unpaid",
    "2": "Paid",
    "3": "Forwarded to Debt Collector"
}
```