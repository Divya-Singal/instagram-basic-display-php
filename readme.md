//GUIDE MEANT FOR DEVELOPERS 
//BY DIVYA SINGAL

# Step 1:
______________

Get your client id, client secret, redirect uri from facebook developer account.
Detailed instructions in :
https://developers.facebook.com/docs/instagram-basic-display-api/getting-started

Write these details in instadetail.json

Please Note: The Redirect Url should point to getcode.php
Also, the refreshcron.php is a cron job to be scheduled. Run it every 30 days. 

# Step 2:
_________________

Give RWX permissions to access_token.json , log.txt, instadetail.json
Example : *sudo chmod -R 777 access_token.json*

# Step 3:
_______________________

Replace your client id, redirect_uri and Open this url in browser:

https://api.instagram.com/oauth/authorize
  ?client_id=27
  &redirect_uri=https://path/to/file/getcode.php
  &scope=user_profile,user_media
  &response_type=code

Kindly, Authorize.

# Step 4:
_________________________
>> Now the access_token.json should have your access_token ready.
This token will live for 60 days from the date of creation.
Schedule cron job in the refreshcron.php such that it runs every 55 days so that your access_token is always valid. 


App Review References:
https://developers.facebook.com/docs/apps/review
https://developers.facebook.com/docs
https://developers.facebook.com/docs/apps/review/sample-submissions
https://developers.facebook.com/docs/apps/review/common-rejection-reasons