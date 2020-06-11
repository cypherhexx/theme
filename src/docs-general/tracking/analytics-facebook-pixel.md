# Facebook Pixel

> How we setup Facebook Pixel

- Facebook Pixel should only be fired from [Google Tag Manager](./tracking/analytics-google-tag-manager.md) based on the cookies set by the [GDPR](./gdpr.md) script 
- The default setting should have ```fbq('consent', 'revoke');```
- After accepting GDPR this can be changed to ```fbq('consent', 'grant');```

Read more: <https://developers.facebook.com/docs/facebook-pixel/implementation/gdpr/>

## FB Pixel setup within Google Tag Manager
Make sure you have set up the User-Defined Variables and Triggers as per the instructions in the [Google Tag Manager](./tracking/analytics-google-tag-manager.md) documentation.

Now you need to set up the following tags

| Name | Type | Firing Triggers |
|----------|-------------|----------|
| GDPR - Accept - FB Pixel | Custom HTML | GDPR - Accepted |
| GDPR - Decline - FB Pixel | Custom HTML | GDPR - Declined |
| GDPR - Undefined - FB Pixel | Custom HTML | GDPR - Undefined |

The custom HTML you'll find below for each trigger, make sure you update the ID which should be provided by the producer.

### GDPR - Accept - FB Pixel
```
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('consent', 'grant');
fbq('init', 'XXXXXXXXXXXXXXXXXXX');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=XXXXXXXXXXXXXXXXXXX&amp;ev=PageView&amp;noscript=1"
/></noscript>
```

### GDPR - Decline - FB Pixel & GDPR - Undefined - FB Pixel
```
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('consent', 'revoke');
fbq('init', 'XXXXXXXXXXXXXXXXXXX');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=XXXXXXXXXXXXXXXXXXX&amp;ev=PageView&amp;noscript=1"
/></noscript>
```

Double chek that you have included ```fbq('consent', 'revoke');``` in both GDPR - Decline - FB Pixel & GDPR - Undefined - FB Pixel. 
