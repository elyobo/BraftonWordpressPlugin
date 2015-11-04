#REQUIRMENTS:

Wordpress : v3.5+

PHP: 
-v5.3+
-DOMDocument
-cURL
-fOpen (video) with allow\_url\_fopen enabled

#Installation:

Installation of the Wordpress Plugin is very easy.                                                                      
                                                                                                                          
 1.  In your Wordpress Dashboard select Plugins-&gt; Add New                                                              
                                                                                                                          
 2.  Select Upload Plugin in the upper left hand corner                                                                   
                                                                                                                          
 3.  Select Choose File and navigate to the place you stored the zip file. Once you select the file, click “Install Now”  
                                                                                                                          
 4.  Once your plugin finishes installing you can “Activate Now” or Return to Plugins Page.                               
                                                                                                                          
 5.  Activate your plugin and find it in the main menu to the left in your Admin Dashboard. {BRAND} Content Importer


##First Time Setup

**Under General Settings Tab.**

1. Turn the "Automatic Import" to on.

2. Select your "API Domain": this is the brand Brafton, ContentLEAD, or Castleford

3. Select an "Importer User". If on a multisite the “Network Admin” will be automatically set for you.

**Under Articles Tab.**

1. Turn the "Article Importer Status" to on.

2. Enter your "API Key" provided by your Account Manager

3. Select a "Default Author" if your Installation of Wordpress has more than one user.

If you are just receiving articles Click "Save Settings". If you are receiving videos please continue.

**Under Videos Tab.**

1. Turn the "Video Importer Status" to on.

2. Enter your "Public Key" provided by your Account Manager.

3. Enter your "Private Key" provided by your Account Manager.

4. Select if you would like your video to appear before or after the body content.

5. If you are using the Atlantis JS video player enter any CTA's you wish to use.

Click the "Save Settings" Button.

Your Content will now import within 2 minutes of turning the importer on. It will regularly trigger approximately every hour to check for fresh article content and twice daily for video content.

Dashboard Widget

> To aid in monitoring your content, there is now a Dashboard widget included with the importer. The “Recently Importer by {BRAND}” widget displays the latest content importer. This widget filter out any native content and will only display content importered.

#Options Explanation

##General:

-   Automatic Import: Must be turned on to enable the Importer to trigger with the Wordpress Sudo cron.

    -   Articles : Will trigger Hourly

    -   Videos: Will trigger 2x Daily

-   API Domain: Setting for registering the product brand your xml feed comes from

-   Importer User: Our content can sometimes contain html tags which Wordpress attempts to strip out when programmatically updating or inserting content. The importer user drop down allows you to select an administrator user to run the importer as during automation allowing you to receive all of your premium content.

    -   \*Note: Sometimes, although the user selected is an administrator, premium content can still be stripped out. If this is the case, simply select a different importer user, ideally the first administrator account that was set up with Wordpress.

    -   Note: If on a multisite the importer will automatically attempt to run the importer as a Network Administrator.

-   Import JQuery: Some of the features supported by the importer require a copy of jQuery to function. If a copy of jQuery is not loaded in the website turn this feature “on” to import a copy from Google’s Developer CDN (Content Delivery Network).

-   Add Premium Styles: Turning this feature on will enable the Styles tab allowing you to overwrite inline styles of some of our premium content including but not limited to pullquotes, videos, tweets etc.

    -   This feature does REQUIRE jQuery.

-   Default Post Status: You may import content in the Published, Drafts, or Private States. Drafts will require manual posting or scheduling to go live.

-   Categories: Import content and set the categories to match the categories set up by your XML Feed or to Import without categories at all.

-   Tag Options: Tags have since been depreciated in value by modern search engines but we still support them. Options to set your tags as the following nodes from the xml feed. Tags, Keywords, or Categories. Of course by selecting one of these options you must be receiving that option from your xml feed.

-   Custom Tags: Allows you to add your own tags to the imported content. Be sure to separate your tags by a ‘,’.

-   Publish Date: Your XML feed supplies 3 dates you can use as the date for your content, Published, Last Modified, and Created. We recommend setting this to “Published”, as it will typically be the most recent date.

-   Add OG Tags: OG Tags are what social media sites (particularly Facebook) use to identify pieces of your content for use in sharing your content. Many SEO plugins already provide support for these however if you are not using one or prefer to use ours (which also supports twittercards and google+ items) select the “Add Tags” Option

-   Update Existing Content: This option will allow you to override your article content within the last 30 days or all of your video content with updated copies from your xml feed. We recommend keeping this option off.

    -   NOTE: If this option is enabled it will take more system resources to run as well as download fresh copies of the article or video images during each run.

