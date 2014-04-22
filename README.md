Analytics plugin for Newscoop
=================================

**Purpose:** Generates and implements Piwik and Google Analytics web analytics code for Newscoop.

Features
-----------
- Lists all available Newscoop publications and prompts to select one from the list
- Presents a form to submit basic and advanced settings that are required to generate Piwik and Google Analytics tracking code for each publication
- Based on those settings, tracking Code for Piwik and Google Analytics is generated (code options: Piwik JavaScript and NoScript ImageTracker, Piwik JS only, Piwik ImageTracker only, Google Analytics Universal tracking and Google Analytics classic tracking)
- Provides the option to visit selected publication and links to Piwik login page (if URL provided)
- Use parameter "tracker" to overwrite the tracking code type

Languages
----------
German, English

Installation
-------------
- from newscoop root directory: $php application/console plugins:install newscoop/newscoop-plugin-analytics

Tracking Code implementation
-----------------------------
- Include smarty block {{analytics_block}}{{/analytics_block}} in the template files (Theme manager)

Requirements
-----------------
- This plugin requires a Piwik installation ("Piwik URL") or a Google Analytics account.
- Once Piwik is installed or the Google Analytics account created, the publication must be added as website in Piwik or Google Analytics ("Site ID").