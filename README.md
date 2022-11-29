# Mona Books

Mona books is a full-stack website created using HTML, CSS, JS for frontend and PHP, mySQL for backend. It utilizes REST API architecture to separate backend and frontend.

## Documentation

### File hierarchy

```
root
|--- Services
| |--- u                             # Uploads
| | |--- user                        # User Images folder
| | |--- product                     # Product Images folder
| |
| |--- api
| | |---books
| | | |--- modify.php
| | | |--- create.php
| | | |--- handler.php
| | |---products
| | | |--- modify.php
| | | |--- create.php
| | | |--- handler.php
| | |---users
| | |--- modify.php                   # update / delete requests
| | |--- create.php                   # create requests
| | |--- handler.php                  # read requests
| |
| |--- config                         # contains sensitive data
| | |--- Database.php                 # contains db logon information
| | |--- (...)
| |
| |--- models                         # contains representation of chosen database tables
| |--- Books.php
| |--- Products.php
| |--- User.php
|                                     # everything pass this point is frontend
|
|--- Assets
| |--- media
| |--- fonts
|
|--- Scripts                          # Javascript files
| |--- shared.js                      # Shared frontend data (nav, header, etc)
| |--- fetch.js                       # HTTP Requests initiator
| |--- (...)                          # etc
|
|--- Styles                           # CSS styles
|--- Pages                            # Pages other than index.html
|--- index.html                       # landing page
```

### Assets and assets handling:

The assets will be divided into two parts: (1) Resources and (2) Server Uploads.

### Database

#### Access Levels:

1. Admin: 0
2. Employee: 1
3. User: 2

**Users can:**

1. Create accounts
2. Read products, books table
3. Update account information
4. Delete account
5. Upload image

**Employees can:**

1. Do all user work
2. CRUD products, product_attributes, products_categories, books table.
3. CRUD non-admin users

**Admins can:**

1. Do all employee work
2. CRUD Employees
