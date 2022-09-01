Features completed
•	User Register – Admin Register
•	User login – Admin Login
•	User request loan
•	User List his loans
•	User View specific loan
•	User can view specific repayment
•	Admins approve loan
•	Admin list all loans 
•	Admin view specific loan
•	Admin can view specific repayment

Validation added
•	Login returns a Token, which need to be added to all further requests to access APIs
•	User cannot see other user loans
•	User cannot approve his loans
•	Admin cannot update other information of loan except status
•	Admin cannot update repayment status
•	Loan amount and loan term cannot be zero


Request Type and Response Formats
Note :  
•	Used Passport package of Laravel for API development, it manages token for user specific sessions
o	Referred this link - https://blog.logrocket.com/laravel-passport-a-tutorial-and-example-build/
•	POSTMAN can be used to interact with API
o	Add header – “Accept – application/json” for all requests
o	Token – to be added at Authorization as a bearer token
o	All data should be sent to be added under Body as a x-www-form-urlencoded options (key value pairs)
o	HTTP methods can be switched from dropdown




Sl, No	Feature	HTTP Method	Request Body	Response Data
0	Dummy API Call 
http://127.0.0.1:8000/api/	GET	-	"message": 
"This is API response"

1a	Register (user)
http://127.0.0.1:8000/api/register	POST	name
email
password
password_confirmation
	JSON
Inserted user information
Along with token
1b	Register (admin)
http://127.0.0.1:8000/api/register	POST	name
email
password
password_confirmation
type : admin	JSON
Inserted user information
Along with token
2	Login (both user & admin)
http://127.0.0.1:8000/api/login	POST	email
password	JSON
Logged in user with token
(copy and paste token to authorization you got here)
3	Loan List (both user & admin)
http://127.0.0.1:8000/api/loan	GET	token (at authorization)	JSON
List of Loans 
(for user – his loans
for admin- all loans)
4	Request Loan (user)
http://127.0.0.1:8000/api/loan	POST	loan_amount
loan_term
token (at authorization)	JSON
Inserted loan information
5	Fetch Loan information (user & admin)
http://127.0.0.1:8000/api/loan/<loan_id>

eg: http://127.0.0.1:8000/api/loan/1	GET	token (at authorization)	JSON
Loan Information
6	Update loan (user & admin)
http://127.0.0.1:8000/api/loan/<loan_id>

eg: http://127.0.0.1:8000/api/loan/1	PATCH	(for user)
loan_amount
loan_term
token (at authorization)

(for admin)
Status – “APPROVED”
token (at authorization)	JSON
Update status
7	Fetch Repayment (user & admin)
http://127.0.0.1:8000/api/repayment/<repayment_id>

eg: http://127.0.0.1:8000/api/ repayment /1	GET	token (at authorization)	JSON
Repayment Information
8	Add Repayment (user)

http://127.0.0.1:8000/api/repayment/<repayment_id>

eg: http://127.0.0.1:8000/api/ repayment /1	PATCH	Status – “PAID”

token (at authorization)	JSON
Update status



Application working flow
1.	Admin registers
2.	User Registers
3.	User login
4.	User see list of his loan requests
5.	User requests loan
6.	Admin login
7.	Admin see list of requested loan
8.	Admin approves loan (automatically creates repayment record for that loan)
9.	Users see list of repayment for a particular loan
10.	User can see repayment
11.	User can update status of repayment


Files Created/Modified
1.	app/Console/Commands/dbcreate.php – to create db from command line
2.	app/Http/Controller/Auth/userAUthController.php – to handle user register and login
3.	app/Http/Controller/LoanController – to handle
a.	index – list loans
b.	show – view loan
c.	store – to save loan
d.	update – to update loan
e.	destroy – to delete loan
4.	app/Http/Controller/RepaymentController – to handle
a.	show – view repayment
b.	update – to update repayment
5.	app/Http/Resources/LoanResource – to generate systematic details of loan 
6.	app/Http/Resources/RepaymentResource – to generate systematic details of repayment 
7.	app/Models/Loan – to handle loan - table related operations (relations / fillables) 
8.	app/Models/Resource – to handle repayment - table related operations (relations / fillables) 
9.	Added Passport routes under app/Providers/AuthServiceProvider
10.	Added apiRource routes under app/routes/api.php
11.	Generated migration files under app/database/migrations


Run 
php artisan db: create loan_db
php artisan migrate