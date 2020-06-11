# Google Tag Manager

> How we use Google Tag Manager (GTM)

We use GMT to keep sites GDPR compliant. The GTM code should be the only tracking code which is hard coded into the site, via the custom site plugin

You'll find commented out GTM code in the following file.

```plugin/custom-plugin-name/custom/tracking-gtm.php```

Once you get the ID from the producer, make sure this is included via this file.

No extra tracking should be added via any code in WordPress, all tracking has to be set in GTM so that it remains GDPR compliant.

### User-Defined Variables:
You need to set up 2 user-defined variables within GTM. These 2 variables relate to the cookies set by the GDPR script.

| Name | Type |
|----------|-------------|
| GDPR - Cookie - Accept | 1st-Party Cookie |
| GDPR - Cookie - Decline | 1st-Party Cookie |


### Triggers:
You also need to set up the following triggers.

| Name | Fires on | conditions |
|----------|-------------|----------|
| GDPR - Accepted | Some page views | GDPR - Cookie - Accept equals accepted & GDPR - Cookie - Decline equals accepted |
| GDPR - Declined | Some page views | GDPR - Cookie - Accept equals declined & GDPR - Cookie - Decline equals declined |
| GDPR - Undefined | Some page views | GDPR - Cookie - Accept equals undefined & GDPR - Cookie - Decline equals undefined |
