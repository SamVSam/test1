## User Auth Token


#### Overview

The "User Auth Token" module provides tokenized authentication for users.

During module's install, auth_token_field is created and is filled with 32-byte random hash, unique per user.
New users will receive their unique tokens on user save.

Users can be automatically logged-in if they have "authtoken" parameter in the url.
This will work for any url, for example:
-  www.example.com?authtoken=TOKEN
-  www.example.com/content/123?authtoken=TOKEN

But will not have effect for already logged in users.


#### INSTALLATION

1) Copy all contents of this package to your modules directory preserving subdirectory structure.
2) Go to Administer -> Modules to install module, after module installation run update.php to generate tokens for already existent users,
     OR
   use "drush pm-enable user_auth_token -y && drush updb -y" command.

