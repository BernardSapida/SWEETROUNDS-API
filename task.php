/*
    * User model
        * Unique email address
        * Create new user (with their favorite, user information, cart) /user/create.php
        * Get list of all users /users/list.php
        * Get details of single user /users/read.php
        * Delete a single user account /users/delete.php
        * Update a single user account /users/delete.php
        * Update password of single user account /users/update_password.php
        * Update user status /users/update_status.php
        * Validate user email and password during signin /users/authenticate.php

    * Favorite model
        * Create cart for new user /favorite/create.php
        * Get favorites of current signed in user /favorite/list.php
        * Update favorite (Add/Remove) /favorite/update.php

    * Cart model
        * Get cart list of current signed in user "user_id" /cart/list.php
        * Update cart item list of current signed in user "user_id" (Add/Remove) /cart/update.php

    * User Informations model
        * Get user informations all users (LEFT JOIN users) /user_information/list.php
        * Get user information of signed in user "user_id" /user_information/read.php
        * Update user information of signed in user "user_id" user_information/update.php

    * Product model
        * Get all products /product/list.php
        * Create new product /product/create.php
        * Update a product /product/update.php
        * Delete a product /product/delete.php

    * Contact Messages model
        * Create new message /contact/create.php
        * Get list of messages /contact/list.php

    * Admin model
        * Unique email address
        * Get all accounts /admin/list.php
        * Create new account for admin /admin/create.php
        * Signin / Signin => Active / Inactive /admin/signout&authenticate.php
        * Update admin account (Important: Status -> Active/Inactive)
        * Validate admin email and password during signin (status) /admin/authenticate.php

    * Report model
        * Get all transaction of specific admin (cashier)
        * Get all transaction by day, week, month, and year
        * report for new customer within the month
        * report for top 10 selling donut and other donuts
        * report for low stock items quantity <= 20
        ? Website Setting -> tax, accepting_orders

    * Order model
        * Get all orders (inner join with order_details) /order/list.php
        * Get orders of signed in users with order details "LEFT JOIN order_details"
        * Create new orders
        * Update order information (order & payment status)

    * Transaction model
        * Get all transactions /transaction/list.php
        * Get specific transactions /transaction/read.php
        * Create new transaction /transaction/create.php
    
    * Populate database
        * Populate admins table
        * Populate cart_items table
        * Populate contact_messages table
        * Populate favorites table
        TODO: Populate orders table
        TODO: Populate order_details table
        * Populate products table
        TODO: Populate transactions table
        * Populate users table
        * Populate user_informations table

    * Important
    ! Error
    ? Questions
    TODO: To do
*/
