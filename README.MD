REQUIRMENTS:

Wordpress : v3.5+

PHP: v5.3+, cURL or fOpen with allow\_url\_fopen enabled

Installation of the Wordpress Plugin is very easy.                        
                                                                                                                          
 1.  In your Wordpress Dashboard select Plugins-&gt; Add New                                                              
                                                                                                                          
 2.  Select Upload Plugin in the upper left hand corner                                                                   
                                                                                                                          
 3.  Select Choose File and navigate to the place you stored the zip file. Once you select the file, click “Install Now”  
                                                                                                                          
 4.  Once your plugin finishes installing you can “Activate Now” or Return to Plugins Page.                               
                                                                                                                          
 5.  Activate your plugin and find it in the main menu to the left in your Admin Dashboard. {BRAND} Content Importer      

**Installation:**

**First Time Setup**

Under General Settings Tab.

1. Turn the "Automatic Import" to on.

2. Select your "API Domain": this is the brand Brafton, ContentLEAD, or Castleford

3. Select an "Importer User". If on a multisite the “Network Admin” will be automatically set for you.

4. Designate a default Author: If there is only one user for this installation the default author will automatically be set to that user.

Under Articles Tab.

1. Turn the "Article Importer Status" to on.

2. Enter your "API Key" provided by your Account Manager

If you are just receiving articles Click "Save Settings". If you are receiving videos please continue.

Under Videos Tab.

1. Turn the "Video Importer Status" to on.

2. Enter your "Public Key" provided by your Account Manager.

3. Enter your "Private Key" provided by your Account Manager.

4. Enter a feed Number (if provided to you by your Account Manager, else leave as 0(zero)).

5. Turn on “Include Player on Pages” if using our HTML5 video player. A browsers default video player (if available) will be used otherwise.

6. Select the type of player you would like to use. (AtlantisJS provides dynamic CTA’s)

7. Select where you would like to video to display (before or after the content of your article)

Click the "Save Settings" Button.

Your Content will now import within 2 minutes of turning the importer on. It will regularly trigger approximately every hour to check for fresh article content and twice daily for video content.

Dashboard Widget

To aid in monitoring your content, there is now a Dashboard widget included with the importer. The “Recently Importer by {BRAND}” widget displays the latest content importer. This widget filter out any native content and will only display content imported by the importer.

**Options Explanation**

Status:

-   Displays the Status of the Importer.

    -   GREEN: indicates everything is functioning properly

    -   RED: Indicates there may be an issue that should be addressed.

General:

-   Automatic Import: Must be turned on to enable the Importer to trigger with the Wordpress Sudo cron.

    -   Articles : Will trigger Hourly

    -   Videos: Will trigger 2x Daily

-   API Domain: Setting for registering the product brand your xml feed comes from

-   Importer User: Our content can sometimes contain html tags which Wordpress attempts to strip out when programmatically updating or inserting content. The importer user drop down allows you to select an administrator user to run the importer as during automation allowing you to receive all of your premium content.

    -   \*Note: Sometimes, although the user selected is an administrator, premium content can still be stripped out. If this is the case, simply select a different importer user, ideally the first administrator account that was set up with Wordpress.

    -   Note: If on a multisite the importer will automatically attempt to run the importer as a Network Administrator.

-   Default Author: If your wordpress install has more than 1 user you can set the Author of your imported content. Note that this will be overridden if the Dynamic Author option is enabled for Editorial content, but it will still be used for Video content (assuming you receive Video content).

-   Arch ID : If you are using our ARCH product you will need to enter your ARCH ID.

    -   Enables the inclusion of our “pumpkin” tracking script added to the sites footer.

    -   Enables a Custom CTA widget for use in any widgetized area on your site.

-   Import JQuery: Some of the features supported by the importer require a copy of jQuery to function. If a copy of jQuery is not loaded in the website turn this feature “on” to import a copy from Google’s Developer CDN (Content Delivery Network).

-   Default Post Status: You may import content in the Published, Drafts, or Private States. Drafts will require manual posting or scheduling to go live.

-   Categories: Import content and set the categories to match the categories set up by your XML Feed or to Import without categories at all.

-   Publish Date: Your XML feed supplies 3 dates you can use as the date for your content, Published, Last Modified, and Created. We recommend setting this to “Published”, as it will typically be the most recent date.

