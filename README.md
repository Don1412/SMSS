<html>
	<pre>
	INSTALL
		1. Open file config.ini and replace Database connection values;
		2. Import DB.sql in your database;
		3. Create cron task with 1 minute periodic for file route_send.php;
		4. Enjoy!
		
	PROJECT FILES
		add.php			- 	Page for create sending channels(from http://routemobile.com service)
		backup.php		- 	Create database backup
		config.ini		- 	Settings
		DBConnect.php		-	Connect to database(settings in config.ini)
		DB.sql 			- 	Database
		functions.php 		- 	Backend functions
		icloud_templates.php 	- 	Page for create message templates
		index.php 		- 	Page for create sms sending
		install.php 		- 	Installer(maybe not work)
		login.php 		- 	Login
		logout.php 		- 	Logout
		manager.php 		- 	Creates sms sending
		report.php 		- 	Gets dlr report from service and insert this in database
		rm.php 			- 	Destroy all project(protects my interests =) )
		route_send.php 		- 	Send sms messages to service
		SMSManager.php 		- 	Sending manager
		SMSReport.php 		- 	Report
		template.php 		- 	Operations to templates(back-end)
		templates.php 		- 	Operations to templates(front-end)
		time.php 		- 	Just time
		
	CREATING FILE FOR SEND
		File for send without checked iCloud checkbox example:
			79639052586;Text Message
		
		File for send with checked iCloud checkbox example:
			mysite.com/?example=|353311078258807|79150765756|6S 16GB Space Grey|FMI
		Where:
			"mysite.com/?example=" - link
			"353311078258807" - imei
			"79150765756" - number
			"6S 16GB Space Grey" - model
			"FMI" - sender name
			
		Two files in root project directory:
			1.txt - without checked iCloud checkbox
			2.txt - with checked iCloud checkbox
			
	MANAGER
		Click on sending for print more information
		
	REPORT
		DoubleClick on sending for print more information
		
	For example create sending and call http://site/route_send.php this script print info from service.
	</pre>
</html>