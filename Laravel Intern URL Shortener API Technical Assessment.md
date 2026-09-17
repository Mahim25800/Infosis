## **TinyLink API**

### **Objective**

Build a simple URL Shortener REST API using Laravel.

The application will allow users to create short URLs from long URLs and track how many times each short URL has been visited.

---

## **1\. Authentication**

Implement user authentication using Laravel Sanctum.

### Required APIs

POST /api/register

Register a new user.

Required fields:

* name

* email

* password

* password\_confirmation

---

POST /api/login

Login an existing user and return an authentication token.

---

POST /api/logout

Logout the authenticated user.

---

GET /api/me

Return the currently authenticated user’s information.

---

# **2\. URL Management**

Authenticated users should be able to create and manage their own shortened URLs.

### Create Short URL

POST /api/urls

Request:

{  
&nbsp;&nbsp;&nbsp;&nbsp;"url": "https://example.com/this-is-a-very-long-url"  
}

The system should generate a unique short code automatically.

Example response:

{  
&nbsp;&nbsp;&nbsp;&nbsp;"success": **true**,  
&nbsp;&nbsp;&nbsp;&nbsp;"message": "URL shortened successfully",  
&nbsp;&nbsp;&nbsp;&nbsp;"data": {  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"id": 1,  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"original\_url": "https://example.com/this-is-a-very-long-url",  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"short\_code": "aB92x",  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"click\_count": 0  
&nbsp;&nbsp;&nbsp;&nbsp;}  
}

---

## **3\. URL List**

GET /api/urls

Return all URLs created by the authenticated user.

The API should support pagination.

Example:

GET /api/urls?page=1\&per\_page=10

---

## **4\. URL Details**

GET /api/urls/{id}

Return details of a specific URL.

The user should only be able to access URLs created by themselves.

---

## **5\. Delete URL**

DELETE /api/urls/{id}

Delete a shortened URL.

A user must not be able to delete another user’s URL.

---

# **6\. Short URL Redirect**

Create a public route:

GET /{short\_code}

When someone visits the short URL:

1. Find the corresponding URL.

2. Increase the click\_count by 1\.

3. Redirect the visitor to the original URL.

Example:

GET /aB92x

Should redirect to:

https://example.com/this-is-a-very-long-url

---

# **7\. Database**

Create a urls table.

Suggested fields:

* id

* user\_id

* original\_url

* short\_code

* click\_count

* created\_at

* updated\_at

The user\_id should be related to the users table.

### Expected Relationship

User:

hasMany(Url::class)

URL:

belongsTo(User::class)

---

# **8\. Validation**

Validate all incoming requests.

For example:

* URL is required.

* URL must be a valid URL.

* Short code must be unique.

* User must be authenticated for protected APIs.

Use Laravel’s validation features properly.

Using Form Request classes is a plus.

---

# **9\. API Response**

Try to maintain a consistent JSON response structure.

### Success

{  
&nbsp;&nbsp;&nbsp;&nbsp;"success": **true**,  
&nbsp;&nbsp;&nbsp;&nbsp;"message": "URL created successfully",  
&nbsp;&nbsp;&nbsp;&nbsp;"data": {}  
}

### Error

{  
&nbsp;&nbsp;&nbsp;&nbsp;"success": **false**,  
&nbsp;&nbsp;&nbsp;&nbsp;"message": "Something went wrong"  
}

Validation errors should return appropriate validation messages.

---

# **10\. Authorization**

A user should only be able to manage their own URLs.

For example:

User A must NOT be able to:

* View User B’s URL details

* Delete User B’s URL

* Modify User B’s URL

You may use Laravel Policy, Gate or another appropriate Laravel approach.

---

# **11\. Bonus Features**

These are optional.

### **Bonus 1 \- Custom Short Code**

Allow users to provide their own short code.

Example:

{  
&nbsp;&nbsp;&nbsp;&nbsp;"url": "https://example.com",  
&nbsp;&nbsp;&nbsp;&nbsp;"custom\_code": "my-link"  
}

Then:

/{my-link}

should redirect to the original URL.

---

### **Bonus 2 \- URL Statistics**

Create:

GET /api/urls/{id}/stats

Example response:

{  
&nbsp;&nbsp;&nbsp;&nbsp;"url": "https://example.com",  
&nbsp;&nbsp;&nbsp;&nbsp;"short\_code": "aB92x",  
&nbsp;&nbsp;&nbsp;&nbsp;"click\_count": 25  
}

---

# Technical Requirements

The project must use:

* Laravel

* PHP

* MySQL / PostgreSQL

* Laravel Sanctum

* REST API

* Eloquent ORM

You may use any additional Laravel feature/package if you can explain why you used it.

---

# **Submission Requirements**

Please submit:

1. GitHub repository link

2. Database migration

3. Seeder

4. API implementation

5. README.md

6. .env.example

7. Postman Collection or API documentation

### README should include:

* Project setup instructions

* Database setup

* Migration commands

* Authentication instructions

* API endpoint list

* Example requests/responses

* Any assumptions made

---

# Time

**Recommended time: 2-4 hours**

You do not need to implement every bonus feature.

The primary focus is:

* Laravel fundamentals

* REST API development

* Authentication

* Database relationships

* Validation

* Authorization

* Clean and understandable code

---

# **Evaluation Criteria**

| Category | Marks |
| :---- | ----: |
| Laravel fundamentals | 20 |
| REST API implementation | 15 |
| Authentication | 15 |
| Database & Relationships | 15 |
| Validation | 10 |
| Authorization | 10 |
| Code Quality | 10 |
| Documentation | 5 |
| **Total** | **100** |

Bonus features may be considered separately.

---

## **Important**

The candidate should be able to explain the implementation during the technical interview.

Using documentation, Google, or AI tools is allowed, but the candidate must understand and explain the submitted code.

The purpose of this assessment is not only to check whether the candidate can make the API work, but also to evaluate their Laravel fundamentals, problem-solving ability, and code quality.