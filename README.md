# PhpEasyToken! 😀

Hi! I'm Peppe 👽 and this is my first release 🔥 , it's meant to Create 🆕 , Upload 📤 , Send 📧 and Check ✅ Tokens for such as User Registration validation or whatever! Feel free to send suggestions or issues! This is Still W.I.P.


# Installation

***Using Composer*** 

    composer require peppestan/php-easy-token

***Using Release*** 
Download the .zip files and put into the website folder

# Requirements
 PHPMailer /PHPMailer



## Database

The necessary connections are inside the file: **database.php** , make sure to edit with your credentials
Also there is a ***.sql*** file into the folder ***/Database Files Example*** with the minimum required fields and tables to make the token system works

## Usage

 1. Make sure to Include **token.php** file into your php pages with:
 `include 'token.php';`
 
 2. **TokenValidate.php** page get's the token variable and email variable with a GET request and handle those 

## function  generate_token(tokenlenght)

 This function generates a token with the given lenght and returns the value
 ***Insert a minimum of 5 for lenght for safety reason***
## function  upload_token(token,email)
This function upload the token and email variables into the database 
## function  mail_token(token,email)
Sends an email to the user email with the generated token url - **maybe this still doesn't work properly**
## function  validate_token(token,email)
The most important function, checks if the token is expired (**time limit is 24hours**) and if it's not it validate the user and delete the users token.
