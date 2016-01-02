# TwoStepValidation
A two step validation demo using Google Authenticator algorithm. Built in php + mysql

# How to use
First setup a mysql database and complete the array in common.php
Then use the file db.sql to import the table.
The user flow is this:
* First the user creates an account
* Next, after the user logs in, he can setup the two steps validation. You can try this using Google Authenticator or similar apps like Duo Mobile.
* Now, when the user logs in, he will be prompted to enter the generated code from the app 

# Credits 
* DemoJS: Russ Sayers - http://blog.tinisles.com/2011/10/google-authenticator-one-time-password-algorithm-in-javascript/
* Bootstrap - http://getbootstrap.com/
* Medoo - http://medoo.in/
