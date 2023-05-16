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
        * Get # of product sold by day, week, month, year.
        # Website Setting -> tax, accepting_orders

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
        * Populate orders table
        * Populate order_details table
        * Populate products table
        * Populate transactions table
        * Populate users table
        * Populate user_informations table

    * Sales Report
        * Online / Walk In

        * Total Revenue - Day
        walkin - /api/v1/report/walkins_revenue/day.php => date
        online - /api/v1/report/online/revenue/day.php  => date

        * Total Revenue - Week
        walkin - /api/v1/report/walkins_revenue/week.php => year, month, week
        online - /api/v1/report/online/revenue/week.php  => year, month, week

        * Total Revenue - Month
        walkin - /api/v1/report/walkins_revenue/week.php => year, month
        online - /api/v1/report/online/revenue/week.php  => year, month

        ! TODO: Total Revenue - Year

        ------------------------------------------------------------------------

        * Online / Walk In
        TODO: Number of Transaction - Day
        TODO: Number of Transaction - Week

        TODO: Number of Transaction - Month
        online - /api/v1/report/online/transaction/month.php  => year, month

        ! TODO: Number of Transaction - Year

        ------------------------------------------------------------------------

        TODO: Average Transaction - Day
        TODO: Average Transaction - Week
        TODO: Average Transaction - Month
        ! TODO: Average Transaction - Year

        TODO: New customers of the month

        ------------------------------------------------------------------------

        ? Inventory Information
        TODO: Get all donuts with less than 20 quantity (Low Quantity Donut)
        TODO: Top 10 Selling Donuts
        TODO: All donuts with total price sold

        TODO: Per Cashier Transaction
            TODO: Day
            TODO: Week
            TODO: Month

        * Sample:
        *       Cashier 1: 10 Transactions
        *       Cashier 2: 20 Transactions
        *       Cashier 3: 30 Transactions
        *       Cashier 4: 40 Transactions
        *       Total: 100 Transactions

    * Cashier WalkIn Transaction
        * Create a page with the following requirement
        * Columns -> Name, Flavor, Image, Quantity, Price.
        * Input -> All column fields, Note (Special Request)
        * Card -> Total, discount
        * Print Receipt -> Business Name, Order Number, Contact #, Shop Address, Date of Transaction, Items, Discount, Total

    * Important
    ! Error
    ? Questions
    TODO: To do
*/
