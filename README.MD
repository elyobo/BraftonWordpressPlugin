<p>Installation:</p>
<p>Installation of the WordPress Plugin is very easy.</p>
<ol>
<li>In your WordPress Dashboard select Plugins-&gt; Add New</li>
<li>Select Upload Plugin in the upper left hand corner</li>
<li>Select Choose File and navigate to the place you stored the zip file. &nbsp;Once you select the file, click “Install Now”</li>
<li>Once your plugin finishes installing you can “Activate Now” or Return to Plugins Page.</li>
<li>Activate your plugin and find it in the main menu to the left in your Admin Dashboard.</li>
</ol>
<p>&nbsp;</p>
<p>Menu Options:</p>
<p>&nbsp;</p>
<p>General:</p>
<p>Master Importer Status: Must be turned on to enable the importer import features</p>
<p>API Domain: &nbsp;Setting for registering the product brand your xml feed comes from</p>
<p>Importer User: &nbsp;Our content can sometimes contain html tags which wordpress attempts to strip out when programmatically updating content. &nbsp;The importer user drop down allows you to select an administrator user to run the importer as during automation allowing you to receive all of your premium content.</p>
<p>*Note: Sometimes, although the user selected is an administrator, premium content can still be stripped out. &nbsp;If this is the case, simply select a different importer user, ideally the first administrator account that was set up with wordpress.</p>
<p>Import JQuery: Some of the features supported by the importer require a copy of jQuery to function. &nbsp;If a copy of jQuery is not loaded in the website turn this feature “on” to import a copy from Google’s Developer CDN (Content Delivery Network).</p>
<p>Add Premium Styles: Turning this feature on will enable the Styles tab allowing you to overwrite inline styles of some of our premium content including but not limited to (pullquotes, videos, tweets etc). &nbsp;This feature does REQUIRE jQuery.</p>
<p>*Note: This feature is not yet enabled</p>
<p>Default Post Status: You may import content as either already Published content or as Drafts that require manually posting them later.</p>
<p>Categories: Import content and set the categories to match the categories set up by your XML Feed or to Import without categories at all.</p>
<p>Custom Categories: Allows you to add your own categories to the imported content. &nbsp;Be sure to separate your categories by a ‘,’.</p>
<p>Tag Options: Tags have since been depreciated in value by modern search engines but we still support them. &nbsp;Options to set your tags as the following nodes from the xml feed. Tags, Keywords, or Categories. &nbsp;Of course by selecting one of these options you must be receiving that option from your xml feed.</p>
<p>Custom Tags: Allows you to add your own tags to the imported content. &nbsp;Be sure to separate your tags by a ‘,’.</p>
<p>Publish Date: &nbsp;Your XML feed supplies 3 dates you can use as the date for your content, Published, Last Modified, and Created. &nbsp;We recommend setting this to “Published”, as it will typically be the most recent date.</p>
<p>Add OG Tags: &nbsp;OG Tags are what social media sites (particularly Facebook) use to identify pieces of your content for use in sharing your content. &nbsp;Many SEO plugins already provide support for these however if you are not using one or prefer to use ours (which also supports twittercards and google+ items) select the “Add Tags” Option</p>
<p>Update Existing Content: This option will allow you to override your article content within the last 30 days or all of your video content with updated copies from your xml feed. &nbsp;We recommend keeping this option off.</p>
<p>&nbsp;</p>
<p>Articles:</p>
<p>Article Importer Status: Enable the import of Article content from your XML feed.</p>
<p>API Key: This is your unique key provided by your Account Manager to access your XML Feed.</p>
<p>Dynamic Author: &nbsp;If you are receiving the ByLine option in your feed this option allows you to dynamically assign the author of your content based on the byline of the content.</p>
<p>Default Author: If your wordpress install has more than 1 user you can set the Author of your imported content. &nbsp;Note that this will be overridden if the Dynamic Author option is enabled for Editorial content, but it will still be used for Video content (assuming you receive Video content).</p>
<p>Set Custom Post Type:<i> (not yet supported)</i></p>
<p>Videos:</p>
<p>Video Importer Status: Enable the import of your Video content from your separate Video XML Feed.</p>
<p>Public Key &amp; Private Key: These are your unique keys provided by your Account Manager to access your Video XML Feed.</p>
<p>Feed Number: this number is the array number for your feed. &nbsp;Most times this number is 0, unless there is more than one video feed for the same account.</p>
<p>Video Script: AtlantisJs is our default video player, which supports CTAs. &nbsp;You can change the video player if you wish to VideoJs or None (if building your own custom embed codes).</p>
<p>Video CSS Fix: Current solution if the video player does not appear properly on a site. &nbsp;Adding this fix can correct some style conflicts. &nbsp;Note that this option only applies to the AtlantisJs</p>
<p>AtlantisJs CTAs: Set the video’s Call To Actions. &nbsp;Available options are</p>
<ol>
<li>Pause Text: The tag line to appear at the top of the video when the pause button is pressed.</li>
<li>Pause Link: The page you wish to send a user if they click the pause text</li>
<li>Ending Title: The Title Tag to display when the video has finished playing.</li>
<li>Ending Subtitle: Smaller text appearing just below the title</li>
<li>Ending Button Text: The Call to Action Text appearing in a button available for clicking (keep this short)</li>
<li>Ending Button Link: The link to send a user if they click the Call to Action Button.</li>
</ol>
<p>&nbsp;</p>
<p>Pumpkin:</p>
<p>This is for enabling our CMAT or Marpro Product. &nbsp;Setting this to “On” enables a Call To Action widget available in the Appearance-&gt;Widget menu, and requires the feed ID from the Marpro account.</p>
<p>Pumpkin Status: Turns the Pumpkin product on or off</p>
<p>Pumpkin Id: Your unique feed id provided by your Account Manager</p>
<p>&nbsp;</p>
<p>Archives:</p>
<p>Archive Importer Status: Turn this “on” to enable the uploading of an XML file provided by your Account Manager from our Archives.</p>
<p>Upload an XML File: This must be an XML file only! &nbsp;The larger the file the longer it will take to upload to your system. &nbsp;Timeouts can occur with larger files. &nbsp;Simply upload the archive again to begin where you left off.</p>
<p>&nbsp;</p>
<p>Error Logs:</p>
<p>Debug Mode: enabling Debug Mode simply reports all errors during import, no matter why they occurred, to the error log.</p>
<p>Clear Error Log: Clear the Error Log once errors have been investigated and dismissed or fixed.</p>
<p>Error Log: Display all the errors reported by the system during an importer run.</p>
<p>*NOTE: Not all errors reported are related to the importer. &nbsp;Some errors are completely harmless and others may be simply “caught” by the importer error system because they occurred while the importer was running.</p>
<p>Manual Control:</p>
<p>This allows you to run the importer manually regardless of your status settings. &nbsp;Note that this will ignore the “Article Importer Status” and “Video Importer Status” options.</p>
<p>Options:</p>
<ol>
<li>Run the Article Importer</li>
<li>Run the Video Importer</li>
<li>Import complete list of categories.</li>
</ol>
<p>&nbsp;</p>
<p>Updating:</p>
<p>It is important to keep all your plugins up to date to ensure that bugs are fixed, security holes are patched, and you have the most up to date features available.</p>
<p>Our Plugin can be upgraded by following a few simple steps.</p>
<ol>
<li>Navigate to your plugin page and find the “Content Importer”</li>
</ol>
<p>&nbsp;</p>
<ol>
<li>You may click the “Update Available” link to view only the plugins that have updates available, or you can find the plugin in the complete list and see the “update Now” link.</li>
<li>You may also click the “View version {x.x} details” to see what changes have been made to the plugin as well as the requirements for compatibility.</li>
</ol>
<ol>
<li>Once you make the decision to update your plugin you can click “Update Now” or ” Install Update Now”</li>
</ol>
<p>*Note: WordPress v4.2.2 has a bug that requires you to disable the plugin before you update or it may not successfully complete.</p>
