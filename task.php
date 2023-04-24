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

    * Order History model
        TODO: 
        ? Should we remove

    * Order model
        * Get all orders (inner join with order_details) /order/list.php
        * Get orders of signed in users with order details "LEFT JOIN order_details"
        * Create new orders
        * Update order information (order & payment status)

    * Transaction model
        TODO: Get all transactions
        TODO: Get specific transactions
        TODO: Create new transaction

    TODO: Create methods for computing sales report (database query, Like: Joins)
    TODO: Insert User Data for each table
    TODO: Do testing for each endpoints

    * Important
    ! Error
    ? Questions
    TODO: To do
*/
