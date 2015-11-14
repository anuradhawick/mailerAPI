# mailerAPI
To allow others to make a simple contact-us form by using your php_mail()

## How this works
* This should be initialized with the given relational schema
* Once created, the implementation allows two recipient email addresses per registered user
* The user once registered the user just have to send ger requests to the mailer.php
* It is advisable to use a server side get request technique since the loggin password for each user will have to be in cluded in the get request. Simply use http_get() or cURL :)
* The password is just to make sure nobody send mails from your domain, Incase, yet if anyonw wish they could.


## Format of the request
* mailer.php?ty=send&amp;un=&lt;USERNAME&gt;&amp;pw=&lt;PASSWORD&gt;&amp;msg=&lt;MESSAGE FROM THE CLIENT&gt;&amp;sub=&lt;SUBJECT&gt;&amp;name=&lt;CLIENT NAME&gt;&amp;cli=&lt;CLIENT EMAIL&gt;


## Response from the server, a JSON objects array of size <= 2
* [{"from":"admin@quarksis.com","to":"anuradhawick@gmail.com","subject":"My Subject","message":"Hi, This is a test","messenger":"tankgame_user@gmail.com"} ]