-   Remote Import: Some systems can have issues with the Wordpress Sudo Cron. If your importer does not trigger automatically you can enable this option which will send a request to our servers requesting a remote import. (v3.4)

    -   NOTE: This option will not work on all systems as it requires the use of XML-RPC which can be blocked by some servers.

Articles:

-   Article Importer Status: Enable the import of Article content from your XML feed.

-   API Key: This is your unique key provided by your Account Manager to access your XML Feed.

-   Dynamic Author: If you are receiving the ByLine option in your feed this option allows you to dynamically assign the author of your content based on the byline of the content.

    -   This option will search the “Users” for an appropriate match and assign that user as the author. If a user is not found the importer will add a new user to the installation.

-   Custom Article Categories: Allows you to add your own categories to the imported article content. Be sure to separate your categories by a ‘,’.

-   \# Articles to Import: Total Number of articles to import each time the importer runs. The lower the number the fewer system resources are used. (v3.1.8)

Videos:

-   Video Importer Status: Enable the import of your Video content from your separate Video XML Feed.

-   Public Key & Private Key: These are your unique keys provided by your Account Manager to access your Video XML Feed.

-   Feed Number: this number is the node idex for your feed. Most times this number is 0, unless there is more than one video feed for the same account. Your Account Manager will provide you with an alternate value if your feed requires it.

-   Custom Video Categories: Allows you to add your own categories to the imported content. Be sure to separate your categories by a ‘,’.

-   Include Player on Pages : If using either the Atlantis or Video Js players you will need to enable one of these scripts to be added to the &lt;HEAD&gt; section of your site.

-   Video player: AtlantisJs is our default video player, which supports CTAs and Google Analytics. You can change the video player if you wish to VideoJs.

    -   The AtlantisJS video player requires JQuery

    -   VideoJS does not require JQuery however is an HTML5 video player which will not work with all Browsers.

-   Insert Video: Display the video before or after the content of your video article.

    -   This option will replace the featured image in your articles, displaying the Video instead of the featured image.

-   \# Videos to Import: Total Number of Videos to import each time the importer runs. The lower the number the fewer system resources are used. (v3.1.8)

-   AtlantisJs CTAs: Set the video’s Call To Actions. Available options are

    -   Pause Text: The tag line to appear at the top of the video when the pause button is pressed.

    -   Pause Link: The page you wish to send a user if they click the pause text

    -   Pause Asset Gateway ID: The form id associated with the Asset Gateway Account. (v3.2.0)

        -   Entering an Asset Gateway ID disabled the pause link.

    -   Ending Title: The Title Tag to display when the video has finished playing.

    -   Ending Subtitle: Smaller text appearing just below the title

    -   Ending CTA Button Image: You may upload an image to use in place of text for the End of Video Button. (v3.2.0)

        -   Button Position: When using the image button you need to specify where the button will appears . (v3.4.1)

    -   Ending Button Text: The Call to Action Text appearing in a button available for clicking (keep this short)

    -   Ending Button Link: The link to send a user if they click the Call to Action Button.

    -   Ending Asset Gateway ID: The form id associated with the Asset Gateway Account. (v3.2.0)

        -   Entering an Asset Gateway ID disabled the Ending Button link.

    -   Ending Background Image: You may upload a background image to use for the End of Video. This image will cover the video player completely and will crop automatically to fit. (v3.2.0)

-   Below The AtlantisJS CTA’s you will notice as you enter your Ending CTA Options you will see an approximation of what your layout will look like. (v3.2.0)

Advanced:

-   Add Premium Styles: This option will enable 2 other menu items detailed below.

-   Add OG Tags: This option will add social site meta data to the &lt;HEAD&gt; of your site including:

    -   Facebook og:tags

    -   Twitter Card tags

    -   Google+ tags

    -   NOTE: Be sure there are no other plugins adding these tags as multiple tags can cause conflicts for some social sites

-   Update Existing Content: This option will override any content previously imported currently in your XML feed.

    -   This option, if enabled, will only apply to the next importer run. It is best used when running the importer manually or in conjunction with an archive file.

-   Tag Options: Tags are rarely used and hold no true SEO Value, however we provide you with options if you choose to use them. The Option you select must be included in your XML Feed.

