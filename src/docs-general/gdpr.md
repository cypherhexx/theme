# GDPR Alert

> Alert which warns users of GDPR acceptance

## Overview
By default the alert is set up to be full screen as this is proven to improve conversion rates, if the design suggest the GDPR message should be reloacted then it's advisable to remind the project team that the conversion rate will drop for all tracking. 

Either way the css is easy enough to change if required

### What's happening here...
On page load we check for 2 cookies, if these are undfinded then the alert is shown. If the terms have been previously accepted or declined the cookies are updated to expire in another 30 days.

These cookies work with our set up at [Google Tag Manager](./tracking/analytics-google-tag-manager.md). 

If the user accepts or has previously acceptted the GDPR terms then GDPR Compliant Tracking is loaded via GTM. If the user has declined then we load any tracking which doesn't break GDPR rules.

## Configuration 
- Header
- Copy
- Accept Button Text
- Decline Button Text

### WordPress dashboard
The copy is managed within Dashboard > Site Configuration

## WordPress Theme
Main template:
- ```THEME/templates/global-gdpr.php```

## Scss files
Main file:
- ```src/scss/components/_gdpr.scss```

## JS Files
- ```src/js/gdpr.js```

Also be aware that the cookies are set using ```js/libs/js.cookie.js```.
