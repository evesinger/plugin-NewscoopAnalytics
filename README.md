Analytics plugin for Newscoop
=================================

**Purpose:** Generates and implements Piwik analytics code for Newscoop.

Features
-----------
- Lists all available Newscoop publications and prompts to select one from the list
- Presents a form to submit basic and advanced settings that are required to generate Piwik tracking code for each publication
- Based on those settings, tracking Code for Piwik Analytics is generated (code options: JavaScript and NoScript ImageTracker, JS only or ImageTracker only)
- Provides the option to visit selected publication and links to Piwik login page (if URL provided)

Installation
-------------
- Install plugin through our Newscoop Plugin System (via admin/plugins or $php application/console plugins:install)

Tracking Code implementation
-----------------------------
- Include smarty block {{piwik_block}}{{/piwik_block}} in the template files (Theme manager)

Requirements
-----------------
- This plugin requires a Piwik installation ("Piwik URL").
- Once Piwik is installed, the publication must be added as website in Piwik ("Site ID").