-   Custom Tags: If you need to add custom tags to your imported content you can list them here separated by “,”.

-   Brafton Post Type: The Content Importer will import content into your “Posts”, however we have provided a custom post type option if you need it.

    -   Post Type Name: You must enter a Name for this custom post type.

        -   The Blogs url will be ([www.domain.com/{myName}/](http://www.domain.com/%7bmyName%7d/))

        -   The individual article url will be (www.domain.com/{myName}/{categoryName}/{postTitle})

        -   Categories will be accessesed by (www.domain.com/{myName}/{categoryName})

        -   Archives will be accessed by (www.domain.com/{myName}/archive/{year}/{month})

-   Set as Pre-existing Custom Post Type: You can import content into an already establish Custom Post type.

-   Choose Pre-existing Custom Category: You can utilize an already defined custom taxonomy instead of the default “Categories”

-   Choose Pre-existing Custom Tag: You can utilize an already defined custom taxonomy instead of the default “Tag”

-   Debug Mode: Debug mode can help trace the importers operation to better identify bugs if your content is not importing. If there is a vital error encountered during normal operation this option is automatically turned on to aid in identifying the issue.

Archives:

-   Archive Importer Status: Turn this “on” to enable the uploading of an XML file provided by your Account Manager from our Archives.

-   Upload an XML File: This must be an XML file only! The larger the file the longer it will take to upload to your system. Timeouts can occur with larger files. Simply upload the archive again to begin where you left off.

Error Logs:

-   Clear Error Log: Clear the Error Log once errors have been investigated and dismissed or fixed.

-   Brafton Log: Display all the errors reported by the system during an importer run.

    -   Clicking “(Show Log)” will display the log on the screen.

    -   \*NOTE: Not all errors reported are related to the importer. Some errors are completely harmless and others may be simply “caught” by the importer error system because they occurred while or after the importer has run.

Note: Errors that result in failure to deliver content are reported to our system to expedite the troubleshooting process. If such an error does occur “Debug Mode” is turned on by default to assist in collecting data that will aid in those efforts.

-   Download Error Log: this will download a text file with any error data currently available. Please be sure to include this report with any emails requesting assistance. (v3.2.0)

Manual Control:

This allows you to run the importer manually regardless of your status settings. Note that this will ignore the “Article Importer Status” and “Video Importer Status” options.

Options:

1.  Run the Article Importer

2.  Run the Video Importer

3.  Import complete list of categories.

Instructions: (v3.4.2)

-   This page displays a PDF of the importer instructions.

ShortCodes: (v3.4.2)

-   Signing up for ARCH provides you with some great form tracking functionality. To add these forms to your site you can use “shortcodes” in your content or any text fields throughout wordpress. You will need the ID of the form you would like to use which can be attained from your Account Manager.

    -   Native From: A native form will display on your page and be interactive in the page as part of the content.

    -   Iframe Form: This form will render in a separate “iframe” and although be interactive in the page will be separate content altogether. This form will not have any effect on your sites content nor will your site have any effects on the iframe form.

    -   PopUp form: This form will display as a popup overlaying your site when a link is clicked. You can utilize any type of content as the link to click including images.

Find Content: (v3.4.2)

-   This feature allows you to easily search through content only imported through the XML feed. You may also search for specific articles utilizing the unique brafton id provided through the XML feed. You can search for multiple articles by entering multiple id’s separated by a “,”.

Article Styles: (v3.2.0)

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

Note: Enabling the Inline Image Styles will apply these changes to any and all images that the writer has inserted into your imported content.

Video Style: (v3.2.0)

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

Updating:

It is important to keep all your plugins up to date to ensure that bugs are fixed, security holes are patched, and you have the most up to date features available.

Our Plugin can be upgraded by following a few simple steps.

1.  Navigate to your plugin page and find the “Content Importer”

2.  You may click the “Update Available” link to view only the plugins that have updates available, or you can find the plugin in the complete list and see the “update Now” link.


1.  You may also click the “View version {x.x} details” to see what changes have been made to the plugin as well as the requirements for compatibility.


1.  Once you make the decision to update your plugin you can click “Update Now” or ” Install Update Now”

> \*Note: Wordpress v4.2.2 has a bug that requires you to disable the plugin before you update or it may not successfully complete.
