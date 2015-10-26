Installation: for Version 3.1+
	Wordpress Plugin for importing Brafton/ContentLEAD/Castleford Content.  
	Latest Version Version 3.2.3
Requirements:
	PHP Version 5.3+ with the following Libraries:
•	DOMDocument
•	cURL (required for videos)
•	fopen
	Wordpress Version 3.5+
You can download our previous version if your wordpress is older than 3.5 however the PHP Requirements will not change for earlier versions. An old version is available here https://github.com/ContentLEAD/Marpro-WP-ImporterV1_0/archive/master.zip
Report any issues or Bugs to techsupport@brafton.com 
Installation of the Wordpress Plugin is very easy.
1.	In your wordpress Dashboard Select Plugins-> Add New
2.	Select Upload Plugin in the upper left hand corner
3.	Select Choose File and navigate to the place you stored the zip file.  Once you select the file click “Install Now”
4.	Once your Plugin finishes installing you can “Activate Now” or Return to Plugins Page.
5.	Activate your plugin and find it in the main menu to the left in your Admin Dashboard.


Updating:
It is important to keep all your plugins up to date to ensure that bugs are fixed, security holes are patched, and you have the most up to date features available.
Our Plugin can be upgraded by following a few simple steps.
1.	Navigate to your plugin page and find the “Content Importer”
2.	You may click the “Update Available” link to view only the plugins that have updates available or you can find the plugin in the complete list and see the “update Now” link. 
3.	You may also click the “View version {x.x} details” to see what changes have been made to the plugin as well as the requirements for compatibility.


4.	Once you make the decision to update your plugin you can click “update now” or ” Install Update Now”
*Note: Wordpress v4.2.2 has a bug that requires you to disable the plugin before you update or it may not successfully complete.

Menu Options:
General:
	Master Importer Status: Must be turned on to enable the importer import features
	API Domain:  Setting for registering the product brand your xml feed comes from
	Importer user:  Our content can sometimes contain html tags which wordpress attempts to strip out when programmatically updating content.  The importer user drop down allows you to select an administrator user to run the importer as during automation allowing you to receive all of your premium content.
	*Note: Sometimes although the user selected is an administrator premium content can still be stripped out.  If this is the case simply select a different importer user Ideally the first administrator account that was setup with wordpress.
	Import JQuery: Some of the features supported by the importer require a copy of jQuery to function.  If a copy of jQuery is not loaded in the website turn this feature “on” to import a copy from Googles developer CDN (Content Delivery network).
	Add Premium Styles: Turning this feature on will enable the Styles tab allowing you to overright inline styles of some of our premium content including but not limited to (pullquotes, videos, tweets ect).  This feature does REQUIRE jQuery.
	*Note: This feature is not yet enabled
	Deafult Post Status: You may import content as either already Published content or as Draft’s that require manually posting them later.
	Categories: Import content and set the categories to match the categories set up by your XML Feed or to Import without categories at all.
	Custom Categories: Allows you to add your own categories to the imported content.  Be sure to separate your categories by a ‘,’.
	Tag Options: Tags have since been depreciated in value by modern search engines but we still support them.  Options to set your tags as the following nodes from the xml feed. Tags, Keywords, or Categories.  Of course by selecting one of these options you must be receiving that option from your xml feed.
	Custom Tags: Allows you to add your own tags to the imported content.  Be sure to separate your tags by a ‘,’.
	Publish Date:  Your XML feed supplies 3 dates you can use as the date for your content, Published, Last Modified, and Created.  We recommend setting this to published as it will be the most recent date.
	Add OG Tags:  OG Tags are what social media site (particularly Facebook) use to identify pieces of your content for use in sharing your content.  Many SEO plugins already provide support for these however if you are not using one or prefer to use ours (which also supports twittercards and google+ items) Select the “Add Tags” Option
	Update Existing Content: This option will allow you to override your article content within the last 30 days or all of your video content with updated copies from your xml feed.  We recommend keeping this option off.  _NOTE: Will download a fresh copy of the image each time the importer is run_

Articles:
	Article Importer Status: Enable the import of Article content from your XML feed.
	API Key: This is your unique key provided by your Account Manager to access your XML Feed.
	Dynamic Author:  If you are receiving the ByLine option in your feed this option allows you to dynamically assign the author of your content based on the byline of the content.
	Default Author: If your wordpress install has more than 1 user you can set the Author of your imported Content.
	Set Custom Post Type: (not yet supported)
Videos:
	Video Importer Status: Enable the import of your Video Content from your Separate Video XML Feed.
	Public Key & Private Key: These are your unique keys provided by your Account Manager to access your Video XML Feed.
	Feed Number: this number Is the array number for your feed.  Most times this number is 0 unless there is more than one video feed for the same account.
	Video Script: AtlantisJs is our default video player supporting CTA’s.  You can change the video player if you wish to VideoJs or None (if building your own custom embed codes).
	Video CSS Fix: Current solution if the video player does not appear properly on a site.  Adding this fix can correct some style conflicts.
	AtlatisJs CTA’s: Set the video’s Call To Actions.  Available options are
1.	Pause Text: The tag line to appear at the top of the video when the pause button is pressed.
2.	Pause Link: The page you wish to send a user if they click the pause text
3.	Ending Title: The Title Tag to display when the video has finished playing.
4.	Ending Subtitle: Smaller text appearing just below the title
5.	Ending Button Text: The Call to Action Text appearing in a button available for clicking (keep this short)
6.	Ending Button Link: The link to send a user if they click the Call to Action Button.

Pumpkin:
	This is for enabling our CMAT or Marpro Product.  Turning enables a Call To Action widget available in the Appearance->Widget menu and requires the feed id from the marpro account.
	Pumpkin Status: Turns the Pumpkin product on or off
	Pumpkin Id: Your unique feed id provided by your Account Manager

Archives:
	Archive Importer Status: Turn this “on” to enable the uploading of an XML file provided by your Account Manager from our Archives.
	Upload an XML File: This must be an XML file only!  The larger the file the longer it will take to upload to your system.  Timeouts can occur with larger files.  Simply upload the archive again to begin where you left off.

Error Logs:
	Dubug Mode: enabling Debug Mode simply reports all errors during import no matter why they occurred to the error log.
	Clear Error Log: Clear the Error Log once errors have been investigated and dismissed or fixed.
	Error Log: Display all the errors reported by the system during an importer run.
		*NOTE: Not all error reported are related to the importer.  Some errors are completely harmless and others may be simply “caught” by the importer error system because they occurred while the importer was running.
Manual Control:
	This allows you to run the importer manually regardless of your status settings.
	Options:
1.	Run the Article Importer
2.	Run the Video Importer
3.	Import complete list of categories.