-   Remote Import: Some systems can have issues with the Wordpress Sudo Cron. If your importer does not trigger automatically you can enable this option which will send a request to our servers requesting a remote import.

    -   NOTE: This option will not work on all systems as it requires the use of XML-RPC which is can be blocked by some servers.

##Articles:

-   Article Importer Status: Enable the import of Article content from your XML feed.

-   API Key: This is your unique key provided by your Account Manager to access your XML Feed.

-   Dynamic Author: If you are receiving the ByLine option in your feed this option allows you to dynamically assign the author of your content based on the byline of the content.

-   Default Author: If your wordpress install has more than 1 user you can set the Author of your imported content. Note that this will be overridden if the Dynamic Author option is enabled for Editorial content, but it will still be used for Video content (assuming you receive Video content).

-   Custom Article Categories: Allows you to add your own categories to the imported article content. Be sure to separate your categories by a ‘,’.

-   {BRAND} Post Type: If you wish to separate your existing content from your imported content you can turn this feature on which will set up a new “blog\_content” post type. You will need to modify your site templates to display your content in this case. (v3.2.0)

    -   Note: You will need to enter a URL slug to prepend to all imported content.

-   Set as Pre-existing Custom Post Type: If you are already using a custom post type and would like to import your content into it you may select it from the drop down Menu. (v3.2.0)

    -   Choosing this option will require you to enter the category and/or tag taxonomy terms associated with your custom post type in order to add associate the XML feed categories with your content.

-   \# Articles to Import: Total Number of articles to import each time the importer runs. The lower the number the fewer system resources are used. (v3.1.8)

##Videos:

-   Video Importer Status: Enable the import of your Video content from your separate Video XML Feed.

-   Public Key & Private Key: These are your unique keys provided by your Account Manager to access your Video XML Feed.

-   Feed Number: this number is the array number for your feed. Most times this number is 0, unless there is more than one video feed for the same account.

-   Custom Video Categories: Allows you to add your own categories to the imported content. Be sure to separate your categories by a ‘,’.

-   Include Player on Pages : If using either the Atlantis or Video Js players you will need to enable one of these scripts to be added to the &lt;HEAD&gt; section of your site.

-   Video player: AtlantisJs is our default video player, which supports CTAs and Google Analytics. You can change the video player if you wish to VideoJs.

    -   The AtlantisJS video player requires JQuery

    -   VideoJS does not require JQuery however is an HTML5 video player which will not work with all Browsers.

-   Video Position : Display the video before or after the content of your video article.

    -   Note: The best option is to modify your templates to display the video instead of the featured image.

-   \# Videos to Import: Total Number of Videos to import each time the importer runs. The lower the number the fewer system resources are used. (v3.1.8)

-   AtlantisJs CTAs: Set the video’s Call To Actions. Available options are

    -   Pause Text: The tag line to appear at the top of the video when the pause button is pressed.

    -   Pause Link: The page you wish to send a user if they click the pause text

    -   Pause Asset Gateway ID: The form id associated with the Asset Gateway Account. (v3.2.0)

        -   Entering an Asset Gateway ID disabled the pause link.

    -   Ending Title: The Title Tag to display when the video has finished playing.

    -   Ending Subtitle: Smaller text appearing just below the title

    -   Ending CTA Button Image: You may upload an image to use in place of text for the End of Video Button. (v3.2.0)

        -   Button Position: When using the image button you need to specify where the button will appears .

    -   Ending Button Text: The Call to Action Text appearing in a button available for clicking (keep this short)

    -   Ending Button Link: The link to send a user if they click the Call to Action Button.

    -   Ending Asset Gateway ID: The form id associated with the Asset Gateway Account. (v3.2.0)

        -   Entering an Asset Gateway ID disabled the Ending Button link.

    -   Ending Background Image: You may upload a background image to use for the End of Video. This image will cover the video player completely and will crop automatically to fit. (v3.2.0)

-   Below The AtlantisJS CTA’s you will notice as you enter your Ending CTA Options you will see an approximation of what your layout will look like. (v3.2.0)

##ARCH:

This is for enabling our Arch Product. Setting this to “On” enables a Call To Action widget available in the Appearance-&gt;Widget menu, and requires the feed ID from the Arch account.

-   Arch Status: Turns the Arch product on or off.

-   Arch Id: Your unique feed id provided by your Account Manager

##Archives:

-   Archive Importer Status: Turn this “on” to enable the uploading of an XML file provided by your Account Manager from our Archives.

-   Upload an XML File: This must be an XML file only! The larger the file the longer it will take to upload to your system. Timeouts can occur with larger files. Simply upload the archive again to begin where you left off.

##Error Logs:

-   Debug Mode: enabling Debug Mode collects all errors reported during operation of the content importer plugin.

-   Clear Error Log: Clear the Error Log once errors have been investigated and dismissed or fixed.

-   Error Log: Display all the errors reported by the system during an importer run.

    -   \*NOTE: Not all errors reported are related to the importer. Some errors are completely harmless and others may be simply “caught” by the importer error system because they occurred while the importer was running.

> Note: Errors that result in failure to deliver content are reported to our system to expedite the troubleshooting process. If such an error does occur “Debug Mode” is turned on by default to assist in collecting data that will aid in those efforts.

-   Download Error Log: this will download a text file with any error data currently available. Please be sure to include this report with any emails requesting assistance. (v3.2.0)

##Manual Control:

This allows you to run the importer manually regardless of your status settings. Note that this will ignore the “Article Importer Status” and “Video Importer Status” options.

##Options:

1.  Run the Article Importer

2.  Run the Video Importer

3.  Import complete list of categories.

#Premium Styles:

This Option will only be available if “Add Premium Styles” option is turned on under the “General Settings” Tab.

##Article Styles: (v3.2.0)

-   Enable Pull Quote Styles: Turning this on will effect changes to Pull Quotes that are imported.

    -   Width of Pull Quotes: Often times the width set by our writers may not be ideal for your page layout. You may change the width to any value you like. This value is in percent based on the elements container.

-   Pull Quote Float: The default set by the writer on what side of the content a Pull Quote is displayed may be modified.

    -   Setting this to “none” will result in the Pull Quote extending the entire width of the content

-   Pull Quote margins: This will space the Pull Quote away from surrounding content by the pixel width you specify.

-   Enable Inline Image Styles: Turning this on will effect changes to Inline Images that are imported.

    -   Width of Inline Image: Often times the width set by our writers may not be ideal for your page layout. You may change the width to any value you like. This value is in percent based on the elements container.

-   Inline Image Float: The default set by the writer on what side of the content an Inline Image is displayed may be modified.

    -   Setting this to “none” will result in the Inline Image extending the entire width of the content

-   Inline Image margins: This will space the Inline Image away from surrounding content by the pixel width you specify.

> Note: Enabling the Inline Image Styles will apply these changes to any and all images that the writer has inserted into your imported content.

##Atlantis Video Style: (v3.2.0)

You now have the option to control some video player elements to help fit better with the look and feel of your site. If you are using this feature all fields will need to be filled out. (v3.2.0)

-   Enable Custom CSS Below:

    -   Custom CSS Sheet Below: Enables the ability to write your own CSS rules for the video player.

        -   Default css selector rules are automatically imported for you allowing you to modify the most commonly edited rules. You can add your own rules or delete existing ones.

    -   None : Disable the use of Custom Video Styles.

    -   Use Selection Below: Enables the ability to use a color picker and drop downs to modify video styles.

-   Pause Text Color: The color of your Pause Text.

-   Ending Background Color: If you are not using a background image you may specify your background color. You may enter “transparent” for no background color.

-   Ending Title Color: The color of the Ending title Text.

-   Ending title Background Color: You may specify a Background color of the Title Text to help set it apart. You may enter “transparent” for no background color.

-   Ending title Alignment: You may specify the text alignment of the title.

-   Ending SubTitle Background color: :You may specify a Background color of the Subtitle Text to help set it apart. You may enter “transparent” for no background color.

-   Ending Subtitle Alignment: You may specify the text alignment of the subtitle.

-   Ending Button Color: If you are not using an image as your button you may specify a color for your button.

-   Ending Text button color: If you are not using an image as your button you may specify the text color of your button.

-   Ending Button Color Hover: Change the color of your button when the mouse hovers over it.

-   Ending Text Button Color: Change the color of your button text when the mouse hovers over it.

-   Custom CSS rules: If you are so inclined to write your own css for the video player you may turn this option on instead of using the options above.

    -   You may use any valid CSS styles to target the appropriate elements.

#Updating:

It is important to keep all your plugins up to date to ensure that bugs are fixed, security holes are patched, and you have the most up to date features available.

Our Plugin can be upgraded by following a few simple steps.

1.  Navigate to your plugin page and find the “Content Importer”

2.  You may click the “Update Available” link to view only the plugins that have updates available, or you can find the plugin in the complete list and see the “update Now” link.

3.  You may also click the “View version {x.x} details” to see what changes have been made to the plugin as well as the requirements for compatibility.

4.  Once you make the decision to update your plugin you can click “Update Now” or ” Install Update Now”

> \*Note: Wordpress v4.2.2 has a bug that requires you to disable the plugin before you update or it may not successfully complete.
