**Get Authentication Token**
----
Send a key given from the accounts backend. Will return the actual token created when the user logged in or signed up that can be used to make requests to the Files REST API.

* **URL**

  /token

* **Method:**

  `POST`

*  **URL Params**

   **Required:**

   `key=[integer]`

* **Success Response:**

     * **Code:** 200 OK <br />
       **Content:** `{ "token":"10Em39Vn58f7" }`

* **Error Response:**

   * **Code:** 503 SERVICE UNAVAILABLE <br />
       **Content:**<br/>
       `{ "status":"error", "message":"No key was received." }`<br/>
       `{ "status":"error", "message":"Invalid token key." }`

* **Sample Call:**

```javascript
       $.ajax({
         url: "https://account.scpl.dev/api/v1/token",
         dataType: "json",
         type: "POST",
         date: {
            key: "LOGIN_KEY"
         },
         success : function(r) {
           console.log(r);
         }
       });
```

* **Notes:**

     _No notes._
